<?php

namespace App\Http\Controllers;

use App\Events\BroadcastGamerInvite;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GameInviteRequest;

class GameInviteController extends Controller
{
    /**
     * @return Response
     */
    public function store(GameInviteRequest $request): RedirectResponse
    {
        \Log::info($request);

        BroadcastGamerInvite::dispatch(
            $request->invite_name,
            $request->user_name,
            $request->slug
        );

        return back();
    }
}