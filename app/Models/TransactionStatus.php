<?php

namespace App\Models;

enum TransactionStatus: string
{
    case Available = 'available';
    case Failed = 'failed';
    case Pending = 'pending';
}
