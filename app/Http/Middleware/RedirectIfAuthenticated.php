<?php

namespace App\Http\Middleware;

use App\Http\Cookies\JsonWebTokenCookie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie(JsonWebTokenCookie::NAME);
        if (empty($token)) {
            return $next($request);
        }

        try {
            JWTAuth::setToken($token);
            JWTAuth::authenticate();
        } catch (Throwable $_) {
            return $next($request);
        }

        return to_route('dashboard');
    }
}
