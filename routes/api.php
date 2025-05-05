<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt-auth')->group(function () {
    Route::post('refresh', [AuthenticatedSessionController::class, 'refresh']);
});

require __DIR__.'/accounting.php';
