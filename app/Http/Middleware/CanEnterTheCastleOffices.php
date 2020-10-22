<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CanEnterTheCastleOffices
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
        abort_unless((user()->role != 'Sales Rep' && user()->role != 'Setter'), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
