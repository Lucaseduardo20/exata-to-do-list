<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_authentication_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => fake()->randomElement(['admin', 'user'])
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    public function test_authentication_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => fake()->randomElement(['admin', 'user'])
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'incorrectpassword'
        ]);

        $response->assertStatus(403);
        $this->assertGuest();
    }

    public function test_authentication_with_empty_fields(): void
    {
        $response = $this->post('/login', []);

        $response->assertStatus(302);
    }

    public function test_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $user = User::query()->where('email', 'test@example.com')->exists();

        $response->assertStatus(200);
        $this->assertTrue($user);
    }

    public function test_register_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(500);
    }

    public function test_register_fails_with_missing_fields(): void
    {
        $response = $this->postJson('/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_saves_correct_user_attributes(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $user = User::query()->where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_register_with_invalid_email(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }
}
