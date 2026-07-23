<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\Project;

interface ProjectServiceInterface
{
    public function createProject(string $name, string $description, string $workspaceId): Project;

    public function updateProject(Project $project, string $name, ?string $description): Project;

    public function deleteProject(Project $project): void;

    public function getProjectById(string $projectId): ?Project;

    public function getProjectsByWorkspaceId(string $workspaceId): array;
}
