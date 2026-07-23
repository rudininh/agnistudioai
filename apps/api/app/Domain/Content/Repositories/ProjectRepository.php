<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\Project;

interface ProjectRepository
{
    public function findById(string $id): ?Project;

    public function findByWorkspaceId(string $workspaceId): array;

    public function findByIds(array $ids): array;

    public function findBySlug(string $workspaceId, string $slug): ?Project;

    public function save(Project $project): void;

    public function delete(Project $project): void;

    public function deleteByWorkspaceId(string $workspaceId): void;

    public function countByWorkspace(string $workspaceId): int;

    public function existsBySlug(string $workspaceId, string $slug, ?string $excludeId = null): bool;
}
