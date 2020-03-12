<?php

namespace App\Http\Controllers\Meteorology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Met_obsdata_t2;
use App\Models\Met_obsdata_ws;
use App\Models\Met_obsdata_wd;
use App\Models\Met_simdata_t2;
use App\Models\Met_simdata_ws;
use App\Models\Met_simdata_wd;

class MetDataController extends Controller
{


    public function getnum($datas)
    {
        for ($i = 1; $i <= 12; $i++) {
            $zero12[$i] = 0;
        }

        for ($year=2016; $year <= (int)date("Y") ; $year++) { 
            $datanum[$year] = $zero12;
            foreach ($datas as $data) {
                for ($i = 1; $i <= 12; $i++) {
                    if ($data->month == $i && $data->year == $year) {
                        $datanum[$year][$i] += 1;
                    }
                }
            }
        }

        return $datanum;
    }
    public function index(Request $request)
    {
        $obs_t2 = Met_obsdata_t2::all();
        $sim_t2 = Met_simdata_t2::all();
        $obs_ws = Met_obsdata_ws::all();
        $sim_ws = Met_simdata_ws::all();
        $obs_wd = Met_obsdata_wd::all();
        $sim_wd = Met_simdata_wd::all();


        $obsnum_t2 = $this->getnum($obs_t2);
        $simnum_t2 = $this->getnum($sim_t2);
        $obsnum_ws = $this->getnum($obs_ws);
        $simnum_ws = $this->getnum($sim_ws);
        $obsnum_wd = $this->getnum($obs_wd);
        $simnum_wd = $this->getnum($sim_wd);

        return view('Meteorology.MetData', compact(
            'obs_t2',
            'sim_t2',
            'obs_ws',
            'sim_ws',
            'obs_wd',
            'sim_wd',
            'obsnum_t2',
            'simnum_t2',
            'obsnum_ws',
            'simnum_ws',
            'obsnum_wd',
            'simnum_wd'
        ));
    }

    public function MetMonthData(Request $request, $year, $month, $datatype, $var)
    {
        $this->check_date_and_data($year,$month,$datatype,$var);

        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);


