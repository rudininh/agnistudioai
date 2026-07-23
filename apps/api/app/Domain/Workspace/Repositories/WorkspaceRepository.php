<?php

namespace App\Domain\Workspace\Repositories;

use App\Domain\Workspace\Entities\Workspace;

interface WorkspaceRepository
{
    public function findById(string $id): ?Workspace;

    public function findByOwnerId(string $ownerId): array;

    public function findByIds(array $ids): array;

    public function save(Workspace $workspace): void;

    public function delete(Workspace $workspace): void;

    public function existsBySlug(string $slug, ?string $excludeId = null): bool;

    public function countByOwner(string $ownerId): int;

    public function findAll(int $limit = 20, int $offset = 0): array;
}
