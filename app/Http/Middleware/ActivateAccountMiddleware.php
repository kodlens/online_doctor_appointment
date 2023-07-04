<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;

class ActivateAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $is_activate = Auth::user()->is_activate;

        if($is_activate > 0){
            return $next($request);
        }

        return redirect('/activate-account');


        //return $next($request);
    }
}
