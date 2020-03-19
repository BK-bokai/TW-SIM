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
use App\Services\MetDataService;
use Auth;

class MetDataController extends Controller
{

    public function __construct(MetDataService $MetDataService)
    {
        $this->MetDataService = $MetDataService;
    }

    public function getnum($datas)
    {
        for ($i = 1; $i <= 12; $i++) {
            $zero12[$i] = 0;
        }

        for ($year = 2016; $year <= (int) date("Y"); $year++) {
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
        $user = Auth::user();
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
            'simnum_wd',
            'user'
        ));
    }

    public function MetMonthData(Request $request, $year, $month, $datatype, $var)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $check = $this->MetDataService->check_date_and_data($year, $month, $datatype, $var);
        if ($check) {
            return $this->MetDataService->get_Month_Data($year, $month, $datatype, $var, $num);
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
    }


    public function MetUpload(Request $request, $year, $month, $datatype, $var)
    {
        if ($this->MetDataService->CheckUpload($request, $year, $month, $datatype, $var) !== 'isok') {
            return $this->MetDataService->CheckUpload($request, $year, $month, $datatype, $var);
        }
        $this->MetDataService->Met_Create($request, $year, $month, $datatype, $var);
        return redirect(route('Met.MetMonthData', ['year' => $year, 'month' => $month, 'datatype' => $datatype, 'var' => $var]));
    }



    function download(Request $request, $data, $datatype, $var)
    {
        if ($datatype == 'Obs') {
            switch ($var) {
                case "T2":
                    $Metdata = Met_obsdata_t2::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
                case "WS":
                    $Metdata = Met_obsdata_ws::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
                case "WD":
                    $Metdata = Met_obsdata_wd::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
            }
        } elseif ($datatype == 'Sim') {
            switch ($var) {
                case "T2":
                    $Metdata = Met_simdata_t2::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
                case "WS":
                    $Metdata = Met_simdata_ws::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
                case "WD":
                    $Metdata = Met_simdata_wd::where('id', $data)->first();
                    return response()->download(public_path() . $Metdata->Path);
                    break;
            }
        }
    }

    public function MetDelete(Request $request, $data, $datatype, $var)
    {
        if ($datatype == "Obs") {
            switch ($var) {
                case "T2":
                    $MetData = Met_obsdata_t2::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
                case "WS":
                    $MetData = Met_obsdata_ws::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
                case "WD":
                    $MetData = Met_obsdata_wd::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
            }
        } elseif ($datatype == "Sim") {
            switch ($var) {
                case "T2":
                    $MetData = Met_simdata_t2::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
                case "WS":
                    $MetData = Met_simdata_ws::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
                case "WD":
                    $MetData = Met_simdata_wd::where('id', $data)->first();
                    unlink(public_path() . $MetData->Path);
                    $MetData->delete();
                    break;
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
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WS') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_obsdata_ws::where('id', $dataID)->first();
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WD') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_obsdata_wd::where('id', $dataID)->first();
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
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
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WS') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_simdata_ws::where('id', $dataID)->first();
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
                    }
                    $zip->close();
                    return response()->download($zip_file);
                } elseif ($var == 'WD') {
                    foreach ($dataIDs as $dataID) {
                        $data = Met_simdata_wd::where('id', $dataID)->first();
                        $zip->addFile(public_path() . $data->Path, $data->Filename);
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
