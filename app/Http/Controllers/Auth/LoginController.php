<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $LoginService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    protected function redirectTo()
    {
        return route('Met.Evaluate');
    }

    public function username()
    {
        return 'name';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoginService $LoginService)
    {
        $this->middleware('guest')->except('logout');
        $this->LoginService = $LoginService;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function attemptLogin(Request $request)
    {
        $active_user = User::where($this->username(), $request->name)
            ->where('active', 'active')->first();
        if ($active_user !== null) {

            return $this->guard()->attempt(
                $this->credentials($request),
                $request->filled('remember')
            );
        }

        return false;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        // $errors = [$this->username() => trans('auth.failed')];
        $errors = [$this->username() => '請確認帳號密碼'];
        // Load user from database
        $user = User::where($this->username(), $request->{$this->username()})->first();
        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->active != 'active') {
            $errors = [$this->username() => '此帳號並未被啟動，若已完成註冊動作，請確認信箱!'];
        };
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        };
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    public function login(Request $request)
    {
        $this->LoginService->validateLogin($request);

        //$this裡面是否存在'hasTooManyLoginAttempts'方法，查詢帳號是否被鎖
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            //如果帳號被鎖就回傳被鎖的訊息
            return $this->sendLockoutResponse($request);
        }


        if ($this->attemptLogin($request)) {
            //使用上面attemptLogin方法，若回傳true則導入後台
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.

        //若上面沒登入成功，則會增加登入失敗次數
        $this->incrementLoginAttempts($request);
        //並且導入登入頁面
        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
