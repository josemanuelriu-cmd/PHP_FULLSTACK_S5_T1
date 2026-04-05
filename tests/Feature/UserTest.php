<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\ClientRepository;

class UserTest extends TestCase
{    
    public function test_login_successful(): void
    {
        $user = User::factory()
            ->withPassword('password')
            ->create([
                'email' => 'test3@example.com',
            ]);
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test3@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }
    public function test_login_failed(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(401);
    }
    public function test_logout_successful(): void
    {
        $user = User::factory()
            ->withPassword('password')
            ->create([
                'email' => 'test4@example.com',
            ]);

        $response = $this->actingAs($user, 'api') // 'api' es el guard de Passport
                     ->postJson('/api/v1/logout');
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully'
            ]);
    }
    public function test_register_successful(): void
    {
        $payload = [
            'num_partner' => 3,
            'nickname' => 'PruebasTest',
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'junta',
            'registration_date' => now()->toDateString(),
            'withdrawal_date' => null,
            'telephone' => '123456777',
            'age' => 25,
            'language' => 'es',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'user' => ['id', 'num_partner', 'nickname', 'name', 'email', 'type', 'registration_date', 'withdrawal_date', 'created_at', 'updated_at'],
            'token'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User'
        ]);
    }
   
    public function test_basic_default_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function test_get_users_list(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']]);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden'
        ]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden'
        ]);
    }
    public function test_get_user_detail(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']);
        $response->assertJsonFragment(['name' => 'Test User']);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'num_partner', 'nickname', 'name', 'type', 'registration_date', 'withdrawal_date', 'email', 'telephone', 'age', 'language', 'email_verified_at', 'created_at', 'updated_at']);
        $response->assertJsonFragment(['name' => 'Test User']);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users/1');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden'
        ]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users/1');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden'
        ]);
    }
    public function test_get_non_existing_user_detail(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/users/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_create_user_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'num_partner' => 3,
            'nickname' => 'PruebasTest',
            'name' => 'PruebasTest',
            'password' => 'password1',
            'type' => 'admin',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas@zas.es',
            'telephone' => '123456787',
            'age' => 25,
            'language' => 'es',
        ];
        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'PruebasTest',
            'email' => 'pruebas@zas.es',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'pruebas@zas.es',
        ]);
    }
    public function test_soft_delete_user_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/users/3');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User deleted',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => 3,
            'withdrawal_date' => now()->toDateString(), // o not null
        ]);
    }
    public function test_update_user_as_admin(): void
    {
        // Primero creamos un usuario en BBDD
        $user = User::factory()->admin()->create([
            'num_partner' => 2,
            'nickname' => 'PruebasTest2',
            'name' => 'PruebasTest2',
            'password' => 'password2',
            'type' => 'admin',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas3@zas.es',
            'telephone' => '123456788',
            'age' => 25,
            'language' => 'es',
        ]);
        $data = [
            'name' => 'PruebasTest2 actualizado',
            'type' => 'admin',
            'registration_date' => now()->toDateString(),
            'age' => 35,
            'language' => 'en',
        ];
        Passport::actingAs($user);
        $response = $this->putJson("/api/v1/users/{$user->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'PruebasTest2 actualizado',
            'type' => 'admin',
        ]);
        // Verificar en BBDD
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'PruebasTest2 actualizado',
            'type' => 'admin',
        ]);
    }
    public function test_create_user_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'num_partner' => 4,
            'nickname' => 'PruebasTest4',
            'name' => 'PruebasTest4',
            'password' => 'password4',
            'type' => 'junta',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas4@zas.es',
            'telephone' => '123456784',
            'age' => 25,
            'language' => 'es',
        ];
        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'pruebas4@zas.es',
        ]);
    }
    public function test_soft_delete_user_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/users/9');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        
    }
    public function test_update_user_as_junta(): void
    {
        // Primero creamos un usuario en BBDD
        $user = User::factory()->junta()->create([
            'num_partner' => 2,
            'nickname' => 'PruebasTest2',
            'name' => 'PruebasTest2',
            'password' => 'password2',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas33@zas.es',
            'telephone' => '123456783',
            'age' => 25,
            'language' => 'es',
        ]);
        $data = [
            'name' => 'PruebasTest2 actualizado',
            'registration_date' => now()->toDateString(),
            'age' => 35,
            'language' => 'en',
        ];
        Passport::actingAs($user);

        $response = $this->putJson("/api/v1/users/{$user->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'PruebasTest2 actualizado',
            'age' => 35,
        ]);
        // Verificar en BBDD
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'PruebasTest2 actualizado',
            'age' => 35,
        ]);
    }
    public function test_create_user_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'num_partner' => 4,
            'nickname' => 'PruebasTest5',
            'name' => 'PruebasTest5',
            'password' => 'password5',
            'type' => 'partner',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas5@zas.es',
            'telephone' => '123456785',
            'age' => 25,
            'language' => 'es',
        ];
        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'pruebas5@zas.es',
        ]);
    }
    public function test_soft_delete_user_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/users/8');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        
    }
    public function test_update_user_as_partner(): void
    {
        // Primero creamos un usuario en BBDD
        $user = User::factory()->partner()->create([
            'num_partner' => 36,
            'nickname' => 'PruebasTest6',
            'name' => 'PruebasTest6',
            'password' => 'password6',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas36@zas.es',
            'telephone' => '123456786',
            'age' => 25,
            'language' => 'es',
        ]);
        $data = [
            'name' => 'PruebasTest6 actualizado',
            'registration_date' => now()->toDateString(),
            'age' => 35,
            'language' => 'en',
        ];
        Passport::actingAs($user);

        $response = $this->putJson("/api/v1/users/{$user->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'PruebasTest6 actualizado',
            'age' => 35,
        ]);
        // Verificar en BBDD
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'PruebasTest6 actualizado',
            'age' => 35,
        ]);
    }

    public function test_create_user_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'num_partner' => 37,
            'nickname' => 'PruebasTest7',
            'name' => 'PruebasTest7',
            'password' => 'password7',
            'type' => 'guest',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas7@zas.es',
            'telephone' => '123456787',
            'age' => 25,
            'language' => 'es',
        ];
        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'pruebas7@zas.es',
        ]);
    }
    public function test_soft_delete_user_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/users/7');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        
    }
    public function test_update_user_as_guest(): void
    {
        // Primero creamos un usuario en BBDD
        $user = User::factory()->guest()->create([
            'num_partner' => 37,
            'nickname' => 'PruebasTest7',
            'name' => 'PruebasTest7',
            'password' => 'password7',
            'registration_date' => now()->toDateString(),
            'email' => 'pruebas37@zas.es',
            'telephone' => '123457777',
            'age' => 25,
            'language' => 'es',
        ]);
        $data = [
            'name' => 'PruebasTest7 actualizado',
            'registration_date' => now()->toDateString(),
            'age' => 35,
            'language' => 'en',
        ];
        Passport::actingAs($user);

        $response = $this->putJson("/api/v1/users/{$user->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'PruebasTest7 actualizado',
            'age' => 35,
        ]);
        // Verificar en BBDD
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'PruebasTest7 actualizado',
            'age' => 35,
        ]);
    }   
}