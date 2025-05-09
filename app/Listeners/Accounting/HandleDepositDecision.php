<?php

namespace App\Listeners\Accounting;

use App\Events\Accounting\DepositProcessed;
use App\Events\Accounting\UserBalanceUpdated;
use App\Models\TransactionStatus;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HandleDepositDecision implements ShouldQueue
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
    public function handle(DepositProcessed $depositProcessed): void
    {
        $transaction = $depositProcessed->getTransaction();
        if ($transaction->status !== TransactionStatus::Available) {
            return;
        }

        $user = $transaction->user;
        $amount = $transaction->amount;

        $retries = 3;
        $attempt = 0;

        while ($attempt < $retries) {
            $attempt++;

            try {
                Cache::lock("user:{$user->id}:balance", 10)->block(5, function () use (&$user, $amount) {
                    $user->wallet_balance += $amount;
                    $user->save();
                });

                UserBalanceUpdated::dispatch($user->uuid, $user->wallet_balance);
                break;

            } catch (LockTimeoutException $_) {
                // Sleep between retries
                sleep(1);
            } catch (\Throwable $e) {
                // Something else went wrong, do not try again
                /** @todo Perhaps add this to a list for manual review */
                Log::error($e->getMessage());
                break;
            }
        }

        // All retries failed
        Log::error("Failed to acquire lock for user {$user->id} after {$retries} attempts.");
    }
}
