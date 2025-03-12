<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_user_can_register_successfully()
    {
        $response = $this->postJson("/api/register", [
            'name' => 'John Doe',
            'email' => 'john@mail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'auth_token',
                'user' => ['name', 'email', 'updated_at', 'created_at', 'id']
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john@mail.com']);
    }

    public function test_if_registration_fails_if_required_fields_are_missing()
    {
        $response = $this->postJson('/api/register', []);
        $response->assertStatus(422)
        ->assertJson([
            'error' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
    }

    public function test_if_email_is_already_taken()
    {
        User::factory()->create(['email' => 'john@mail.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'email' => 'john@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'email' => ["The email has already been taken."]
                ]
            ]);
    }

    public function test_if_registration_fails_if_password_confirmation_does_not_match()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'email' => 'john@mail.com',
            'password' => '12345678',
            'password_confirmation' => '123456781'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'password' => ["The password confirmation does not match."]
                ]
            ]);
    }

    public function test_if_user_can_login_successfully()
    {
        User::factory()->create([
            'email' => 'john@mail.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@mail.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'message',
                    'auth_token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ]
            );
    }

    public function test_if_user_has_prompted_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'john@mail.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('api/login', [
            'email' => 'john1@mail.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials'
            ]);
    }

    public function test_if_login_required_fields_are_missing()
    {
        $response = $this->postJson('/api/login', []);
        $response->assertStatus(422)
        ->assertJson([
            'error' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
    }

    public function test_if_user_is_able_to_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out',
            ]);
    }

    public function test_if_logout_fails_for_unauthenticated_user()
    {
        $response = $this->postJson('api/logout');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

}
