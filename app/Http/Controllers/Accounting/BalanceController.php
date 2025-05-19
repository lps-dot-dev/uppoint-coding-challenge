<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['wallet_balance' => $request->user()->wallet_balance]);
    }
}
