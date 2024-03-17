<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel("games.room", function (User $user) {
    return $user;
});

Broadcast::channel("games.invite.{user_name}", function (User $user, string $user_name) {
    return $user->name === $user_name;
});

Broadcast::channel("games.accept.{user_name}", function (User $user, string $user_name) {
    return $user->name === $user_name;
});

Broadcast::channel("games.play.{slug}", function (User $user, string $slug) {
    $game = Game::where("slug", $slug)->first();

    return $user->name === $game->player_one || $user->name === $game->player_two;
});