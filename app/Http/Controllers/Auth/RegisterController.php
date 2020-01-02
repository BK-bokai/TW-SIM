<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Services\RegisterService;
use Illuminate\Foundation\Bootstrap\RegisterFacades;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';
    protected $RegisterService;

    protected function redirectTo()
    {
        return route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegisterService $RegisterService)
    {
        $this->middleware('guest');
        $this->RegisterService = $RegisterService;
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => $data['active'],
        ]);
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user_name    = User::where('name', $request->name)->first();
        $user_email   = User::where('email', $request->email)->first();

        if ($user_name != null || $user_email != null) 
        {
            return $this->RegisterService->confirm($request);
        }
        // 製作active_token
        $activasion = md5(uniqid(rand(), true));
        $request['active'] = $activasion;


        $this->RegisterService->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

    //     // $this->RegisterService->send($activasion,$request);

    //     $this->RegisterService->sendServer($user->email, $activasion);
        
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('status', '已註冊成功，請至信箱收取確認信件');;
    }
}
