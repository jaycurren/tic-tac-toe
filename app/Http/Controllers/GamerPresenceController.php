<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\BroadcastGamerPresence;

class GamerPresenceController extends Controller
{
    /**
     * @return Response
     */
    public function store(): Response
    {
        $count = User::count();

        $name = "guest-" . $count;

        $user = User::create([
            "name" => $name,
            "email" => $name . "@",
            "password" => Hash::make($name)
        ]);

        Auth::login($user);

        BroadcastGamerPresence::dispatch();

        return Inertia::render("Welcome", ["user" => $user]);
    }
}