<?php

namespace App\Infrastructure\Persistence\Eloquent\Workspace;

use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EloquentWorkspaceRepository implements WorkspaceRepository
{
    /**
     * Find a workspace by its ID.
     */
    public function findById(string $id): ?Workspace
    {
        $row = DB::table('workspaces')->where('id', $id)->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Find all workspaces by owner ID.
     */
    public function findByOwnerId(string $ownerId): array
    {
        $rows = DB::table('workspaces')
            ->where('owner_id', $ownerId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find workspaces by their IDs.
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $rows = DB::table('workspaces')
            ->whereIn('id', $ids)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Save a workspace.
     */
    public function save(Workspace $workspace): void
    {
        $data = [
            'id' => $workspace->getId()->toString(),
            'owner_id' => $workspace->getOwnerId()->toString(),
            'name' => $workspace->getName(),
            'slug' => $workspace->getSlug(),
            'description' => $workspace->getDescription(),
            'status' => $workspace->getStatus(),
            'updated_at' => now(),
        ];
        // Check if workspace exists
        $existing = DB::table('workspaces')
            ->where('id', $workspace->getId()->toString())
            ->first();
        if ($existing) {
            // Update existing
            unset($data['created_at']);
            DB::table('workspaces')
                ->where('id', $workspace->getId()->toString())
                ->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            DB::table('workspaces')->insert($data);
        }
    }

    /**
     * Delete a workspace.
     */
    public function delete(Workspace $workspace): void
    {
        DB::table('workspaces')
            ->where('id', $workspace->getId()->toString())
            ->delete();
    }

    /**
     * Check if a workspace slug exists (excluding a specific workspace ID if provided).
     */
    public function existsBySlug(string $slug, ?string $workspaceId = null): bool
    {
        $query = DB::table('workspaces')
            ->where('slug', $slug);
        if ($workspaceId) {
            $query->where('id', '!=', $workspaceId);
        }

        return $query->exists();
    }

    /**
     * Count workspaces by owner.
     */
    public function countByOwner(string $ownerId): int
    {
        return (int) DB::table('workspaces')
            ->where('owner_id', $ownerId)
            ->count();
    }

    /**
     * Find all workspaces with pagination.
     */
    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $rows = DB::table('workspaces')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $row): Workspace
    {
        $workspace = new Workspace(
            $row['name'],
            $row['description'] ?? '',
            Uuid::fromString($row['owner_id']),
            $row['description'] ?? null,
            Uuid::fromString($row['id']),
            isset($row['created_at']) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['created_at']) : null
        );
        // Set additional properties
        $workspace->setSlug($row['slug']);
        $workspace->setStatus($row['status'] ?? 'active');

        // Note: We don't have setters for createdAt/updatedAt in the entity
        // For now, we'll note that the entity's timestamps might not match the DB
        return $workspace;
    }
}
