<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;

class TypeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_types_list(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonStructure(['*' => ['id', 'type', 'description']]);
    }
    public function test_get_type_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'type', 'description']);
        $response->assertJsonFragment(['type' => 'abstracto']);
    }
    public function test_get_non_existing_type_detail(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
    }
    public function test_create_type(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'Cartas',
            'description' => 'Cartas Description',
        ];
        $response = $this->post('/api/v1/types', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'type' => 'Cartas',
            'description' => 'Cartas Description',
        ]);
        $this->assertDatabaseHas('types', [
            'type' => 'Cartas',
            'description' => 'Cartas Description',
        ]);
    }
    public function test_delete_type(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Type deleted',
        ]);
    }
    public function test_update_type(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ];
        $response = $this->put('/api/v1/types/3', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ]);
        $this->assertDatabaseHas('types', [
            'id' => 3,
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ]);
    }
}
