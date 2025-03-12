<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Models\StreamType;
use App\Models\User;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StreamApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createStreamData()
    {
        $type = StreamType::factory()->create();
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'title' => 'Test Stream',
            'description' => 'Test description',
            'tokens_price' => 50,
            'type_id' => $type->id,
            'date_expiration' => now()->addDay()->format('Y-m-d H:i:s')
        ];
    }

    public function test_if_it_returns_paginated_streams()
    {
        Stream::factory()->count(15)->create();

        $response = $this->getJson("/api/streams");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'tokens_price', 'type_id', 'date_expiration']
                ],
                'links',
                'meta'
            ])
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.total', 15);
    }

    public function test_if_user_can_create_streams()
    {
        $data = $this->createStreamData();
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/streams", $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Stream created successfully',
            ]);

        $this->assertDatabaseHas('streams', ['title' => 'Test Stream']);
    }

    public function test_if_stream_creation_is_valid()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/streams', [
            'title' => '',
            'description' => '',
            'tokens_price' => '',
            'type_id' => null,
            'date_expiration' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'title' => ['The title field is required.'],
                    'tokens_price' => ['The tokens price field is required.'],
                    'date_expiration' => ['The date expiration field is required.'],
                ]
            ]);
    }

    public function test_if_non_authenticated_user_can_create_stream()
    {
        $data = $this->createStreamData();

        $response = $this->postJson('/api/streams', $data);

        $response->assertStatus(401);
    }

    public function test_if_user_can_update_their_own_stream()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $stream = Stream::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'tokens_price' => 75,
            'type_id' => $stream->type_id,
            'date_expiration' => now()->addDays(2)->format('Y-m-d H:i:s')
        ];

        $response = $this->putJson("/api/streams/{$stream->id}", $updatedData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Web stream updated successfully',
                'data' => [
                    'title' => 'Updated title',
                    'description' => 'Updated description',
                    'tokens_price' => 75,
                ]
            ]);

        $this->assertDatabaseHas('streams', ['title' => 'Updated title']);

    }

    public function test_if_other_user_is_prevented_from_updating_other_streams()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $stream = Stream::factory()->create(['user_id' => $otherUser->id]);

        $updatedData = [
            'title' => 'Hacked Updated title',
            'description' => 'Hacked Updated description',
            'tokens_price' => 7500,
            'type_id' => $stream->type_id,
            'date_expiration' => now()->addDays(2)->format('Y-m-d H:i:s')
        ];

        $response = $this->putJson("/api/streams/{$stream->id}", $updatedData);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);

        $this->assertDatabaseMissing('streams', ['title' => 'Hacked Updated title']);
    }

    public function test_if_user_can_delete_their_own_stream()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $stream = Stream::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/streams/{$stream->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Stream deleted successfully'
            ]);

        $this->assertDatabaseMissing('streams', ['id' => $stream->id]);
    }

    public function test_if_other_user_is_prevented_from_deleting_other_streams()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $stream = Stream::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->deleteJson("/api/streams/{$stream->id}");

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);

        $this->assertDatabaseHas('streams', ['id' => $stream->id]);
    }
}
