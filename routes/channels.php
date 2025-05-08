<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::private('Accounting.{id}', function (User $user, int $id) {
    return $user->id === $id;
});
