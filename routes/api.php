<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt-auth')->group(function () {
    Route::post('broadcasting/auth', function (Request $request) {
        try {
            return Broadcast::auth($request);
        } catch (\Throwable $_) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    });
    Route::post('refresh', [AuthenticatedSessionController::class, 'refresh']);
});

require __DIR__.'/accounting.php';
