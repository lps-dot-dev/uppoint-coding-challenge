<?php

use App\Http\Cookies\JsonWebTokenCookie;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HandleJsonWebToken;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RefreshJsonWebToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->priority([
            HandleJsonWebToken::class,
            RedirectIfAuthenticated::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            RefreshJsonWebToken::class,
        ]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'jwt-auth' => HandleJsonWebToken::class,
            'jwt-guest' => RedirectIfAuthenticated::class,
            'jwt-refresh' => RefreshJsonWebToken::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->inertia()) {
                return to_route($e->redirectTo($request))
                    ->withoutCookie(JsonWebTokenCookie::NAME)
                    ->withErrors([
                        'email' => trans('auth.failed'),
                    ]);
            }
    
            if ($request->expectsJson()) {
                return response()
                    ->json(['message' => 'Unauthenticated.'], 401)
                    ->withoutCookie(JsonWebTokenCookie::NAME);
            }
    
            return redirect()
                ->guest($e->redirectTo($request))
                ->withoutCookie(JsonWebTokenCookie::NAME);
        });
    })->create();
