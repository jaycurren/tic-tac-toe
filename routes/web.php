<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GamePlayController;
use App\Http\Controllers\GameCreateController;
use App\Http\Controllers\GameInviteController;
use App\Http\Controllers\GamerPresenceController;

Route::get("/", [GamerPresenceController::class, "store"]);

Route::group(["prefix" => "/game"], function () {
    Route::get("/{slug}", [GamePlayController::class, "show"]);
    Route::get("/{slug}/player-two", [GamePlayController::class, "show"]);
    Route::post("/create", [GameCreateController::class, "store"]);
    Route::post("/invite", [GameInviteController::class, "store"]);
});