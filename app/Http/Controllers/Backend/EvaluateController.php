<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluate_Tasks;
use App\Jobs\Met_Evaluate;
use App\Services\EvaluateService;
use Illuminate\Support\Facades\Auth;

class EvaluateController extends Controller
{
    protected $EvaluateService;

    public function __construct(EvaluateService $EvaluateService)
    {
        $this->EvaluateService = $EvaluateService;
    }

    function index()
    {
        $Evaluate_List = Evaluate_tasks::all();
        return view('backend.Evaluate', compact('Evaluate_List'));
    }

    function evaluate(Request $request)
    {
        $start = $request->start;
        $end   = $request->end;
        $evaluate_exist = Evaluate_Tasks::where('Time_Period', $start.'_'.$end)->first();
        if ($evaluate_exist == null)
        {
            $this->EvaluateService->Met_Evaluate($start,$end);
            $Evaluate_task = new Evaluate_Tasks([
                'Time_Period' => $start.'_'.$end,
            ]);
            Auth::user()->evaluate_tasks()->save($Evaluate_task);
            return redirect(route('admin.Evaluate'));
        }

        return redirect(route('admin.Evaluate'))->with('error', '此段時間已有資料請直接下載');
    }

    function download(Request $request,$Time_Period)
    {        
        $Eva_file = "D:\\bokai\\python\\python-code\\Evaluate_tool\\result\\{$Time_Period}\\{$Time_Period}_evaluate.xlsx";
        return response()->download($Eva_file);
        return $Time_Period;
    }
}
