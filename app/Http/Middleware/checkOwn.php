<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class checkOwn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $pageUser = $request->member;
        if($user == $pageUser || $user->admin)
        {
            return $next($request);
        }
        else{
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
        
    }
}
