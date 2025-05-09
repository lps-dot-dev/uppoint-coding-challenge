<?php

namespace Database\Factories;

use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Collection<int,TransactionStatus> $transactionStatuses */
        $transactionStatuses = collect(TransactionStatus::cases());
        /** @var Collection<int,TransactionType> $transactionTypes */
        $transactionTypes = collect(TransactionType::cases());
        
        return [
            'amount' => fake()->randomFloat(nbMaxDecimals: 2, max: 1000),
            'status' => $transactionStatuses->random(),
            'type' => $transactionTypes->random()
        ];
    }
}
