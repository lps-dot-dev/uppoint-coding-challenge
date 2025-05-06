<?php

namespace App\Http\Services;

use App\Events\Accounting\DepositStarted;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;

class Accounting
{
    public function __construct()
    {
        
    }
    public function setupDeposit(int $userId, float $amount): Transaction
    {
        $pendingTransaction = Transaction::create([
            'user_id' => $userId,
            'amount' => $amount,
            'status' => TransactionStatus::Pending,
            'type' => TransactionType::Deposit
        ]);

        DepositStarted::dispatch($pendingTransaction);
        return $pendingTransaction;
    }
}
