<?php

namespace App\Listeners\Accounting;

use App\Events\Accounting\DepositProcessed;
use App\Events\Accounting\DepositStarted;
use App\Models\TransactionStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessNewDeposit implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DepositStarted $event): void
    {
        // Choose a random transaction status
        $transactionStatuses = collect([TransactionStatus::Available, TransactionStatus::Failed]);
        $transactionStatus = $transactionStatuses->random();

        sleep(10); // simulate some API call or decision making time
        $transaction = $event->getTransaction();
        $transaction->status = $transactionStatus;
        $transaction->save();

        DepositProcessed::dispatch($transaction);
    }
}
