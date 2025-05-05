<?php

use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->enum('status', array_map(fn (TransactionStatus $transactionStatus) => $transactionStatus->value, TransactionStatus::cases()));
            $table->enum('type', array_map(fn (TransactionType $transactionType) => $transactionType->value, TransactionType::cases()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
