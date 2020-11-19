<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CanEnterTheCastleRates
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
        abort_unless((user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager"), Response::HTTP_FORBIDDEN);
        
        return $next($request);
    }
}
