<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth($guard)->guest()) {
            return response()->json(['message' => 'Unauthorized!'], 401);
        }

        return $next($request);
    }
}
