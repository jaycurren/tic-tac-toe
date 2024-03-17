<?php

namespace App\Http\Controllers;

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
        BroadcastGamerPlay::dispatch(
            $request->game,
            $request->turn,
            $slug,
            $request->winner
        );

        return back();
    }
}