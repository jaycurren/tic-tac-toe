<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel("games.room", function (User $user) {
    return $user;
});

Broadcast::channel("games.invite.{user_name}", function (User $user, string $user_name) {
    return $user->name = $user_name;
});