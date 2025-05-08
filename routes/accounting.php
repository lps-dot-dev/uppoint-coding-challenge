<?php

use App\Http\Controllers\Accounting\BalanceController;
use App\Http\Controllers\Accounting\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt-auth'])->group(function () {
    Route::get('balance', [BalanceController::class, 'index']);
    Route::get('transaction', [TransactionsController::class, 'index']);
    Route::post('deposit', [TransactionsController::class, 'create']);
});
