<?php

namespace App\Services;

// use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use App\Models\User;

class LoginService
{

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateLogin(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'name.required'    => '請輸入帳號。',
                'password.required' => '請輸入密碼。',
            ]
        );
    }



}
