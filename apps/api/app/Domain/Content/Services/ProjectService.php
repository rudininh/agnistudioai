<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\Project;
use App\Domain\Content\Repositories\ProjectRepository;
use App\Domain\Workspace\Repositories\WorkspaceRepository;
use App\Events\ProjectCreated;
use App\Events\ProjectUpdated;
use Illuminate\Contracts\Events\Dispatcher;
use Ramsey\Uuid\Uuid;

class ProjectService implements ProjectServiceInterface
{
    protected $projectRepository;

    protected $workspaceRepository;

    protected $events;

    public function __construct(
        ProjectRepository $projectRepository,
        WorkspaceRepository $workspaceRepository,
        Dispatcher $events
    ) {
        $this->projectRepository = $projectRepository;
        $this->workspaceRepository = $workspaceRepository;
        $this->events = $events;
    }

    public function createProject(string $name, string $description, string $workspaceId): Project
    {
        // Optionally, validate that the workspace exists
        $workspace = $this->workspaceRepository->findById($workspaceId);
        if (! $workspace) {
            throw new \InvalidArgumentException('Workspace not found');
        }
        $project = new Project($name, $description, Uuid::fromString($workspaceId));
        $this->projectRepository->save($project);
        // Fire project created event
        $this->events->dispatch(new ProjectCreated($project));

        return $project;
    }

    public function updateProject(Project $project, string $name, ?string $description): Project
    {
        if ($name) {
            $project->setName($name);
        }
        if ($description !== null) {
            $project->setDescription($description);
        }
        $this->projectRepository->save($project);
        // Fire project updated event
        $this->events->dispatch(new ProjectUpdated($project));

        return $project;
    }

    public function deleteProject(Project $project): void
    {
        $this->projectRepository->delete($project);
    }

    public function getProjectById(string $projectId): ?Project
    {
        return $this->projectRepository->findById($projectId);
    }

    public function getProjectsByWorkspaceId(string $workspaceId): array
    {
        return $this->projectRepository->findByWorkspaceId($workspaceId);
    }
}
