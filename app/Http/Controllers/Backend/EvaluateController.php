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
use Excel;
use App\Imports\MetEvaluateImport;

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
        $Evaluate_List = Met_evaluates::where('Finish', 1)->orderBy('Time_Period')->get();
        $First_unFinish = Met_evaluates::where('Finish', 0)->orderBy('created_at')->first();
        if ($First_unFinish !== null) {
            $unFinish_List = Met_evaluates::where('Finish', 0)->whereNotIn('id', [$First_unFinish->id])->orderBy('created_at')->get();
        } else {
            $unFinish_List = Met_evaluates::where('Finish', 0)->orderBy('created_at')->get();
        }
        return view('backend.Evaluate', compact('Evaluate_List', 'First_unFinish', 'unFinish_List'));
    }
    public function change_time_priod($Evaluate_List)
    {
        foreach ($Evaluate_List as $Evaluate) {
            $Time_Period = $Evaluate->Time_Period;
            $Evaluate->Time_Period = str_replace("_","至",$Time_Period);
        }
        return $Evaluate_List;
    }

    function detail(Request $request, Met_evaluates $Met_evaluates)
    {
        // return $Met_evaluates;
        $data = Excel::toArray(new MetEvaluateImport, public_path() . $Met_evaluates->Path);
        $id = $Met_evaluates->id;
        // return $data;
        $Time_Period = $Met_evaluates->Time_Period;
        return view('backend.detailEvaluate', compact('data', 'id', 'Time_Period'));
    }

    function detailImg(Request $request, $area, Met_evaluates $Met_evaluates)
    {
        // $Path = public_path() . $Met_evaluates->Path;
        // $Path = explode("\\", $Path);
        // $Path = join("\\", array_slice($Path, 7, 4)) . '\\imgs';

        $Path = $Met_evaluates->Path;
        $Path = explode("\\", $Path);
        $Path = join("\\", array_slice($Path, 1, 4)) . '\\imgs';
        // array_pop($Path);
        // $Path = join("\\", $Path) . '\\imgs';
        // return $Path;
        $T2Path = $Path . '\\T2';
        $WSPath = $Path . '\\WS';
        $WDPath = $Path . '\\WD';
        foreach (scandir($T2Path) as $imgs) {
            if (strpos($imgs, $area)) {
                $T2img = $T2Path . '\\' . $imgs;
            }
        }
        foreach (scandir($WSPath) as $imgs) {
            if (strpos($imgs, $area)) {
                $WSimg = $WSPath . '\\' . $imgs;
            }
        }
        foreach (scandir($WDPath) as $imgs) {
            if (strpos($imgs, $area)) {
                $WDimg = $WDPath . '\\' . $imgs;
            }
        }
        $id = $Met_evaluates->id;
        $Time_Period = $Met_evaluates->Time_Period;
        return view('backend.DetailImglEvaluate', compact('T2img', 'WSimg', 'WDimg', 'id', 'area', 'Time_Period'));
    }

    function evaluate(Request $request)
    {
        $year = $request->year;
        $start_month = $request->start_month;
        $end_month   = $request->end_month;
        if ($start_month > $end_month) {
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
            // $path = public_path() . "\MetData\Evaluate\\" . $now . '_' . $start . "-" . $end . "\\Result\\" . $start . '_' . $end . '_evaluate.xlsx';
            $path = "\MetData\Evaluate\\" . $now . '_' . $start . "-" . $end . "\\Result\\" . $start . '_' . $end . '_evaluate.xlsx';
            $Execution_Time = 150;
            $Evaluate_task = new Met_evaluates([
                'Time_Period' => $start . '_' . $end,
                'Path' => $path,
                'Execution_Time' => $period * $Execution_Time
            ]);
            Auth::user()->Met_evaluates()->save($Evaluate_task);

            $period = ((int) substr($end, 5, 2) - (int) substr($start, 5, 2)) + 1; #經過幾個月

            $unFinish_List = Met_evaluates::where('Finish', 0)->orderBy('created_at')->get();
            $waitTime = 0;
            foreach ($unFinish_List as $Job) {
                $waitTime += $this->redis->ttl($Job->Time_Period);
            }

            $this->redis->set($start . '_' . $end, 'processing');
            $this->redis->expire($start . '_' . $end, ($period * $Execution_Time) + $waitTime);

            return redirect(route('admin.Evaluate'));
        }

        return redirect(route('admin.Evaluate'))->with('error', '此段時間已有資料請直接下載');
    }

    function download(Request $request, $Time_Period)
    {
        $Eva_data = Met_evaluates::where('Time_Period', $Time_Period)->first();
        $Path = public_path() . $Eva_data->Path;
        $Path = explode("\\", $Path);
        array_pop($Path);
        $Path = join("\\", $Path) . '\\';
        // return $Path;
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
        $Path = public_path() . $Met_eva->Path;
        $Path = explode("\\", $Path);
        array_pop($Path);
        array_pop($Path);//得到前兩層資料夾
        $Path = join("\\", $Path) . "\\";

        $command = "rd ${Path} /s /q ";
        exec($command);
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
                        @rmdir($path);
                    }
                }
            }
        }
    }
}
