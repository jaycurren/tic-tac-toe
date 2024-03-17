<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Events\BroadcastGamerPlay;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GamePlayDataRequest;

class GamePlayDataController extends Controller
{
    /**
     * Check for winner in the current game and set it up if they are
     * @param GamePlayDataRequest $request
     * @param String $slug
     * @return RedirectResponse
     */
    public function store(GamePlayDataRequest $request, string $slug): RedirectResponse
    {
        if ($request->winner !== "none") {
            Game::where("slug", $request->slug)->update(["winner" => $request->winner]);
        }

        BroadcastGamerPlay::dispatch(
            $request->game,
            $request->turn,
            $slug,
            $request->winner
        );

        return back();
    }
}