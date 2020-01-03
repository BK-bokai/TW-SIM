<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluate_tasks;

class EvaluateController extends Controller
{
    function index()
    {
        $Evaluate_List = Evaluate_tasks::all();
        return view('backend.Evaluate', compact('Evaluate_List'));
    }

    function evaluate(Request $request)
    {
        return $request;
    }
}
