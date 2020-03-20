<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class getOutNotAdmin
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
        if(!!!$user->admin){
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404);
        }
        return $next($request);
    }
}
