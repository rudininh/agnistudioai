<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\ContentIdea;
use App\Domain\Content\Entities\ContentItem;
use App\Domain\Content\Entities\Project;
use App\Domain\Workspace\Entities\Workspace;

interface ContentService
{
    // Project Management
    public function createProject(Workspace $workspace, string $name, string $description, ?string $status = null): Project;

    public function getProject(string $id): ?Project;

    public function updateProject(string $id, string $name, ?string $description = null): Project;

    public function deleteProject(string $id): void;

    public function archiveProject(string $id): void;

    public function getProjectsByWorkspace(string $workspaceId): array;

    // Content Idea Management
    public function createIdea(Project $project, string $title, string $description, string $status): ContentIdea;

    public function getIdea(string $id): ?ContentIdea;

    public function updateIdea(string $id, string $title, string $description, string $status): ContentIdea;

    public function deleteIdea(string $id): void;

    public function updateIdeaStatus(string $id, string $status): ContentIdea;

    public function updateIdeaPriority(string $id, int $priority): ContentIdea;

    public function addTagToIdea(string $ideaId, string $tag): void;

    public function removeTagFromIdea(string $ideaId, string $tag): void;

    public function getIdeasByProject(string $projectId): array;

    public function getIdeasByProjectAndStatus(string $projectId, string $status): array;

    // Content Item Management
    public function createContent(ContentIdea $idea, string $title, string $body, string $status): ContentItem;

    public function getContent(string $id): ?ContentItem;

    public function updateContent(string $id, string $title, string $body, string $status): ContentItem;

    public function deleteContent(string $id): void;

    public function publishContent(string $id): ContentItem;

    public function scheduleContent(string $id, \DateTimeInterface $scheduledAt): ContentItem;

    public function getContentByIdea(string $ideaId): ?ContentItem;

    public function getPublishedContentByWorkspace(string $workspaceId, int $limit = 10, int $offset = 0): array;

    public function getContentFeed(string $workspaceId, string $status = 'published', int $limit = 20, int $offset = 0): array;
}
