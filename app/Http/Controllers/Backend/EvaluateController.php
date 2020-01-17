<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluate_Tasks;
use App\Models\Met_evaluates;
use App\Jobs\Met_Evaluate;
use App\Services\EvaluateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;


class EvaluateController extends Controller
{
    protected $EvaluateService;
    protected $redis;

    public function __construct(EvaluateService $EvaluateService)
    {
        $this->EvaluateService = $EvaluateService;
        $this->redis = app('redis.connection');
    }

    function index()
    {
        $Evaluate_List = Met_evaluates::where('Finish', 1)->get();
        $First_unFinish = Met_evaluates::where('Finish', 0)->orderBy('created_at')->first();
        if ($First_unFinish !== null) {
            $unFinish_List = Met_evaluates::where('Finish', 0)->whereNotIn('id', [$First_unFinish->id])->orderBy('created_at')->get();
        } else {
            $unFinish_List = Met_evaluates::where('Finish', 0)->orderBy('created_at')->get();
        }
        return view('backend.Evaluate', compact('Evaluate_List', 'First_unFinish', 'unFinish_List'));
    }

    function evaluate(Request $request)
    {
        $year = $request->year;
        $start_month = $request->start_month;
        $end_month   = $request->end_month;
        if($start_month>$end_month)
        {
            return redirect(route('admin.Evaluate'))->with('error', '您輸入的時間有誤'); 
        }
         

        $end = date("Y-m-d", strtotime("{$year}-{$end_month}-01"));
        $end = date("Y-m-d", strtotime("{$end} + 1 month - 1day "));

        $start = date("Y-m-d", strtotime("{$year}-{$start_month}-01"));


        // $start = $request->start;
        // $end   = $request->end;
        $evaluate_exist = Met_evaluates::where('Time_Period', $start . '_' . $end)->first();
        if ($evaluate_exist == null) {
            $now = date("Y-m-d-H-i-s");
            $rootdir = public_path() . "\MetData\\";
            $this->EvaluateService->Met_Evaluate($now, $start, $end, $rootdir);
            $period = ((int) substr($end, 5, 2) - (int) substr($start, 5, 2)) + 1; #經過幾個月
            $path = public_path() . "\MetData\Evaluate\\" . $now . '_' . $start . "-" . $end . "\\" . $start . '_' . $end . '_evaluate.xlsx';

            $Evaluate_task = new Met_evaluates([
                'Time_Period' => $start . '_' . $end,
                'Path' => $path,
                'Execution_Time' => $period * 210
            ]);
            Auth::user()->Met_evaluates()->save($Evaluate_task);

            $period = ((int) substr($end, 5, 2) - (int) substr($start, 5, 2)) + 1; #經過幾個月

            $unFinish_List = Met_evaluates::where('Finish', 0)->orderBy('created_at')->get();
            $waitTime = 0;
            foreach ($unFinish_List as $Job) {
                $waitTime += $this->redis->ttl($Job->Time_Period);
            }

            $this->redis->set($start . '_' . $end, 'processing');
            $this->redis->expire($start . '_' . $end, ($period * 210) + $waitTime);

            return redirect(route('admin.Evaluate'));
        }

        return redirect(route('admin.Evaluate'))->with('error', '此段時間已有資料請直接下載');
    }

    function download(Request $request, $Time_Period)
    {
        $Eva_data = Met_evaluates::where('Time_Period', $Time_Period)->first();
        $Path = $Eva_data->Path;
        $Path = explode("\\", $Path);
        array_pop($Path);
        $Path = join("\\", $Path) . "\\Result\\";
        $zip_fileName = 'TWSimEvaFile.zip';
        $zip_file = $this->zipfile($zip_fileName, $Path);
        return response()->download($zip_file);
    }

    function zipfile($zipFileName, $Path)
    {
        $zip_file = $zipFileName;
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $predir = Null;
        $this->addFileToZip($Path, $zip, $predir);
        $zip->close();
        return $zip_file;
    }

    function addFileToZip($Path, $zip, $predir)
    {
        $datas = scandir($Path);
        foreach ($datas as $data) {
            if ($data != "." && $data != "..") {
                if (!is_dir($Path . $data)) {
                    if (is_null($predir)) {
                        $download = $Path . $data;
                        $zip->addFile($download, $data);
                    } else {
                        $download = $Path . $data;
                        $zip->addFile($download, $predir . '\\' . $data);
                    }
                } else {
                    $this->addFileToZip($Path . $data . '\\', $zip, $data);
                }
            }
        }
    }




    function delete(Request $request, Met_evaluates $Met_eva)
    {
        $Path = $Met_eva->Path;
        $Path = explode("\\", $Path);
        array_pop($Path);
        $Path = join("\\", $Path) . "\\";
        $this->deldir($Path);
        @rmdir($Path);
        // unlink($Met_eva->Path);
        $Met_eva->delete();
        return [$Path];
    }

    function deldir($path)
    {
        //如果是目錄則繼續
        if (is_dir($path)) {
            //掃描一個資料夾內的所有資料夾和檔案並返回陣列
            $p = scandir($path);
            foreach ($p as $val) {
                // 排除目錄中的.和..
                if ($val != "." && $val != "..") {
                    //如果是目錄則遞迴子目錄，繼續操作
                    if (is_dir($path . $val)) {
                        //子目錄中操作刪除資料夾和檔案
                        $this->deldir($path . $val . '/');
                        //目錄清空後刪除空資料夾
                        @rmdir($path . $val . '/');
                    } else {
                        //如果是檔案直接刪除
                        unlink($path . $val);
                    }
                }
            }
        }
    }
}
