<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return (user()->role == "Admin" || user()->role == "Owner") ? redirect()->route('castle.users.index') : redirect('/home');
        }

        return $next($request);
    }
}
