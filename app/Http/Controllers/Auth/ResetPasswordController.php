<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $ResetPasswordService;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ResetPasswordService $ResetPasswordService)
    {
        $this->middleware('guest')->except('userReset');
        $this->ResetPasswordService = $ResetPasswordService;
    }

    protected function redirectTo()
    {
        return route('login');
    }

    public function showResetForm(Request $request, $reset_token = null)
    {
        $user = User::where('reset_token', $reset_token)->first();
        // return($user);
        if (!is_null($user)) {
            return view('auth.reset')->with(
                ['token' => $reset_token, 'email' => $request->email]
            );;
        } else {
            return redirect()->route('login');
        }
    }

    public function reset(Request $request)
    {
        $this->ResetPasswordService->validator($request->all())->validate();
        $user = User::where('email', $request->email)->where('reset_token', $request->token)->first();

        $response = $this->ResetPasswordService->resetPassword(
            $user,
            // Hash::make($request->password)
            bcrypt($request->password)
            //與 Hash::make($request->password); 一樣
        );
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }


    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', "密碼以重設完畢，您可進行登入動作");
    }


    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput()
            ->withErrors(['password' => "操厝有務請重新操作"]);
    }


    public function broker()
    {
        return Password::broker();
    }


}
