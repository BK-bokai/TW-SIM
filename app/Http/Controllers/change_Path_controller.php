<?php

namespace App\Http\Controllers;

use App\Models\Met_obsdata_t2;
use App\Models\Met_obsdata_wd;
use App\Models\Met_obsdata_ws;
use App\Models\Met_simdata_t2;
use App\Models\Met_simdata_wd;
use App\Models\Met_simdata_ws;
use Illuminate\Http\Request;

class change_Path_controller extends Controller
{
    public function index(){
        $Met_obsdata_t2 = $this->change_Path(Met_obsdata_t2::all());
        $Met_obsdata_ws = $this->change_Path(Met_obsdata_ws::all());
        $Met_obsdata_wd = $this->change_Path(Met_obsdata_wd::all());
        $Met_simdata_t2 = $this->change_Path(Met_simdata_t2::all());
        $Met_simdata_ws = $this->change_Path(Met_simdata_ws::all());
        $Met_simdata_ws = $this->change_Path(Met_simdata_ws::all());
        return $Met_obsdata_t2;

    }

    public function change_Path($datas){
        foreach ($datas as $data) {
            $Path = explode("\\", $data->Path);
            if(count($Path)  > 7){
                $data->Path = '\\'.join("\\", array_slice($Path, 7, 4));
                $data->save();
            }
        }
        return $datas;
    }
}