        if ($datatype == 'Obs') {
            if ($var == 'T2') {
                $datas = Met_obsdata_t2::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            } elseif ($var == 'WS') {
                $datas = Met_obsdata_ws::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            } elseif ($var == 'WD') {
                $datas = Met_obsdata_wd::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            }
        }
        if ($datatype == 'Sim') {
            if ($var == 'T2') {
                $datas = Met_simdata_t2::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            } elseif ($var == 'WS') {
                $datas = Met_simdata_ws::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            } elseif ($var == 'WD') {
                $datas = Met_simdata_wd::where('year', $year)->where('month', $month)->orderBy('date')->get();
                return view('Meteorology.MetMonthData', compact('datas', 'year', 'month', 'datatype', 'var', 'num'));
            }
        }
    }

    public function check_date_and_data($year,$month,$datatype,$var)
    {
        $dataType_Arr = ['Sim', 'Obs'];
        $var_Arr = ['T2', 'WS', 'WD'];

        if ((int)$year>=(int)date("Y") && (int)$month > (int)date("m")){
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
        elseif((int)$month>12 || !in_array($var, $var_Arr, true) || !in_array($datatype, $dataType_Arr, true)){
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
    }

    public function ObsCheckUpload(Request $request, $year, $month, $datatype, $var)
    {
        $msg = ['T2' => '溫度', 'WS' => '風速', 'WD' => '風向'];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $path = public_path() . "\MetData\Obs\\$var\\";
                $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                if (substr($fileName, 11, 2) !== $var) {
                    $errors = ['files' => "請確認您上傳的資料是否為{$msg[$var]}!"];
                    return redirect()->back()
                        ->withErrors($errors);
                }

                if (is_file($path . $fileName)) {
                    $errors = ['files' => '請勿上傳系統已有的資料!'];
                    return redirect()->back()
                        ->withErrors($errors);
                }

                if (substr($fileName, 0, 7) !== $time) {
                    $lastYear = (string) ((int) $year - 1);
                    $nextYear = (string) ((int) $year + 1);
                    // if ($month == '1' && substr($fileName, 0, 10) == "{$lastYear}-12-31") {
                    //     continue;
                    // }
                    // if ($month == '12' && substr($fileName, 0, 10) == "{$nextYear}-01-01") {
                    //     continue;
                    // }
                    $errors = ['files' => '請確認上傳的檔案日期'];
                    return redirect()->back()
                        ->withErrors($errors);
                }
            }
            return 'isok';
        }
    }

    public function SimCheckUpload(Request $request, $year, $month, $datatype, $var)
    {
        $msg = ['T2' => '溫度', 'WS' => '風速', 'WD' => '風向'];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $path = public_path() . "\MetData\\$datatype\\$var\\";
                $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                if (substr($fileName, 22, 2) !== $var) {
                    $errors = ['files' => "請確認您上傳的資料是否為{$msg[$var]}!"];
                    return redirect()->back()
                        ->withErrors($errors);
                }

                if (is_file($path . $fileName)) {
                    $errors = ['files' => '請勿上傳系統已有的資料!'];
                    return redirect()->back()
                        ->withErrors($errors);
                }

                if (substr($fileName, 11, 7) !== $time) {
                    $lastYear = (string) ((int) $year - 1);
                    // if ($month == '1' && substr($fileName, 11, 10) == "{$lastYear}-12-31") {
                    //     continue;
                    // }
                    $errors = ['files' => '請確認上傳的檔案日期'];
                    return redirect()->back()
                        ->withErrors($errors);
                }
            }
            return 'isok';
        }
    }


    public function MetUpload(Request $request, $year, $month, $datatype, $var)
    {
        if ($datatype == 'Obs') {
            if ($this->ObsCheckUpload($request, $year, $month, $datatype, $var) !== 'isok') {
                return $this->ObsCheckUpload($request, $year, $month, $datatype, $var);
            }

            if ($var == 'T2') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_obsdata_t2::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 5, 2),
                        'date' => substr($fileName, 0, 10),
                    ]);
                }
            }

            if ($var == 'WS') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_obsdata_ws::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 5, 2),
                        'date' => substr($fileName, 0, 10),
                    ]);
                }
            }

            if ($var == 'WD') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_obsdata_wd::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 5, 2),
                        'date' => substr($fileName, 0, 10),
                    ]);
                }
            }


            return redirect(route('admin.MetMonthData', ['year' => $year, 'month' => $month, 'datatype' => $datatype, 'var' => $var]));
        } elseif ($datatype == 'Sim') {

            if ($this->SimCheckUpload($request, $year, $month, $datatype, $var) !== 'isok') {
                return $this->SimCheckUpload($request, $year, $month, $datatype, $var);
            }

            if ($var == 'T2') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_simdata_t2::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 19, 2),
                        'date' => substr($fileName, 11, 10),
                    ]);
                }
            }

            if ($var == 'WS') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_simdata_ws::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 19, 2),
                        'date' => substr($fileName, 11, 10),
                    ]);
                }
            }

            if ($var == 'WD') {
                foreach ($request->file('files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    // $path = public_path() . "\MetData\\$datatype\\$var\\";
                    $path = "\MetData\\$datatype\\$var\\";
                    $time = date("Y-m", strtotime("{$year}-{$month}-01"));
                    $file->move(public_path() .$path, $fileName);
                    Met_simdata_wd::create([
                        'Filename' => $fileName,
                        'Path'  => $path . $fileName,
                        'year' => $year,
                        'month' => $month,
                        'day'  => substr($time, 19, 2),
                        'date' => substr($fileName, 11, 10),
                    ]);
                }
            }


            return redirect(route('admin.MetMonthData', ['year' => $year, 'month' => $month, 'datatype' => $datatype, 'var' => $var]));
        }
    }

    function download(Request $request, $data, $datatype, $var)
    {
        if ($datatype == 'Obs') {
            if ($var == 'T2') {
                $zip_file = 'TWSimEvaFile.zip';
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $Metdata = Met_obsdata_t2::where('id', $data)->first();
                $zip->addFile(public_path() .$Metdata->Path, $Metdata->Filename);
                $zip->close();

                return response()->download($zip_file);
                // return response()->download($Metdata->Path);

            } elseif ($var == 'WS') {
                $Metdata = Met_obsdata_ws::where('id', $data)->first();
                return response()->download(public_path() .$Metdata->Path);
            } elseif ($var == 'WD') {
                $Metdata = Met_obsdata_wd::where('id', $data)->first();
                return response()->download(public_path() .$Metdata->Path);
            }
        } elseif ($datatype == 'Sim') {
            if ($var == 'T2') {
                $Metdata = Met_simdata_t2::where('id', $data)->first();
                return response()->download(public_path() .$Metdata->Path);
            } elseif ($var == 'WS') {
                $Metdata = Met_simdata_ws::where('id', $data)->first();
                return response()->download(public_path() .$Metdata->Path);
            } elseif ($var == 'WD') {
                $Metdata = Met_simdata_wd::where('id', $data)->first();
                return response()->download(public_path() .$Metdata->Path);
            }
        }
    }

    public function MetDelete(Request $request, $data, $datatype, $var)
    {
        if ($datatype == "Sim") {
            if ($var == "T2") {
                $MetData = Met_simdata_t2::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
            }
            if ($var == "WS") {
                $MetData = Met_simdata_ws::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
            }
            if ($var == "WD") {
                $MetData = Met_simdata_wd::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
            }
        }

        if ($datatype == "Obs") {
            if ($var == "T2") {
                $MetData = Met_obsdata_t2::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
                // $MetData = Met_obsdata_t2::all();
                // foreach($MetData as $data)
                // {
                //     $path = public_path() . "\MetData\\$datatype\\$var\\";
                //     $data->Path = $path.$data->Filename;
                //     $data->save();
                // }

            }
            if ($var == "WS") {
                $MetData = Met_obsdata_ws::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
            }
            if ($var == "WD") {
                $MetData = Met_obsdata_wd::where('id', $data)->first();
                unlink(public_path() .$MetData->Path);
                $MetData->delete();
            }
        }

        return [$data, $datatype, $var];
    }

    function Multiple(Request $request, $method, $datatype, $var)
    {
        $dataIDs = $request->DataID;

        if ($method == 'download') {
            if ($datatype == 'Obs') {
                $zip_file = 'TWSimEvaFile.zip';
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                if ($var == 'T2') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_obsdata_t2::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WS') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_obsdata_ws::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WD') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_obsdata_wd::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                }
            } elseif ($datatype == 'Sim') {
                $zip_file = 'TWSimEvaFile.zip';
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                if ($var == 'T2') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_simdata_t2::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WS') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_simdata_ws::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WD') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_simdata_wd::where('id', $dataID)->first();
                        $zip->addFile(public_path() .$data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                }
            }
        } elseif ($method == 'delete') {
            foreach ($dataIDs as $dataID) {
                $this->MetDelete($request, $dataID, $datatype, $var);
            }
            return redirect()->back();
        }
    }
}
