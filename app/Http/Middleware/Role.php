<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;

class Role
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role === '' || $role === null) {
            throw new InvalidArgumentException('You need to inform at least one role!');
        }

        $roles = array_filter(explode('|', $role), fn ($role) => trim($role));

        if (count($roles) < 1) {
            throw new InvalidArgumentException('You need to inform at least one valid role!');
        }

        abort_if(!user()->hasAnyRole($roles), Response::HTTP_NOT_FOUND);

        return $next($request);
    }
}
