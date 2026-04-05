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
        //as admin
        $user = User::factory()->admin()->create();        
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']]);
        //as junta
        $user = User::factory()->junta()->create();        
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']]);
        //as partner
        $user = User::factory()->partner()->create();        
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']]);
        //as guest
        $user = User::factory()->guest()->create();        
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']]);
    }
    public function test_get_zassession_detail(): void
    {
        //as admin    
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create();
        $response = $this->get("/api/v1/zassessions/{$zassession->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => $zassession->name]);
        //as junta    
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create();
        $response = $this->get("/api/v1/zassessions/{$zassession->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => $zassession->name]);
        //as partner    
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create();
        $response = $this->get("/api/v1/zassessions/{$zassession->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => $zassession->name]);
        //as guest    
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create();
        $response = $this->get("/api/v1/zassessions/{$zassession->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'event_name', 'date', 'start_time', 'end_time', 'max_users', 'direction', 'latitude', 'longitude']);
        $response->assertJsonFragment(['name' => $zassession->name]);
    }
    public function test_get_non_existing_zassession_detail(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_create_zassession_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Zassession admin',
            'event_name' => 'Admin Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Admin Direction',
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
    public function test_create_zassession_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Zassession junta',
            'event_name' => 'Junta Event',
            'date' => '2026-10-13',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Junta Direction',
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
    public function test_create_zassession_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Zassession partner',
            'event_name' => 'partner Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'partner Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->post('/api/v1/zassessions', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('zassessions', [
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
    public function test_create_zassession_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Zassession guest',
            'event_name' => 'guest Event',
            'date' => '2026-10-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'guest Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->post('/api/v1/zassessions', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden'
        ]);
        $this->assertDatabaseMissing('zassessions', [
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
    public function test_delete_zassession_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/zassessions/2');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Zassession deleted',
        ]);
    }
    public function test_delete_zassession_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/zassessions/3');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Zassession deleted',
        ]);
    }
    public function test_delete_zassession_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/zassessions/4');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_delete_zassession_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/zassessions/5');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_update_zassession_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Updated Zassession Admin',
            'event_name' => 'Updated Admin Event',
            'date' => '2026-11-10',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated Admin Direction',
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
    public function test_update_zassession_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Updated Zassession Junta',
            'event_name' => 'Updated Junta Event',
            'date' => '2026-11-11',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated Junta Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->put('/api/v1/zassessions/4', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => 4,
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
            'id' => 4,
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
    public function test_update_zassession_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Updated Zassession partner',
            'event_name' => 'Updated partner Event',
            'date' => '2026-11-12',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated partner Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->put('/api/v1/zassessions/5', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('zassessions', [
            'id' => 5,
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
    public function test_update_zassession_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Updated Zassession guest',
            'event_name' => 'Updated guest Event',
            'date' => '2026-11-14',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 15,
            'direction' => 'Updated guest Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ];
        $response = $this->put('/api/v1/zassessions/6', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('zassessions', [
            'id' => 6,
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
    public function test_user_join_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_user_join_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_user_join_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_user_join_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
    public function test_user_leave_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_user_leave_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_user_leave_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_user_leave_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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
    public function test_get_session_users_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        $response = $this->get('/api/v1/zassessions/1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
    }
    public function test_get_session_users_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        $response = $this->get('/api/v1/zassessions/1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
    }
    public function test_get_session_users_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        $response = $this->get('/api/v1/zassessions/1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
    }
    public function test_get_session_users_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'max_users' => 15,
        ]);
        $response = $this->get('/api/v1/zassessions/1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
    }
    
    public function test_zassession_stats_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
            'session_name' => 'Updated Zassession Admin',
            'max_users' => 15,
            'users_count' => 2,
            'available_slots' => 13,
            'is_full' => false,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'games_count' => 28
        ]);
    }
    public function test_zassession_stats_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
            'session_name' => 'Updated Zassession Admin',
            'max_users' => 15,
            'users_count' => 2,
            'available_slots' => 13,
            'is_full' => false,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'games_count' => 28
        ]);
    }
    public function test_zassession_stats_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
            'session_name' => 'Updated Zassession Admin',
            'max_users' => 15,
            'users_count' => 2,
            'available_slots' => 13,
            'is_full' => false,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'games_count' => 28
        ]);
    }
    public function test_zassession_stats_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/zassessions/1/stats');
        $response->assertStatus(403);     
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }    
    public function test_allstats(): void
    {
        $user = User::factory()->admin()->create();
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
    }    
    public function test_zassession_is_full_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'name' => 'Full Session',
            'max_users' => 0,
        ]);
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Session is full',
        ]);
    }
    public function test_zassession_is_full_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'name' => 'Full Session',
            'max_users' => 0,
        ]);
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Session is full',
        ]);
    }
    public function test_zassession_is_full_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'name' => 'Full Session',
            'max_users' => 0,
        ]);
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Session is full',
        ]);
    }
    public function test_zassession_is_full_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $zassession = Zassession::factory()->create([
            'name' => 'Full Session',
            'max_users' => 0,
        ]);
        $response = $this->post("/api/v1/zassessions/{$zassession->id}/join");
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Session is full',
        ]);
    }
    public function test_create_zassession_with_invalid_data(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_get_games_of_zassession_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_get_games_of_zassession_as_junta(): void
    {
        $user = User::factory()->junta()->create();
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
    public function test_get_games_of_zassession_as_partner(): void
    {
        $user = User::factory()->partner()->create();
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
    public function test_get_games_of_zassession_as_guest(): void
    {
        $user = User::factory()->guest()->create();
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