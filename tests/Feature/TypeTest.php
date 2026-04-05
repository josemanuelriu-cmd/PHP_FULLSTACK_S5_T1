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
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure(['*' => ['id', 'type', 'description']]);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure(['*' => ['id', 'type', 'description']]);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure(['*' => ['id', 'type', 'description']]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_get_type_detail(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'type', 'description']);
        $response->assertJsonFragment(['type' => 'abstracto']);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'type', 'description']);
        $response->assertJsonFragment(['type' => 'abstracto']);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'type', 'description']);
        $response->assertJsonFragment(['type' => 'abstracto']);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/1');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_get_non_existing_type_detail(): void
    {
        //as admin
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
        //as junta
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
        //as partner
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Not Found',
        ]);
        //as guest
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/v1/types/999');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
    public function test_create_type_as_admin(): void
    {
        $user = User::factory()->admin()->create();
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
    public function test_delete_type_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/types/1');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Type deleted',
        ]);
    }
    public function test_update_type_as_admin(): void
    {
        $user = User::factory()->admin()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ];
        $response = $this->put('/api/v1/types/4', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ]);
        $this->assertDatabaseHas('types', [
            'id' => 4,
            'type' => 'Cartas',
            'description' => 'Updated Cartas Description',
        ]);
    }

    public function test_create_type_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'clásico',
            'description' => 'clásico Description',
        ];
        $response = $this->post('/api/v1/types', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'type' => 'clásico',
            'description' => 'clásico Description',
        ]);
        $this->assertDatabaseHas('types', [
            'type' => 'clásico',
            'description' => 'clásico Description',
        ]);
    }
    public function test_delete_type_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/types/2');
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseHas('types', [
            'id' => 2,
            'type' => 'ameritrash',
        ]);
    }
    public function test_update_type_as_junta(): void
    {
        $user = User::factory()->junta()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'clásico',
            'description' => 'Updated clásico Description',
        ];
        $response = $this->put('/api/v1/types/5', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'clásico',
            'description' => 'Updated clásico Description',
        ]);
        $this->assertDatabaseHas('types', [
            'id' => 5,
            'type' => 'clásico',
            'description' => 'Updated clásico Description',
        ]);
    }
    public function test_create_type_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'cooperativo',
            'description' => 'cooperativo Description',
        ];
        $response = $this->post('/api/v1/types', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('types', [
            'type' => 'cooperativo',
            'description' => 'cooperativo Description',
        ]);
    }
    public function test_delete_type_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/types/2');
        $response->assertStatus(403);
         $response->assertJson([
            'message' => 'Forbidden',
        ]);        
    }
    public function test_update_type_as_partner(): void
    {
        $user = User::factory()->partner()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'clásico',
            'description' => 'Updated clásico Description 2',
        ];
        $response = $this->put('/api/v1/types/5', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }

    public function test_create_type_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'cooperativo',
            'description' => 'cooperativo Description',
        ];
        $response = $this->post('/api/v1/types', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
        $this->assertDatabaseMissing('types', [
            'type' => 'cooperativo',
            'description' => 'cooperativo Description',
        ]);
    }
    public function test_delete_type_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);
        $response = $this->delete('/api/v1/types/2');
        $response->assertStatus(403);
         $response->assertJson([
            'message' => 'Forbidden',
        ]);        
    }
    public function test_update_type_as_guest(): void
    {
        $user = User::factory()->guest()->create();
        Passport::actingAs($user);

        $data = [
            'type' => 'clásico',
            'description' => 'Updated clásico Description 2',
        ];
        $response = $this->put('/api/v1/types/5', $data);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
}
