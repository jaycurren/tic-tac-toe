<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GamePlayTest extends TestCase
{
    /**
     * @test
     */
    public function test_get_valid_game_route_with_data(): void
    {
        $slug = Game::first()->slug;

        $response = $this->get("/game/" . $slug);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_get_invalid_game_route_with_404(): void
    {
        $response = $this->get("/game/invalid");

        $response->assertStatus(404);
    }
}