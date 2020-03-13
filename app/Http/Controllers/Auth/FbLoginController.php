<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Socialite;

class FbLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();
        $user = $this->createUser($getInfo, $provider);
        auth()->login($user);
        return redirect()->route('Met.Evaluate');
        // return Redirect::to('https://localhost/php/bkLaravel_2/public/Met/img');
    }

    function createUser($getInfo, $provider)
    {
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'username' => $getInfo->name,
                'email'    => $getInfo->email,
                'password' => $getInfo->id,
                'active' => 'active',
                'level' => 3,
                'provider' => $provider,
                'provider_id' => $getInfo->id
            ]);
        }
        return $user;
    }
}
