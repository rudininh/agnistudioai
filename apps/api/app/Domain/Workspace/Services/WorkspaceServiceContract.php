<?php

namespace App\Domain\Workspace\Services;

use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Entities\WorkspaceMember;

interface WorkspaceService
{
    public function createWorkspace(string $name, string $description, string $ownerId, ?string $id = null): Workspace;

    public function getWorkspace(string $workspaceId): ?Workspace;

    public function getWorkspaceBySlug(string $slug): ?Workspace;

    public function updateWorkspace(string $workspaceId, string $name, ?string $description = null): Workspace;

    public function deleteWorkspace(string $workspaceId): void;

    public function inviteUser(string $workspaceId, string $userId, string $role): WorkspaceMember;

    public function removeUser(string $workspaceId, string $userId): void;

    public function changeUserRole(string $workspaceId, string $userId, string $role): WorkspaceMember;

    public function getWorkspaceMembers(string $workspaceId): array;

    public function getUserWorkspaces(string $userId): array;

    public function userCanAccessWorkspace(string $userId, string $workspaceId): bool;

    public function userCanManageWorkspace(string $userId, string $workspaceId): bool;
}
