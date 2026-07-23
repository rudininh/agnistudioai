<?php

namespace App\Domain\Workspace\Repositories;

use App\Domain\Workspace\Entities\WorkspaceMember;

interface WorkspaceMemberRepository
{
    public function findById(string $id): ?WorkspaceMember;

    public function findByUserAndWorkspace(string $userId, string $workspaceId): ?WorkspaceMember;

    public function findByWorkspaceId(string $workspaceId): array;

    public function findByUserId(string $userId): array;

    public function save(WorkspaceMember $workspaceMember): void;

    public function delete(WorkspaceMember $workspaceMember): void;

    public function deleteByWorkspaceId(string $workspaceId): void;

    public function deleteByUserId(string $userId): void;

    public function countByWorkspace(string $workspaceId): int;

    public function countByUser(string $userId): int;

    public function exists(string $userId, string $workspaceId): bool;
}
