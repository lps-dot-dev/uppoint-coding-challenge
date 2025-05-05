<?php

namespace App\Models;

enum TransactionType: string
{
    case Deposit = 'deposit';
    case Transfer = 'transfer';
    case Withdrawl = 'withdrawl';
}
