<?php

namespace App\Services;

use App\Models\Met_obsdata_t2;
use App\Models\Met_obsdata_ws;
use App\Models\Met_obsdata_wd;
use App\Models\Met_simdata_t2;
use App\Models\Met_simdata_ws;
use App\Models\Met_simdata_wd;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

class MetDataService
{
    public function get_Month_Data($year, $month, $datatype, $var, $num)
    {
        $user = Auth::user();
        if ($datatype == 'Obs') {
            if ($var == 'T2') {
                $datas = Met_obsdata_t2::where('year', $year)->where('month', $month)->orderBy('date')->get();
            } elseif ($var == 'WS') {
                $datas = Met_obsdata_ws::where('year', $year)->where('month', $month)->orderBy('date')->get();
            } elseif ($var == 'WD') {
                $datas = Met_obsdata_wd::where('year', $year)->where('month', $month)->orderBy('date')->get();
            }
        }
        if ($datatype == 'Sim') {
            if ($var == 'T2') {
                $datas = Met_simdata_t2::where('year', $year)->where('month', $month)->orderBy('date')->get();
            } elseif ($var == 'WS') {
                $datas = Met_simdata_ws::where('year', $year)->where('month', $month)->orderBy('date')->get();
            } elseif ($var == 'WD') {
                $datas = Met_simdata_wd::where('year', $year)->where('month', $month)->orderBy('date')->get();
            }
        }
        return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num','user'));

    }

    public function check_date_and_data($year, $month, $datatype, $var)
    {
        $dataType_Arr = ['Sim', 'Obs'];
        $var_Arr = ['T2', 'WS', 'WD'];

        if ((int) $year >= (int) date("Y") && (int) $month > (int) date("m")) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        } elseif ((int) $month > 12 || !in_array($var, $var_Arr, true) || !in_array($datatype, $dataType_Arr, true)) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        } else {
            return TRUE;
        }
    }


    public function CheckUpload(Request $request, $year, $month, $datatype, $var)
    {
        
        $msg = ['T2' => '溫度', 'WS' => '風速', 'WD' => '風向'];
        $errors=[];
        $error = false;


        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $time = date("Y-m", strtotime("{$year}-{$month}-01"));

                if ($datatype == 'Obs') {
                    $path = public_path() . "\MetData\Obs\\$var\\";
                    $data_Time = substr($fileName, 0, 7);
                    if(count(explode('obs',$fileName))==1){
                        $errors['datatype']="請確認您上傳的資料是否為觀測資料!";
                        $error = TRUE;
                    }

                } elseif ($datatype == 'Sim') {
                    $path = public_path() . "\MetData\Sim\\$var\\";
                    $data_Time = substr($fileName, 11, 7);
                    if(count(explode('wrfout',$fileName))==1){
                        $errors['datatype']="請確認您上傳的資料是否為模擬資料!";
                        $error = TRUE;
                    }
                }

                if(count(explode($var,$fileName))==1){
                    $errors['var']="請確認您上傳的資料是否為{$msg[$var]}!";
                    $error = TRUE;
                }
                if (is_file($path . $fileName)) {
                    $errors['files']='請勿上傳系統已有的資料!';
                    $error = TRUE;
                }
                if ($data_Time !== $time) {
                    $lastYear = (string) ((int) $year - 1);
                    $nextYear = (string) ((int) $year + 1);
                    $errors['time']='請確認上傳的檔案日期';
                    $error = TRUE;
                }
            }
        }
        else{
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }

        if($error){
            return redirect()->back()
            ->withErrors($errors);
        }
        else{
            return 'isok';
        }
    }

    public function Met_Create(Request $request, $year, $month, $datatype, $var)
    {
        foreach ($request->file('files') as $file) {
            $fileName = $file->getClientOriginalName();
            $path = "\MetData\\$datatype\\$var\\";
            $time = date("Y-m", strtotime("{$year}-{$month}-01"));
            $file->move(public_path() . $path, $fileName);
            $create_Data = [
                'Filename' => $fileName,
                'Path'  => $path . $fileName,
                'year' => $year,
                'month' => $month,
            ];

            if ($datatype == 'Obs') {
                $create_Data['day'] = substr($fileName, 8, 2);
                $create_Data['date'] = substr($fileName, 0, 10);
                switch ($var) {
                    case "T2":
                        Met_obsdata_t2::create($create_Data);
                        break;
                    case "WS":
                        Met_obsdata_ws::create($create_Data);
                        break;
                    case "WD":
                        Met_obsdata_wd::create($create_Data);
                        break;
                }
            } elseif ($datatype == 'Sim') {
                $create_Data['day'] = substr($fileName, 19, 2);
                $create_Data['date'] = substr($fileName, 11, 10);
                switch ($var) {
                    case "T2":
                        Met_simdata_t2::create($create_Data);
                        break;
                    case "WS":
                        Met_simdata_ws::create($create_Data);
                        break;
                    case "WD":
                        Met_simdata_wd::create($create_Data);
                        break;
                }
            }
        }
    }

}
