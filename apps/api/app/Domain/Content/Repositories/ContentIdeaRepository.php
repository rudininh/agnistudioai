<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\ContentIdea;

interface ContentIdeaRepository
{
    public function findById(string $id): ?ContentIdea;

    public function findByProjectId(string $projectId): array;

    public function findByIds(array $ids): array;

    public function findByStatus(string $status, string $projectId): array;

    /**
     * Find content ideas by status across all projects.
     */
    public function findByStatusAcrossProjects(string $status): array;

    public function save(ContentIdea $contentIdea): void;

    public function delete(ContentIdea $contentIdea): void;

    public function deleteByProjectId(string $projectId): void;

    public function countByProject(string $projectId): int;

    public function countByProjectAndStatus(string $projectId, string $status): int;
}
