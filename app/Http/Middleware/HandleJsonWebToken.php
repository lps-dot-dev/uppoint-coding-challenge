<?php

namespace App\Http\Middleware;

use App\Http\Cookies\JsonWebTokenCookie;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class HandleJsonWebToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie(JsonWebTokenCookie::NAME);
        if (is_string($token) === false) {
            throw new AuthenticationException(redirectTo: route('login'));
        }

        try {
            JWTAuth::setToken($token);
            JWTAuth::authenticate();
        } catch (Throwable $e) {
            throw new AuthenticationException(redirectTo: route('login'));
        }

        return $next($request);
    }
}
