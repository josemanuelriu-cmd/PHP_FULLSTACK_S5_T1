<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\Boardgame;
use App\Models\User;


class BoardgameTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_get_boardgames_list(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames');     
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']]);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames');     
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']]);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames');     
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames');     
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']]);
    }
    public function test_get_boardgame_detail(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/2');
        $response->assertStatus(200);
        $response->assertJsonStructure(['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']);
        $response->assertJsonFragment(['name' => 'Carcassonne']);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/2');
        $response->assertStatus(200);
        $response->assertJsonStructure(['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']);
        $response->assertJsonFragment(['name' => 'Carcassonne']);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/2');
        $response->assertStatus(200);
        $response->assertJsonStructure(['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']);
        $response->assertJsonFragment(['name' => 'Carcassonne']);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/2');
        $response->assertStatus(200);
        $response->assertJsonStructure(['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']);
        $response->assertJsonFragment(['name' => 'Carcassonne']);
    }
    
    public function get_non_existing_boardgame_details(): void
    {
        //si va el anterior, este también debería ir con cualquier rol de usuario
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/666');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    
    public function test_create_boardgame_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas',
            'slug' => 'pruebas',
            'min_players' => 3,
            'max_players' => 5,
            'min_age' => 10,
            'duration' => 100,
            'description' => 'descripcion prueba',
            'owner_user_id' => 1,
        ];
        $response = $this->post('/api/v1/boardgames', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Pruebas',
        ]);
        $this->assertDatabaseHas('boardgames', [
            'slug' => 'pruebas',
        ]);
    }
    
    public function test_delete_boardgame_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/boardgames/3');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Boardgame deleted',
        ]);
    }
    
    public function test_update_boardgame_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas Update',
            'slug' => 'pruebas-update',
            'min_players' => 4,
            'max_players' => 6,
            'min_age' => 12,
            'duration' => 120,
            'description' => 'descripcion prueba update',
        ];
        $response = $this->put('/api/v1/boardgames/2', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Pruebas Update',
        ]);
        $this->assertDatabaseHas('boardgames', [
            'slug' => 'pruebas-update',
        ]);
    }

    public function test_create_boardgame_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas_junta',
            'slug' => 'pruebas-junta',
            'min_players' => 3,
            'max_players' => 5,
            'min_age' => 10,
            'duration' => 100,
            'description' => 'descripcion prueba',
            'owner_user_id' => 1,
        ];
        $response = $this->post('/api/v1/boardgames', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Pruebas_junta',
        ]);
        $this->assertDatabaseHas('boardgames', [
            'slug' => 'pruebas-junta',
        ]);
    }
    
    public function test_delete_boardgame_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/boardgames/4');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Boardgame deleted',
        ]);
    }
    
    public function test_update_boardgame_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas Update2',
            'slug' => 'pruebas-update2',
            'min_players' => 4,
            'max_players' => 6,
            'min_age' => 12,
            'duration' => 120,
            'description' => 'descripcion prueba update2',
        ];
        $response = $this->put('/api/v1/boardgames/2', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Pruebas Update2',
        ]);
        $this->assertDatabaseHas('boardgames', [
            'slug' => 'pruebas-update2',
        ]);    
    }
    public function test_create_boardgame_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas3',
            'slug' => 'pruebas3',
            'min_players' => 3,
            'max_players' => 5,
            'min_age' => 10,
            'duration' => 100,
            'description' => 'descripcion prueba3',
            'owner_user_id' => 1,
        ];
        $response = $this->post('/api/v1/boardgames', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('boardgames', [
            'slug' => 'pruebas3',
        ]);}
    
    public function test_delete_boardgame_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/boardgames/3');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    
    public function test_update_boardgame_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas Update3',
            'slug' => 'pruebas-update3',
            'min_players' => 4,
            'max_players' => 6,
            'min_age' => 12,
            'duration' => 120,
            'description' => 'descripcion prueba update3',
        ];
        $response = $this->put('/api/v1/boardgames/2', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('boardgames', [
            'slug' => 'pruebas-update3',
        ]);
    }
    public function test_create_boardgame_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas3',
            'slug' => 'pruebas3',
            'min_players' => 3,
            'max_players' => 5,
            'min_age' => 10,
            'duration' => 100,
            'description' => 'descripcion prueba3',
            'owner_user_id' => 1,
        ];
        $response = $this->post('/api/v1/boardgames', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('boardgames', [
            'slug' => 'pruebas3',
        ]);}
    
    public function test_delete_boardgame_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/boardgames/3');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    
    public function test_update_boardgame_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Pruebas Update3',
            'slug' => 'pruebas-update3',
            'min_players' => 4,
            'max_players' => 6,
            'min_age' => 12,
            'duration' => 120,
            'description' => 'descripcion prueba update3',
        ];
        $response = $this->put('/api/v1/boardgames/2', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('boardgames', [
            'slug' => 'pruebas-update3',
        ]);
    }
}
