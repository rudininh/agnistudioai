<?php

namespace App\Infrastructure\Persistence\Eloquent\Workspace;

use App\Domain\Workspace\Entities\WorkspaceMember;
use App\Domain\Workspace\Repositories\WorkspaceMemberRepository;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EloquentWorkspaceMemberRepository implements WorkspaceMemberRepository
{
    /**
     * Find a workspace member by its ID.
     */
    public function findById(string $id): ?WorkspaceMember
    {
        $row = DB::table('workspace_members')->where('id', $id)->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Find a workspace member by user ID and workspace ID.
     */
    public function findByUserAndWorkspace(string $userId, string $workspaceId): ?WorkspaceMember
    {
        $row = DB::table('workspace_members')
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Find all workspace members by workspace ID.
     */
    public function findByWorkspaceId(string $workspaceId): array
    {
        $rows = DB::table('workspace_members')
            ->where('workspace_id', $workspaceId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find all workspace members by user ID.
     */
    public function findByUserId(string $userId): array
    {
        $rows = DB::table('workspace_members')
            ->where('user_id', $userId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Save a workspace member.
     */
    public function save(WorkspaceMember $workspaceMember): void
    {
        $data = [
            'id' => $workspaceMember->getId()->toString(),
            'workspace_id' => $workspaceMember->getWorkspaceId()->toString(),
            'user_id' => $workspaceMember->getUserId()->toString(),
            'role' => $workspaceMember->getRole(),
            'joined_at' => $workspaceMember->getJoinedAt()?->toDateTimeString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Check if workspace member exists
        $existing = DB::table('workspace_members')
            ->where('id', $workspaceMember->getId()->toString())
            ->first();
        if ($existing) {
            // Update existing
            unset($data['created_at']);
            DB::table('workspace_members')
                ->where('id', $workspaceMember->getId()->toString())
                ->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('workspace_members')->insert($data);
        }
    }

    /**
     * Delete a workspace member.
     */
    public function delete(WorkspaceMember $workspaceMember): void
    {
        DB::table('workspace_members')
            ->where('id', $workspaceMember->getId()->toString())
            ->delete();
    }

    /**
     * Delete workspace members by workspace ID.
     */
    public function deleteByWorkspaceId(string $workspaceId): void
    {
        DB::table('workspace_members')
            ->where('workspace_id', $workspaceId)
            ->delete();
    }

    /**
     * Delete workspace members by user ID.
     */
    public function deleteByUserId(string $userId): void
    {
        DB::table('workspace_members')
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Count workspace members by workspace.
     */
    public function countByWorkspace(string $workspaceId): int
    {
        return DB::table('workspace_members')
            ->where('workspace_id', $workspaceId)
            ->count();
    }

    /**
     * Count workspace members by user.
     */
    public function countByUser(string $userId): int
    {
        return DB::table('workspace_members')
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Check if a workspace member exists for a given user and workspace.
     */
    public function exists(string $userId, string $workspaceId): bool
    {
        return DB::table('workspace_members')
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->exists();
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $row): WorkspaceMember
    {
        $workspaceMember = new WorkspaceMember(
            Uuid::fromString($row['id']),
            Uuid::fromString($row['workspace_id']),
            Uuid::fromString($row['user_id']),
            $row['role'],
            isset($row['joined_at']) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['joined_at']) : null
        );

        // Set timestamps if the entity has setters (we might need to add them to the entity)
        // For now, we'll note that the entity's createdAt/updatedAt might not be set
        // but we can add setters to the entity if needed.
        return $workspaceMember;
    }
}
