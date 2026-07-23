<?php

namespace App\Domain\Workspace\Services;

use App\Domain\Authentication\Repositories\EloquentUserRepository;
use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Entities\WorkspaceMember;
use App\Domain\Workspace\Repositories\EloquentWorkspaceMemberRepository;
use App\Domain\Workspace\Repositories\EloquentWorkspaceRepository;
use Ramsey\Uuid\Uuid;

class WorkspaceService implements WorkspaceServiceInterface
{
    protected $workspaceRepository;

    protected $workspaceMemberRepository;

    protected $userRepository;

    public function __construct(
        EloquentWorkspaceRepository $workspaceRepository,
        EloquentWorkspaceMemberRepository $workspaceMemberRepository,
        EloquentUserRepository $userRepository
    ) {
        $this->workspaceRepository = $workspaceRepository;
        $this->workspaceMemberRepository = $workspaceMemberRepository;
        $this->userRepository = $userRepository;
    }

    public function createWorkspace(string $name, string $description, string $ownerId): Workspace
    {
        // Create the workspace
        $workspace = new Workspace($name, $description, Uuid::fromString($ownerId));
        // Save the workspace
        $this->workspaceRepository->save($workspace);
        // Add the owner as a member with owner role
        $this->workspaceMemberRepository->save(
            WorkspaceMember::fromArray([
                'workspace_id' => $workspace->getId()->toString(),
                'user_id' => $ownerId,
                'role' => 'owner',
            ])
        );

        return $workspace;
    }

    public function updateWorkspace(Workspace $workspace, string $name, ?string $description): Workspace
    {
        if ($name) {
            $workspace->setName($name);
        }
        if ($description !== null) {
            $workspace->setDescription($description);
        }
        $this->workspaceRepository->save($workspace);

        return $workspace;
    }

    public function deleteWorkspace(Workspace $workspace): void
    {
        $this->workspaceRepository->delete($workspace);
    }

    public function getWorkspaceById(string $workspaceId): ?Workspace
    {
        return $this->workspaceRepository->findById($workspaceId);
    }

    public function getWorkspacesByOwnerId(string $ownerId): array
    {
        return $this->workspaceRepository->findByOwnerId($ownerId);
    }
}
