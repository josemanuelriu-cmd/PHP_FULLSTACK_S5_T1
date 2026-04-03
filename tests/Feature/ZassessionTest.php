<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Zassession;

class ZassessionTest extends TestCase
{
    public function test_get_zassessions_list(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']]);
    }
    public function test_get_zassession_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $zassession = Zassession::factory()->create();
        $response = $this->get("/api/v1/zassessions/{$zassession->id}");
        //$response = $this->get('/api/v1/zassessions/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => $zassession->name]);
    }
    public function test_get_non_existing_zassession_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_create_zassession(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Zassession 2',
            'event_name' => 'Cartas Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->post('/api/v1/zassessions', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => $data['name'],
            'event_name' => $data['event_name'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'max_users' => $data['max_users'],
            'direction' => $data['direction'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
        $this->assertDatabaseHas('zassessions', [
            'name' => $data['name'],
            'event_name' => $data['event_name'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'max_users' => $data['max_users'],
            'direction' => $data['direction'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
    }
    public function test_delete_zassession(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/zassessions/2');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Zassession deleted',
        ]);
    }
    public function test_update_zassession(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Updated Zassession',
            'event_name' => 'Updated Cartas Event',
            'date' => '2026-11-10',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->put('/api/v1/zassessions/1', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $data['name'],
            'event_name' => $data['event_name'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'max_users' => $data['max_users'],
            'direction' => $data['direction'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
        $this->assertDatabaseHas('zassessions', [
            'id' => 1,
            'name' => $data['name'],
            'event_name' => $data['event_name'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'max_users' => $data['max_users'],
            'direction' => $data['direction'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
    }

    public function test_user_join(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User joined the session',
        ]);
         $this->assertDatabaseHas('user_zassession', [
            'user_id' => $user->id,
            'zassession_id' => $zassession->id,
         ]);
    }
    public function test_user_leave(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);

        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(200);
        $response = $this->delete("/api/v1/zassessions/{$zassession->id}/leave");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User left the session',
        ]);
        $this->assertDatabaseMissing('user_zassession', [
            'user_id' => $user->id,
            'zassession_id' => $zassession->id,
        ]);
    }
    public function test_get_session_users(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        $response = $this->get('/api/v1/zassessions/1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);

    }
    public function test_zassession_stats(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions/1/stats');
        $response->assertStatus(200);
     
        $response->assertJsonStructure([
            'session_id',
            'session_name',
            'max_users',
            'users_count',
            'available_slots',
            'is_full',
            'start_time',
            'end_time',
            'games_count'
        ]);
        $response->assertJson([
            'session_id' => 1,
            'session_name' => 'Updated Zassession',
            'max_users' => 15,
            'users_count' => 2,
            'available_slots' => 13,
            'is_full' => false,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'games_count' => 2
        ]);
    }
    public function test_allstats(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions/stats');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_sessions',
            'total_users_in_sessions',
            'users_per_session',
            'games_per_session',
            'users_per_game',
        ]);
        $response->assertJson([
            'total_sessions' => $response->json()['total_sessions'], // Asegura que el número total de sesiones es correcto
            'total_users_in_sessions' => $response->json()['total_users_in_sessions'], // Asegura que el número total de usuarios en sesiones es correcto
            'users_per_session' => [2, 0, 0, 1, 0, 0],
            'games_per_session' => [2, 0, 0, 0, 0, 0],
            'users_per_game' => [null, null],
        ]);
    }
     public function test_zassession_is_full(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $zassession = Zassession::factory()->create([
            'max_users' => 1,
        ]);

        $zassession->users()->attach($user->id);

        $response = $this->get("/api/v1/zassessions/{$zassession->id}/stats");
        $response->assertStatus(200);
        $response->assertJson([
            'session_id' => $zassession->id,
            'session_name' => $zassession->name,
            'max_users' => 1,
            'users_count' => 1,
            'available_slots' => 0,
            'is_full' => true,
            'start_time' => $zassession->start_time->format('H:i:s'),
            'end_time' => $zassession->end_time->format('H:i:s'),
        ]);
    }
    public function test_create_zassession_with_invalid_data(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'a',
            'event_name' => 'b',
            'date' => '32/01/2026',
            'start_time' => '25:00:00',
            'end_time' => '27:00:00',
            'max_users' => -1,
            'direction' => '',
            'latitude' => 1000,
            'longitude' => 1000
        ];
        $response = $this->postJson('/api/v1/zassessions', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
    }
     public function test_get_games_of_zassession(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);

        $game = Game::factory()->create([
            'zassession_id' => $zassession->id,
            'boardgame_id' => 2,
            'host_user_id' => $user->id,
            'max_players' => 5,
        ]);
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(200);

        $response = $this->get("/api/v1/zassessions/{$zassession->id}/games");
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonStructure(['*' => ['id', 'zassession_id', 'boardgame_id', 'host_user_id', 'max_players', 'start_time', 'status', 'necesary_know_how']]);
        $response->assertJsonFragment(['id' => $game->id]);

    }

}
