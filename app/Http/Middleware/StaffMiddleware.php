<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
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
        $role = Auth::user()->role;

        if($role == 'STAFF' || $role == 'ADMINISTRATOR'){
            return $next($request);
        }

        abort(403);
    }
}
