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
        $Evaluate_List = Met_evaluates::all();
        // foreach($Evaluate_List as $Job)
        // {
        //     if(!!$Job->Finish)
        //     {
        //         $this->redis->del($Job->Tiem_Period);
        //     }
        // }
        return view('backend.Evaluate', compact('Evaluate_List'));
    }

    function evaluate(Request $request)
    {
        $year = $request->year;
        $start_month = $request->start_month;
        $end_month   = $request->end_month;

        $end = date("Y-m-d",strtotime("{$year}-{$end_month}-01"));
        $end = date("Y-m-d",strtotime("{$end} + 1 month - 1day "));

        $start = date("Y-m-d",strtotime("{$year}-{$start_month}-01"));


        // $start = $request->start;
        // $end   = $request->end;
        $evaluate_exist = Met_evaluates::where('Time_Period', $start.'_'.$end)->first();
        if ($evaluate_exist == null)
        {
            $now = date("Y-m-d-H-i-s");
            $rootdir = public_path()."\MetData\\";
            // $this->EvaluateService->Met_Evaluate($now,$start,$end,$rootdir);
            
            $path = public_path(). "\MetData\Evaluate\\".$now.'_'.$start."-".$end."\\".$start.'_'.$end.'_evaluate.xlsx';

            $Evaluate_task = new Met_evaluates([
                'Time_Period' => $start.'_'.$end,
                'Path' => $path,
            ]);
            Auth::user()->Met_evaluates()->save($Evaluate_task);

            $period = ((int)substr($end, 5, 2)-(int)substr($start, 5, 2))+1;#經過幾個月

            $Evaluate_List = Met_evaluates::all();
            $waitTime = 0;
            foreach($Evaluate_List as $Job)
            {
                if(!$Job->Finish)
                {
                    $waitTime += $this->redis->ttl($Job->Time_Period);
                }
            }

            $this->redis->set($start.'_'.$end,'processing');
            $this->redis->expire($start.'_'.$end,($period*70)+$waitTime);

            return redirect(route('admin.Evaluate'));
        }

        return redirect(route('admin.Evaluate'))->with('error', '此段時間已有資料請直接下載');
    }

    function download(Request $request,$Time_Period)
    {        
        $Eva_data = Met_evaluates::where('Time_Period',$Time_Period)->first();
        return response()->download($Eva_data->Path);
    }
}
