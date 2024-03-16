<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GameCreateRequest;

class GameCreateController extends Controller
{
    /**
     * @param GameCreateRequest $request
     */
    public function store(GameCreateRequest $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();

        $game = Game::create([
            "player_one" => $request->name,
            "slug" => $uuid
        ]);

        return redirect("/game/" . $game->slug);
    }
}