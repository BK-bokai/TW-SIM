<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    protected $ResetPasswordService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ResetPasswordService $ResetPasswordService)
    {
        $this->middleware('guest');
        $this->ResetPasswordService = $ResetPasswordService;
    }

    public function showLinkRequestForm()
    {
        return view('auth.ForgetPw');
    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', '已寄送密碼重製連結，請收取Email信件');
    }

    public function sendResetLinkEmail(Request $request)
    {
        //驗證email
        $this->ResetPasswordService->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $response = $this->ResetPasswordService->sendResetLink($request->email);


        //將發送Email返回的狀態送給前端
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
