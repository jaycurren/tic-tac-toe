<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Events\BroadcastGamerAccept;
use App\Events\BroadcastGamerPresence;
use App\Http\Requests\GamePlayerJoinRequest;

class GamePlayerJoinController extends Controller
{
    /**
     * @param GamePlayerJoinRequest $request
     * @param string $slug
     * @return Response
     */
    public function show(GamePlayerJoinRequest $request, string $slug): Response
    {
        $game = Game::where("slug", $slug)->first();

        if (!$game) abort(404);

        $user = User::where("name", $request->name)->first();

        $game->update([
            "player_two" => $request->name
        ]);

        BroadcastGamerAccept::dispatch($game->player_one);
        BroadcastGamerPresence::dispatch();

        return Inertia::render("Join", ["game" => $game, "user" => $user]);
    }
}