<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return to_route('login');
})->middleware('jwt-guest')->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware('jwt-auth', 'jwt-refresh')->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
