<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('APP_KEY');
        if ($token != 'ABCDEFGHI'){
            return response()->json(['message'=>'App Key Not Found'],401); # Status 401 (Unauthorized)
        }
        return $next($request);
    }
}
