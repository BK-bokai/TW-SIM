<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class MemberController extends Controller
{
    public function index(){
        // $members = User::where('provider','!=','facebook')->get();
        // $members = User::whereNull('provider')->get();
        // $members = User::whereNOTIn('provider',['facebook'])->get();
        $members = User::all();
        return view('Member.memberList',compact('members'));
    }

    public function memberPage(Request $request,User $member)
    {
        // return $member;
        return view('Member.memberPage',compact('member'));
    }
}
