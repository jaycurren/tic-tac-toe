<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameCreateTest extends TestCase
{
    /** @test */
    public function test_home_screen_render()
    {
        $response = $this->get("/");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_create_game_and_redirect()
    {
        $response = $this->post("/game/create", [
            "name" => "guest-0"
        ]);

        $response->assertStatus(302);
    }
}