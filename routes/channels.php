<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Accounting.{uuid}', function (User $user, string $uuid) {
    return $user->uuid === $uuid;
});
