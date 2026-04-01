<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;

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
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->post('/api/v1/games', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
        $this->assertDatabaseHas('games', [
            'zassession_id' => 2,
            'boardgame_id' => 2,
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
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/3', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
        $this->assertDatabaseHas('games', [
            'id' => 3,
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ]);
    }
        public function test_user_join(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User joined the game',
        ]);
         $this->assertDatabaseHas('game_user', [
            'game_id' => $game->id,
            'user_id' => $user->id,            
         ]);
    }
    public function test_user_leave(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);

        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);
        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User left the game',
        ]);
        $this->assertDatabaseMissing('game_user', [
            'game_id' => $game->id,
            'user_id' => $user->id,            
        ]);
    }
    public function test_user_join_full_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 1,
        ]);

        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);
        
        $anotherUser = User::factory()->create();
        Passport::actingAs($anotherUser);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Game is full',
        ]);
    }

    public function test_user_join_already_joined_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);

        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);
        
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(402);
        $response->assertJson([
            'message' => 'User already joined this game',
        ]);
    }

    public function test_user_leave_not_joined_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);

        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'User is not joined to this game',
        ]);
    }

    public function test_user_join_non_existing_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->post("/api/v1/games/999/join");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Game not found',
        ]);
    }

    public function test_user_leave_non_existing_game(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->delete("/api/v1/games/999/leave");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Game not found',
        ]);
    }

    
    public function test_get_game_players(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->post("/api/v1/games/{$game->id}/join");

        $response = $this->get("/api/v1/games/{$game->id}/users");
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
