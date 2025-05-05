<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Transaction::paginate(10));
    }
}
