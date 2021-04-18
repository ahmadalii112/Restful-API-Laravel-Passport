<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthBasic
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        # onceBasic => this is HTTP basic Authentication without setting a user identifier cookie in the session
        if (Auth::onceBasic()) {
            return response()->json(['message'=>'Authentication Failed'],401); # Status 401 (Unauthorized)
        } else {
            return $next($request);
        }
    }
}
