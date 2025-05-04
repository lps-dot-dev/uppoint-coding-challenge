<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt')->group(function () {
    Route::post('refresh', [AuthenticatedSessionController::class, 'refresh'])
        ->name('refresh');
});
