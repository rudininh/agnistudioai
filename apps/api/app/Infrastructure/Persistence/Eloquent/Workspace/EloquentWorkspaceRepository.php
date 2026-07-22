
<?php

namespace App\Infrastructure\Persistence\Eloquent\Workspace;

use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Repositories\WorkspaceRepository;
use App\Domain\Authentication\Entities\User;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class EloquentWorkspaceRepository implements WorkspaceRepository
{
    /**
     * Find a workspace by its ID.
     */
    public function findById(string \): ?Workspace
    {
        \ = DB::table('workspaces')->where('id', \)->first();
        
        if (!\) {
            return null;
        }
        
        return ->modelToEntity(\);
    }

    /**
     * Find all workspaces owned by a user.
     */
    public function findByOwnerId(string \): array
    {
        \ = DB::table('workspaces')
            ->where('owner_id', \)
            ->get();
        
        return array_map([, 'modelToEntity'], ->toArray());
    }

    /**
     * Find workspaces by their IDs.
     */
    public function findByIds(array \): array
    {
        if (empty(\)) {
            return [];
        }
        
        \ = DB::table('workspaces')
            ->whereIn('id', \)
            ->get();
        
        return array_map([, 'modelToEntity'], ->toArray());
    }

    /**
     * Save a workspace.
     */
    public function save(Workspace \): void
    {
        \ = [
            'id' => \->getId()->toString(),
            'owner_id' => \->getOwnerId()->toString(),
            'name' => \->getName(),
            'slug' => \->getSlug(),
            'description' => \->getDescription(),
            'is_active' => \->isActive(),
            'settings' => json_encode(\->getSettings()),
            'updated_at' => now()
        ];

        // Check if workspace exists
        \ = DB::table('workspaces')
            ->where('id', \->getId()->toString())
            ->first();

        if (\) {
            // Update existing
            DB::table('workspaces')
                ->where('id', \->getId()->toString())
                ->update();
        } else {
            // Insert new
            \['created_at'] = now();
            \['updated_at'] = now();
            DB::table('workspaces')->insert();
        }
    }

    /**
     * Delete a workspace.
     */
    public function delete(Workspace \): void
    {
        DB::table('workspaces')
            ->where('id', \->getId()->toString())
            ->delete();
    }

    /**
     * Check if a workspace slug exists (excluding a specific workspace ID if provided).
     */
    public function existsBySlug(string \, ?string \ = null): bool
    {
        \ = DB::table('workspaces')
            ->where('slug', \);

        if (\) {
            \->where('id', '!=', \);
        }

        return \->exists();
    }

    /**
     * Count workspaces owned by a user.
     */
    public function countByOwner(string \): int
    {
        return DB::table('workspaces')
            ->where('owner_id', \)
            ->count();
    }

    /**
     * Find all workspaces with pagination.
     */
    public function findAll(int \ = 20, int \ = 0): array
    {
        \ = DB::table('workspaces')
            ->offset()
            ->limit()
            ->get();
        
        return array_map([, 'modelToEntity'], ->toArray());
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array \): Workspace
    {
        \ = new Workspace(
            \['name'],
            \['description'] ?? '',
            Uuid::fromString(\['owner_id']),
            \['description'] ?? null,
            Uuid::fromString(\['id']),
            isset(\['created_at']) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', \['created_at']) : null
        );
        
        // Set additional properties
        \->setSlug(\['slug']);
        \->setIsActive((bool) \['is_active']);
        \->setSettings(json_decode(\['settings'], true) ?? []);
        
        if (isset(\['updated_at'])) {
            // Note: We don't have a setter for updatedAt in the entity, but we could add one
            // For now, we'll note that the entity's updatedAt might not match the DB
        }
        
        return \;
    }
}

