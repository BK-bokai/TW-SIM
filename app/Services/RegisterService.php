<?php

namespace App\Services;

// use App\Repositories\MemberRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\register;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendRegisterMail;

class RegisterService
{
    // protected $MemberRepo;
    // public function __construct(MemberRepository $MemberRepo)
    // {
    //     $this->MemberRepo = $MemberRepo;
    // }

    // public function GetAll()
    // {
    //     return $this->MemberRepo->GetAll();
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['same:password'],
        ], [
            'name.required'    => '請輸入帳號。',
            'email.email'    => '請輸入正確的信箱。',
            'email.required'    => '請輸入信箱。',
            'password.required' => '請輸入最少8碼的密碼。',
            'password.min' => '請輸入最少8碼的密碼。',
            'password_confirmation.same' => '兩次密碼不相同。',
            'password.confirmed' => '兩次密碼不相同。',
        ]);
    }

    /**
     * 確認資料庫是否有重複註冊
     */

    public function confirm($request)
    {
        $user_name    = User::where('name', $request->name)->first();
        $user_email   = User::where('email', $request->email)->first();
        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user_name != null || $user_email != null) {
            if ($user_name != null) {
                $errors = ['name' => '此使用者名稱已被使用過!'];
            };

            if ($user_email != null) {
                $errors = ['email' => '此信箱已被註冊!'];
            };

            if ($user_name != null && $user_email != null) {
                $errors = ['name' => '此使用者名稱已被使用過!', 'email' => '此信箱已被註冊!'];
            };

            return redirect()->back()
                ->withInput($request->only('user', 'email', 'remember'))
                ->withErrors($errors);
        };


        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        };

        return false;
    }

    public function sendServer($email,$activasion)
    {
        dispatch(new SendRegisterMail($email,$activasion));
    }
}
