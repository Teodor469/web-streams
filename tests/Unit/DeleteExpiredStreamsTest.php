<?php

namespace Tests\Unit;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteExpiredStreamsTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_deletes_only_expired_streams()
    {
        $validStream = Stream::factory()->create([
            'date_expiration' => now()->addDay(),
        ]);

        $expiredStream = Stream::factory()->create([
            'date_expiration' => now()->subDay(),
        ]);

        $this->artisan('streams:delete-expired')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('streams', ['id' => $expiredStream->id]);

        $this->assertDatabaseHas('streams', ['id' => $validStream->id]);
    }
}
