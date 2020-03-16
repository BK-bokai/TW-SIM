<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendResetMail;
use Illuminate\Http\Request;

class ResetPasswordService
{

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function validateEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => "請輸入正確的Email",
                'email.email' => "請輸入正確的Email",
            ]
        );
    }

    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink($email)
    {

        $user = User::where('email', $email)->first();
        $reset_token = md5(uniqid(rand(), true));

        //把token update到此user的資料庫裡面

        if (is_null($user)) {
            return false;
        } else {
            $user->reset_token = $reset_token;
            $user->save();

            $url = route('password.reset', ['reset_token' => "{$reset_token}?email={$email}"]);
            dispatch(new SendResetMail($email, $url));

            return true;
        }


        return false;
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['same:password'],
            'token' => 'required',
        ], [
            'email.email'    => '請經由正確管道重設密碼。',
            'email.required'    => '請經由正確管道重設密碼。',
            'password.required' => '請輸入最少8碼的密碼。',
            'password.min' => '請輸入最少8碼的密碼。',
            'password_confirmation.same' => '兩次密碼不相同。',
            'password.confirmed' => '兩次密碼不相同。',
            'token.required' => '請經由正確管道重設密碼。',

        ]);
    }

    public function userValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['same:password'],
        ], [
            'password.required' => '請輸入最少8碼的密碼。',
            'password.min' => '請輸入最少8碼的密碼。',
            'password_confirmation.same' => '兩次密碼不相同。',
            'password.confirmed' => '兩次密碼不相同。',
        ]);
    }



    public function resetPassword($user, $password)
    {
        $user->password = $password;
        $user->reset_token = 'have reseted';

        $user->save();
        return true;
    }
}
