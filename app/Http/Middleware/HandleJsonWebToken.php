<?php

namespace App\Http\Middleware;

use App\Http\Cookies\JsonWebTokenCookie;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (empty($token)) {
            throw new AuthenticationException(redirectTo: 'login');
        }

        try {
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();
            Auth::setUser($user);
        } catch (Throwable $_) {
            throw new AuthenticationException(redirectTo: 'login');
        }

        return $next($request);
    }
}
