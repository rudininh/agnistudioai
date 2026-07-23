<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\ContentItem;

interface ContentItemRepository
{
    public function findById(string $id): ?ContentItem;

    public function findByIdeaId(string $ideaId): ?ContentItem;

    public function findByIds(array $ids): array;

    public function findByStatus(array $statuses): array;

    public function findByDateRange(string $workspaceId, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array;

    public function save(ContentItem $contentItem): void;

    public function delete(ContentItem $contentItem): void;

    public function deleteByIdeaId(string $ideaId): void;

    public function countByStatus(string $status): int;

    public function countByWorkspaceAndDateRange(string $workspaceId, \DateTimeInterface $startDate, \DateTimeInterface $endDate): int;
}
