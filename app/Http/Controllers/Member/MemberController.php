<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\MemberService;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    protected $MemberService;
    public function __construct(MemberService $MemberService)
    {
        $this->MemberService = $MemberService;
    }
    public function index()
    {
        $user = Auth::user();
        $members = User::all();
        return view('Member.memberList', compact('members', 'user'));
    }

    public function memberPage(Request $request, User $member)
    {
        $user = Auth::user();
        return view('Member.memberPage', compact('member', 'user'));
    }

    public function memberCheck(Request $request, User $member)
    {
        // 將$request的內容與$user比較，看是否有更改以及是否與其他資料重複
        $check = $this->MemberService->checkUser($request, $member);
        return $check;
    }

    public function memberUpdate(Request $request, User $member)
    {
        $this->MemberService->validator($request->all())->validate();
        $check = $this->MemberService->checkUser($request, $member);
        if ($check['canChange']) {
            $member->name = $request->name;
            $member->email = $request->email;
            $member->admin = $request->admin ? true : False;
            $member->save();
            return redirect(route('Member.memberPage', ['member' => $member->id]))
                ->with('status', "使用者資料已更新完畢");
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
    }

    public function memberUpdatePwdPage(Request $request, User $member)
    {
        $user = Auth::user();
        return view('Member.memberUpdatePwd', compact('user', 'member'));
    }

    public function memberUpdatePwd(Request $request, User $member)
    {
        $user = Auth::user();

        $this->MemberService->passwordValidator($request->all())->validate();
        

        $response = $this->MemberService->resetPassword(
            $member,
            // Hash::make($request->password)
            bcrypt($request->password)
            //與 Hash::make($request->password); 一樣
        );
        return redirect(route('Member.UpdatePwdPage', ['member' => $member->id]))
                ->with('status', $member->name.'的密碼已重置完畢');
    }
}
