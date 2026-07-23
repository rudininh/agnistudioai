<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\ContentIdea;

interface ContentIdeaServiceInterface
{
    public function createIdea(string $title, string $description, string $contentType, string $projectId, ?string $scheduledFor = null): ContentIdea;

    public function updateIdea(ContentIdea $idea, string $title, string $description, string $contentType, ?string $scheduledFor = null): ContentIdea;

    public function deleteIdea(ContentIdea $idea): void;

    public function getIdeaById(string $ideaId): ?ContentIdea;

    public function getIdeasByProjectId(string $projectId): array;

    public function getIdeasByStatus(string $status, string $projectId): array;

    /**
     * Get content ideas by status across all projects.
     */
    public function getIdeasByStatusAcrossProjects(string $status): array;

    public function submitForReview(ContentIdea $idea): void;

    public function approve(ContentIdea $idea): void;

    public function reject(ContentIdea $idea): void;

    public function markInProgress(ContentIdea $idea): void;
}
