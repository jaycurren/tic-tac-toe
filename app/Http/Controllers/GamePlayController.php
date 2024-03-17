<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Events\BroadcastGamerPresence;

class GamePlayController extends Controller
{
    /**
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        $game = Game::where("slug", $slug)->first();

        if (!$game) abort(404);

        $user = User::where("name", $game->player_one)->first();

        BroadcastGamerPresence::dispatch();

        return Inertia::render("Play", ["game" => $game, "user" => $user]);
    }
}