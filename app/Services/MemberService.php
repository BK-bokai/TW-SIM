<?php

namespace App\Services;

// use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class MemberService
{
    /**
     * 將$request的內容與$user比較，看是否有更改以及是否與其他資料重複
     */
    public function checkUser($request, $user)
    {
        $check = [];
        $request->admin = $request->admin? 1:0 ;
        /**
         * 判斷除了此使用者外，是否有其他使用者使用此信箱，與使用者名稱。
         */
        $users_name = User::where('id', '!=', $user->id)
            ->where('name', '=', $request->name)->get();
        $users_email = User::where('id', '!=', $user->id)
            ->where('email', '=', $request->email)->get();

        $check['canChange'] = True;

        if (count($users_name) == 0 && count($users_email) == 0) {
            $check['repeat'] = 0;
        } else {
            $check['repeat'] = 1;
            $check['repeat_name'] = (count($users_name) > 0) ? 1 : 0;
            $check['repeat_email'] = (count($users_email) > 0) ? 1 : 0;
            $check['canChange'] = False;
        }

        /**
         * 判斷是否有更改過資料
         */
        if (
            $request->name != $user->name ||
            $request->email != $user->email ||
            $request->admin != $user->admin
        ) {
            $check['change'] = 1;
        } else {
            $check['change'] = 0;
            $check['canChange'] = False;
        }

        return $check;
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            // 'admin' =>['required_without_all:admin']
        ], [
            'name.required'    => '請輸入帳號。',
            'email.email'    => '請輸入正確的信箱。',
            'email.required'    => '請輸入信箱。',
        ]);
    }

    public function passwordValidator(array $data)
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

    public function resetPassword($member, $password)
    {
        $member->password = $password;
        $member->save();
        return true;
    }

}