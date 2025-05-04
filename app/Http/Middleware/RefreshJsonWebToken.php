<?php

namespace App\Http\Middleware;

use App\Http\Cookies\JsonWebTokenCookie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RefreshJsonWebToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $token = $request->cookie(JsonWebTokenCookie::NAME);
            if (!$token) {
                return $response;
            }

            // Refresh the token always after a valid request
            $newToken = JWTAuth::refresh($token);

            // Attach new token in HttpOnly, Secure cookie
            $cookie = JsonWebTokenCookie::make($newToken);
            $response->headers->setCookie($cookie);

        } catch (JWTException $e) {
            // Could not refresh token — ignore or handle if needed
        }

        return $response;
    }
}
