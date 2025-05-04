<?php

namespace App\Http\Cookies;

class JsonWebTokenCookie
{
    public const NAME = 'access_token';
    public static function make(string $tokenValue): \Symfony\Component\HttpFoundation\Cookie
    {
        return \Illuminate\Support\Facades\Cookie::make(
            name: self::NAME,
            value: $tokenValue,
            minutes: 5,
            path: '/',
            secure: !app()->environment('local'),
            httpOnly: true,
            sameSite: 'Strict'
        );
    }
}
