<?php

namespace App\Http\Middleware;

use App\Enum\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return (user()->hasAnyRole([Role::ADMIN, Role::OWNER]))
                ? redirect()->route('castle.users.index')
                : redirect('/home');
        }

        return $next($request);
    }
}
