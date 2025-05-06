<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Accounting.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
