<?php

namespace Tests\Feature;

use App\Domain\Workspace\Entities\Workspace;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(User $user)
    {
        $token = $user->createToken('test-token')->plainTextToken;

        return $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ]);
    }

    public function test_authenticated_user_can_create_workspace(): void
    {
        $user = User::factory()->create();

        $response = $this->authenticate($user)->postJson('/api/v1/workspaces', [
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'ownerId',
                'isActive',
                'createdAt',
                'updatedAt',
                'settings',
            ],
        ]);

        $this->assertDatabaseHas('workspaces', [
            'name' => 'Test Workspace',
            'owner_id' => $user->id->toString(),
        ]);
    }

    public function test_authenticated_user_can_see_their_workspaces(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
            'owner_id' => $user->id,
            'slug' => 'test-workspace',
        ]);

        $response = $this->authenticate($user)->getJson('/api/v1/workspaces');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'ownerId',
                    'isActive',
                    'createdAt',
                    'updatedAt',
                    'settings',
                ],
            ],
        ]);

        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Workspace',
        ]);
    }

    public function test_authenticated_user_cannot_see_other_users_workspaces(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Other Workspace',
            'description' => 'An other user workspace',
            'owner_id' => $otherUser->id,
            'slug' => 'other-workspace',
        ]);

        $response = $this->authenticate($user)->getJson('/api/v1/workspaces');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_authenticated_user_can_get_a_specific_workspace(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
            'owner_id' => $user->id,
            'slug' => 'test-workspace',
        ]);

        $response = $this->authenticate($user)->getJson("/api/v1/workspaces/{$workspace->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'ownerId',
                'isActive',
                'createdAt',
                'updatedAt',
                'settings',
            ],
        ]);

        $response->assertJsonFragment([
            'name' => 'Test Workspace',
        ]);
    }

    public function test_authenticated_user_cannot_get_other_users_workspace(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Other Workspace',
            'description' => 'An other user workspace',
            'owner_id' => $otherUser->id,
            'slug' => 'other-workspace',
        ]);

        $response = $this->authenticate($user)->getJson("/api/v1/workspaces/{$workspace->id}");

        $response->assertStatus(403);
    }

    public function test_authenticated_user_can_update_workspace(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
            'owner_id' => $user->id,
            'slug' => 'test-workspace',
        ]);

        $response = $this->authenticate($user)->putJson("/api/v1/workspaces/{$workspace->id}", [
            'name' => 'Updated Workspace',
            'description' => 'An updated workspace',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'ownerId',
                'isActive',
                'createdAt',
                'updatedAt',
                'settings',
            ],
        ]);

        $response->assertJsonFragment([
            'name' => 'Updated Workspace',
            'description' => 'An updated workspace',
        ]);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'name' => 'Updated Workspace',
        ]);
    }

    public function test_authenticated_user_can_delete_workspace(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
            'owner_id' => $user->id,
            'slug' => 'test-workspace',
        ]);

        $response = $this->authenticate($user)->deleteJson("/api/v1/workspaces/{$workspace->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Workspace deleted successfully',
        ]);

        $this->assertDatabaseMissing('workspaces', [
            'id' => $workspace->id,
        ]);
    }

    public function test_authenticated_user_cannot_delete_other_users_workspace(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Other Workspace',
            'description' => 'An other user workspace',
            'owner_id' => $otherUser->id,
            'slug' => 'other-workspace',
        ]);

        $response = $this->authenticate($user)->deleteJson("/api/v1/workspaces/{$workspace->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
        ]);
    }
}
