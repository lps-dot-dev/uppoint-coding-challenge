<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartDepositRequest;
use App\Http\Services\Accounting;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Transaction::where('user_id', $request->user()->id)->paginate(10));
    }

    public function create(Accounting $accountingService, StartDepositRequest $startDepositRequest)
    {
        return response()->json($accountingService->setupDeposit(
            $startDepositRequest->user()->id,
            (float)$startDepositRequest->get('amount')
        ));
    }
}
