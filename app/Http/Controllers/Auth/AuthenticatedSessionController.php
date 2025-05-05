<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Cookies\JsonWebTokenCookie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $cookie = JsonWebTokenCookie::make($token);
        return to_route('dashboard')->withCookie($cookie);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $token = $request->cookie(JsonWebTokenCookie::NAME);
        try {
            JWTAuth::setToken($token)->invalidate();
        } catch (JWTException $_) {
            // Do nothing.
        }
    
        return to_route('home')->withoutCookie(JsonWebTokenCookie::NAME);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(): HttpResponse
    {
        $newToken = auth()->refresh();
        $cookie = JsonWebTokenCookie::make($newToken);
        return response('')->cookie($cookie);
    }
}
