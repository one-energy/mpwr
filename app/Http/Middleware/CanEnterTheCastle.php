<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CanEnterTheCastle
{
    public function handle($request, Closure $next)
    {
        abort_unless(user()->isMaster(), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
