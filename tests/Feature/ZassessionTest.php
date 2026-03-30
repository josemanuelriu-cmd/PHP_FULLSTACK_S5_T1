<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
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
        $response = $this->get('/api/v1/zassessions/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => 'Zassession 1']);
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
            'name' => 'Zassession 2',
            'event_name' => 'Cartas Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);
        $this->assertDatabaseHas('zassessions', [
            'name' => 'Zassession 2',
            'event_name' => 'Cartas Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
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
            'name' => 'Updated Zassession',
            'event_name' => 'Updated Cartas Event',
            'date' => '2026-11-10',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);
        $this->assertDatabaseHas('zassessions', [
            'id' => 1,
            'name' => 'Updated Zassession',
            'event_name' => 'Updated Cartas Event',
            'date' => '2026-11-10',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
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
//@dd($response->json());        
        $response->assertJsonStructure([
            'session_id',
            'session_name',
            'max_users',
            'users_count',
            'available_slots',
            'is_full',
            'start_time',
            'end_time'
            //'games_count'
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
            //'games_count'
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
            //'games_per_session',
        ]);
        $response->assertJson([
            'total_sessions' => 5,
            'total_users_in_sessions' => 3,
            'users_per_session' => [2, 0, 1, 0, 0],
            //'games_per_session' => [0, 0],
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
    /*
    public function test_user_not_joined(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $zassession = Zassession::factory()->create();

        $response = $this->get("/api/v1/zassessions/{$zassession->id}/stats");
        $response->assertStatus(200);
        $response->assertJson([
            'is_user_joined' => false,
        ]);
    }
        */

}
