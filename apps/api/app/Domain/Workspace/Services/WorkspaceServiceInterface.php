<?php

namespace App\Domain\Workspace\Services;

use App\Domain\Workspace\Entities\Workspace;

interface WorkspaceServiceInterface
{
    public function createWorkspace(string $name, string $description, string $ownerId): Workspace;

    public function updateWorkspace(Workspace $workspace, string $name, ?string $description): Workspace;

    public function deleteWorkspace(Workspace $workspace): void;

    public function getWorkspaceById(string $workspaceId): ?Workspace;

    public function getWorkspacesByOwnerId(string $ownerId): array;
}
