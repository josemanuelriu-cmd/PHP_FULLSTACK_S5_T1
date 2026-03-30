<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;

class GameTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_games_list(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']]);
    }
    public function test_get_game_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']);
        $response->assertJsonFragment(['status' => 'open']);
    }
    public function test_get_non_existing_game_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Game Not Found',
        ]);
    }
    public function test_create_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->post('/api/v1/games', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
        $this->assertDatabaseHas('games', [
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
    }
    public function test_delete_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Game deleted',
        ]);
    }
    public function test_update_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/3', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
        $this->assertDatabaseHas('games', [
            'id' => 3,
            'zassession_id' => 1,
            'boardgame_id' => 1,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
    }
}
