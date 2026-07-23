<?php

namespace App\Infrastructure\Persistence\Eloquent\Content;

use App\Domain\Content\Entities\Project;
use App\Domain\Content\Repositories\ProjectRepository;
use App\Domain\Workspace\Entities\Workspace;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EloquentProjectRepository implements ProjectRepository
{
    /**
     * Find a project by its ID.
     */
    public function findById(string $id): ?Project
    {
        $row = DB::table('projects')->where('id', $id)->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Find all projects by workspace ID.
     */
    public function findByWorkspaceId(string $workspaceId): array
    {
        $rows = DB::table('projects')
            ->where('workspace_id', $workspaceId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find projects by their IDs.
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $rows = DB::table('projects')
            ->whereIn('id', $ids)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find a project by its slug and workspace ID.
     */
    public function findBySlug(string $slug, string $workspaceId): ?Project
    {
        $row = DB::table('projects')
            ->where('slug', $slug)
            ->where('workspace_id', $workspaceId)
            ->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Save a project.
     */
    public function save(Project $project): void
    {
        $data = [
            'id' => $project->getId()->toString(),
            'workspace_id' => $project->getWorkspaceId()->toString(),
            'name' => $project->getName(),
            'slug' => $project->getSlug(),
            'description' => $project->getDescription(),
            'status' => $project->getStatus(),
            'priority' => $project->getPriority(),
            'updated_at' => now(),
        ];
        // Check if project exists
        $existing = DB::table('projects')
            ->where('id', $project->getId()->toString())
            ->first();
        if ($existing) {
            // Update existing
            unset($data['created_at']);
            DB::table('projects')
                ->where('id', $project->getId()->toString())
                ->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            DB::table('projects')->insert($data);
        }
    }

    /**
     * Delete a project.
     */
    public function delete(Project $project): void
    {
        DB::table('projects')
            ->where('id', $project->getId()->toString())
            ->delete();
    }

    /**
     * Delete projects by workspace ID.
     */
    public function deleteByWorkspaceId(string $workspaceId): void
    {
        DB::table('projects')
            ->where('workspace_id', $workspaceId)
            ->delete();
    }

    /**
     * Count projects by workspace.
     */
    public function countByWorkspace(string $workspaceId): int
    {
        return (int) DB::table('projects')
            ->where('workspace_id', $workspaceId)
            ->count();
    }

    /**
     * Check if a project slug exists (excluding a specific project ID if provided).
     */
    public function existsBySlug(string $slug, string $workspaceId, ?string $projectId = null): bool
    {
        $query = DB::table('projects')
            ->where('slug', $slug)
            ->where('workspace_id', $workspaceId);
        if ($projectId) {
            $query->where('id', '!=', $projectId);
        }

        return $query->exists();
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $row): Project
    {
        $project = new Project(
            $row['name'],
            $row['description'] ?? '',
            Uuid::fromString($row['workspace_id']),
            $row['description'] ?? null,
            Uuid::fromString($row['id']),
            isset($row['created_at']) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['created_at']) : null
        );
        // Set additional properties
        $project->setSlug($row['slug']);
        $project->setStatus($row['status'] ?? 'new');
        $project->setPriority((int) ($row['priority'] ?? 3));

        // Note: We don't have setters for createdAt/updatedAt in the entity, but we could add them.
        // For now, we'll note that the entity's timestamps might not match the DB.
        return $project;
    }
}
