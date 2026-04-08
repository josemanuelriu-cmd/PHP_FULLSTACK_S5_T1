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
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']]);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']]);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_get_game_detail_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']);
        $response->assertJsonFragment(['status' => 'open']);
    }
    public function test_get_game_detail_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']);
        $response->assertJsonFragment(['status' => 'open']);
    }
    public function test_get_game_detail_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']);
        $response->assertJsonFragment(['status' => 'open']);
    }
    public function test_get_game_detail_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']);
        $response->assertJsonFragment(['status' => 'open']);
    }
    public function test_get_non_existing_game_detail(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/games/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_create_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
        $this->assertDatabaseHas('games', [
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_create_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
        $this->assertDatabaseHas('games', [
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_create_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
        $this->assertDatabaseHas('games', [
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_create_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $data = [
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 7,
            'start_time' => '19:30:00',
            'status' => 'open',
            'necesary_know_how' => false,
        ];
        $response = $this->post('/api/v1/games', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('games', [
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_delete_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/games/1');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Game deleted',
        ]);
    }
    public function test_delete_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/games/2');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Game deleted',
        ]);
    }
    public function test_delete_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/games/3');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_delete_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/games/3');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_update_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $data = [
            'id' => 3,
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/' . $data['id'], $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
        $this->assertDatabaseHas('games', [
            'id' => $data['id'],
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_update_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $data = [
            'id' => 3,
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 5,
            'start_time' => '18:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/' . $data['id'], $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
        $this->assertDatabaseHas('games', [
            'id' => $data['id'],
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_update_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $data = [
            'id' => 3,
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 6,
            'start_time' => '19:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/' . $data['id'], $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('games', [
            'id' => $data['id'],
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_update_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $data = [
            'id' => 3,
            'zassession_id' => 2,
            'boardgame_id' => 2,
            'host_user_id' => 1,
            'max_players' => 7,
            'start_time' => '20:00:00',
            'status' => 'open',
            'necesary_know_how' => true,
        ];
        $response = $this->put('/api/v1/games/' . $data['id'], $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('games', [
            'id' => $data['id'],
            'zassession_id' => $data['zassession_id'],
            'boardgame_id' => $data['boardgame_id'],
            'host_user_id' => $data['host_user_id'],
            'max_players' => $data['max_players'],
            'start_time' => $data['start_time'],
            'status' => $data['status'],
            'necesary_know_how' => $data['necesary_know_how'],
        ]);
    }
    public function test_user_join_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_user_join_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_user_join_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_user_join_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
    public function test_user_leave_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_user_leave_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_user_leave_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_user_leave_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
    public function test_user_join_full_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 1,
        ]);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);        
        $anotherUser = User::factory()->admin()->create();
        Passport::actingAs($anotherUser);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Game is full',
        ]);
    }
    public function test_user_join_full_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 1,
        ]);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);        
        $anotherUser = User::factory()->junta()->create();
        Passport::actingAs($anotherUser);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Game is full',
        ]);
    }
    public function test_user_join_full_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 1,
        ]);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);        
        $anotherUser = User::factory()->partner()->create();
        Passport::actingAs($anotherUser);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Game is full',
        ]);
    }
    public function test_user_join_full_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 1,
        ]);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(200);        
        $anotherUser = User::factory()->guest()->create();
        Passport::actingAs($anotherUser);
        $response = $this->post("/api/v1/games/{$game->id}/join");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Game is full',
        ]);
    }
    public function test_user_join_already_joined_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User already joined this game',
        ]);
    }
    public function test_user_join_already_joined_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User already joined this game',
        ]);
    }
    public function test_user_join_already_joined_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User already joined this game',
        ]);
    }
    public function test_user_join_already_joined_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User already joined this game',
        ]);
    }
    public function test_user_leave_not_joined_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User is not joined to this game',
        ]);
    }
    public function test_user_leave_not_joined_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User is not joined to this game',
        ]);
    }
    public function test_user_leave_not_joined_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User is not joined to this game',
        ]);
    }
    public function test_user_leave_not_joined_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->delete("/api/v1/games/{$game->id}/leave");
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'User is not joined to this game',
        ]);
    }
    public function test_user_join_non_existing_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->post("/api/v1/games/999/join");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_join_non_existing_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->post("/api/v1/games/999/join");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_join_non_existing_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->post("/api/v1/games/999/join");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_join_non_existing_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->post("/api/v1/games/999/join");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_leave_non_existing_game_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->delete("/api/v1/games/999/leave");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_leave_non_existing_game_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->delete("/api/v1/games/999/leave");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_leave_non_existing_game_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->delete("/api/v1/games/999/leave");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_user_leave_non_existing_game_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->delete("/api/v1/games/999/leave");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_get_game_players_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_get_game_players_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_get_game_players_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_get_game_players_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
    public function test_get_game_stats_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->get("/api/v1/games/{$game->id}/stats");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'game_id',
            'zassession_id',
            'boardgame_id',
            'host_user_id',
            'max_players',
            'total_players',
            'start_time',
            'status',
            'necesary_know_how',
        ]);
        $response->assertJson([
            'game_id' => $game->id,
            'zassession_id' => $game->zassession_id,
            'boardgame_id' => $game->boardgame_id,
            'host_user_id' => $game->host_user_id,
            'max_players' => $game->max_players,
            'total_players' => 0,
            'start_time' => $game->start_time,
            'status' => $game->status,
            'necesary_know_how' => $game->necesary_know_how,
        ]);
    }
    public function test_get_game_stats_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->get("/api/v1/games/{$game->id}/stats");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'game_id',
            'zassession_id',
            'boardgame_id',
            'host_user_id',
            'max_players',
            'total_players',
            'start_time',
            'status',
            'necesary_know_how',
        ]);
        $response->assertJson([
            'game_id' => $game->id,
            'zassession_id' => $game->zassession_id,
            'boardgame_id' => $game->boardgame_id,
            'host_user_id' => $game->host_user_id,
            'max_players' => $game->max_players,
            'total_players' => 0,
            'start_time' => $game->start_time,
            'status' => $game->status,
            'necesary_know_how' => $game->necesary_know_how,
        ]);
    }
    public function test_get_game_stats_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->get("/api/v1/games/{$game->id}/stats");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'game_id',
            'zassession_id',
            'boardgame_id',
            'host_user_id',
            'max_players',
            'total_players',
            'start_time',
            'status',
            'necesary_know_how',
        ]);
        $response->assertJson([
            'game_id' => $game->id,
            'zassession_id' => $game->zassession_id,
            'boardgame_id' => $game->boardgame_id,
            'host_user_id' => $game->host_user_id,
            'max_players' => $game->max_players,
            'total_players' => 0,
            'start_time' => $game->start_time,
            'status' => $game->status,
            'necesary_know_how' => $game->necesary_know_how,
        ]);
    }
    public function test_get_game_stats_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $game = Game::factory()->create([
            'zassession_id' => 1,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->get("/api/v1/games/{$game->id}/stats");
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);        
    }
}
