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
use Illuminate\Support\Facades\Redis;

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
    protected $redis;

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
        $this->redis = app('redis.connection');
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
        $url =  route('confirm',['active'=>$activasion]);
        
        // $redis = app('redis.connection');
        $this->redis->set($request->name,$activasion);
        $this->redis->expire($request->name,300);

        $this->RegisterService->sendServer($user->email, $activasion, $url);
        
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('status', '已註冊成功，請於5分鐘內至信箱收取確認信件並完成確認動作');
    }

    public function confirm($active)
    {
        $user = User::where('active', $active)->first();

        if( !empty( $user) && $this->redis->get($user->name) )
        {  
            $user->active='active';
            $user->save();
            $this->redis->del($user->name);
            return redirect($this->redirectPath())->with('status', '帳號已啟動，請進行登入動作'); 
        }
        else
        {
            return redirect()->route('login');
        }
    }
}
