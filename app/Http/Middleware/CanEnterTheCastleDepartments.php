<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CanEnterTheCastleDepartments
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
        abort_unless((user()->role == 'Admin' || user()->role == 'Owner'), Response::HTTP_FORBIDDEN);
        
        return $next($request);
    }
}
