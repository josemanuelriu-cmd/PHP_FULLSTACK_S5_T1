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
    /*
    get_boardgames_list
    get_boardgame_details
    get_non_existing_boardgame_details
    create_boardgame
    delete_boardgame
    update_boardgame
    */

    public function test_get_boardgames_list(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames');
     
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']]);
    }
    public function test_get_boardgame_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/2');
        $response->assertStatus(200);
        $response->assertJsonStructure(['name', 'slug', 'min_players', 'max_players', 'min_age', 'duration', 'description']);
        $response->assertJsonFragment(['name' => 'Carcassonne']);
    }
    
    public function get_non_existing_boardgame_details(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/boardgames/666');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    
    public function test_create_boardgame(): void
    {
        $user = User::factory()->create();
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
    
    public function test_delete_boardgame(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->delete('/api/v1/boardgames/3');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Boardgame deleted',
        ]);
    }
    
    public function test_update_boardgame(): void
    {
        $user = User::factory()->create();
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

}
