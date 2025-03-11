<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StreamApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createStreamDate()
    {
        $type = Stream::factory()->create();

        return [
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

        $response = $this->getJson('/api/streams');

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

    }
}
