<?php

namespace App\Http\Middleware;

use Closure;

class AuthIsAdmin
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
        if (!\Auth::check() || \Auth::user()->admin != \App\User::ADMIN) {
            return back();
        }
        return $next($request);
    }
}
