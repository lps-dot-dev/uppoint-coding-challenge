<?php

use App\Http\Controllers\Accounting\BalanceController;
use App\Http\Controllers\Accounting\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt-auth', 'jwt-refresh'])->group(function () {
    Route::get('transaction', [TransactionsController::class, 'index']);
});
