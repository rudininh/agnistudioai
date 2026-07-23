<?php

namespace App\Infrastructure\Persistence\Eloquent\Workspace;

use App\Domain\Authentication\Entities\User;
use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Repositories\WorkspaceRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EloquentWorkspaceRepository implements WorkspaceRepository
{
    /**
     * Find a workspace by its ID.
     */
    public function findById(string $id): ?Workspace
    {
        $data = DB::table('workspaces')->where('id', $id)->first();
        if (! $data) {
            return null;
        }

        return $this->modelToEntity($data);
    }

    /**
     * Find all workspaces owned by a user.
     */
    public function findByOwnerId(string $ownerId): array
    {
        $data = DB::table('workspaces')
            ->where('owner_id', $ownerId)
            ->get();

        return array_map([$this, 'modelToEntity'], $data->toArray());
    }

    /**
     * Find workspaces by their IDs.
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $data = DB::table('workspaces')
            ->whereIn('id', $ids)
            ->get();

        return array_map([$this, 'modelToEntity'], $data->toArray());
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
            'is_active' => $workspace->isActive(),
            'settings' => json_encode($workspace->getSettings()),
            'updated_at' => now(),
        ];
        // Check if workspace exists
        $existing = DB::table('workspaces')
            ->where('id', $workspace->getId()->toString())
            ->first();
        if ($existing) {
            // Update existing
            DB::table('workspaces')
                ->where('id', $workspace->getId()->toString())
                ->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            $data['updated_at'] = now();
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
    public function existsBySlug(string $slug, ?string $excludeId = null): bool
    {
        $query = DB::table('workspaces')
            ->where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Count workspaces owned by a user.
     */
    public function countByOwner(string $ownerId): int
    {
        return DB::table('workspaces')
            ->where('owner_id', $ownerId)
            ->count();
    }

    /**
     * Find all workspaces with pagination.
     */
    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $data = DB::table('workspaces')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return array_map([$this, 'modelToEntity'], $data->toArray());
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $data): Workspace
    {
        $workspace = new Workspace(
            $data['name'],
            $data['description'] ?? '',
            Uuid::fromString($data['owner_id']),
            $data['description'] ?? null,
            Uuid::fromString($data['id']),
            isset($data['created_at']) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']) : null
        );
        // Set additional properties
        $workspace->setSlug($data['slug']);
        $workspace->setIsActive((bool) $data['is_active']);
        $workspace->setSettings(json_decode($data['settings'], true) ?? []);
        if (isset($data['updated_at'])) {
            // Note: We don't have a setter for updatedAt in the entity, but we could add one
            // For now, we'll note that the entity's updatedAt might not match the DB
        }

        return $workspace;
    }
}
