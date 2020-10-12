<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CanEnterTheCastle
{
    public function handle($request, Closure $next)
    {
        abort_unless((user()->role != 'Setter' && user()->role != 'Sales Rep'), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
