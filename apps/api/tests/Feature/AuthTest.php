<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        // Verify password is hashed correctly (not double hashed)
        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        // Create a user first
        $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Try to register with the same email
        $response = $this->postJson('/api/register', [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password456',
            'password_confirmation' => 'password456',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Email already exists',
        ]);
    }

    public function test_user_cannot_register_with_invalid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'firstName' => '', // Required
            'lastName' => '', // Required
            'email' => 'invalid-email', // Invalid email
            'password' => '123', // Too short
            'password_confirmation' => '456', // Doesn't match
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['firstName', 'lastName', 'email', 'password']);
    }

    public function test_user_can_login(): void
    {
        // Create a user first
        $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Now login
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
            ],
            'access_token',
            'token_type',
            'expires_in',
        ]);

        $response->assertJsonFragment([
            'message' => 'Login successful',
        ]);

        // Verify we got a token
        $this->assertNotNull($response['access_token']);
        $this->assertEquals('Bearer', $response['token_type']);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        // Create a user first
        $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Try to login with wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);

        // Try to login with non-existent email
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    public function test_authenticated_user_can_access_profile(): void
    {
        // Register and login a user
        $registerResponse = $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse['access_token'];

        // Access user profile
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ]);

        $response->assertJsonFragment([
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_refresh_token(): void
    {
        // Register and login a user
        $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse['access_token'];

        // Refresh token
        $response = $this->postJson('/api/refresh', [
            'token' => $token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

        $this->assertNotNull($response['access_token']);
        $this->assertEquals('Bearer', $response['token_type']);
    }

    public function test_user_cannot_refresh_with_invalid_token(): void
    {
        $response = $this->postJson('/api/refresh', [
            'token' => 'invalid-token',
        ]);

        $response->assertStatus(422); // Validation error
    }

    public function test_authenticated_user_can_logout(): void
    {
        // Register and login a user
        $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse['access_token'];

        // Logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logged out successfully',
        ]);

        // Try to access protected route after logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_password_is_not_double_hashed(): void
    {
        // Register a user
        $response = $this->postJson('/api/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertEquals(201, $response->status());

        // Check the user in the database
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);

        // Verify the password is hashed exactly once
        $hash = $user->password;
        $this->assertTrue(Hash::check('password123', $hash));

        // Hash the password again and verify it's different (to confirm it's not double hashed)
        $doubleHash = Hash::make('password123');
        $this->assertFalse(Hash::check($doubleHash, $hash), 'Password appears to be double hashed');
    }
}
