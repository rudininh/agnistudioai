<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\ContentIdea;
use App\Domain\Content\Repositories\ContentIdeaRepository;
use App\Domain\Content\Repositories\ProjectRepository;
use App\Events\ContentIdeaCreated;
use App\Events\ContentIdeaUpdated;
use App\Jobs\ProcessContentItem;
use App\Jobs\ScheduleContentIdeaReminder;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ContentIdeaService implements ContentIdeaServiceInterface
{
    protected $ideaRepository;

    protected $projectRepository;

    protected $events;

    public function __construct(
        ContentIdeaRepository $ideaRepository,
        ProjectRepository $projectRepository,
        Dispatcher $events
    ) {
        $this->ideaRepository = $ideaRepository;
        $this->projectRepository = $projectRepository;
        $this->events = $events;
    }

    public function createIdea(string $title, string $description, string $contentType, string $projectId, ?string $scheduledFor = null): ContentIdea
    {
        // Validate that the project exists
        $project = $this->projectRepository->findById($projectId);
        if (! $project) {
            throw new \InvalidArgumentException('Project not found');
        }
        $idea = new ContentIdea(
            Uuid::fromString($projectId),
            $title,
            $description,
            $contentType
        );
        if ($scheduledFor) {
            $idea->setScheduledFor(new \DateTimeImmutable($scheduledFor));
        }
        $this->ideaRepository->save($idea);
        // Fire content idea created event
        $this->events->dispatch(new ContentIdeaCreated($idea));
        // Schedule a follow-up reminder for new ideas (example of job dispatching)
        // Delay the reminder by 3 days
        if ($idea->getStatus() === 'idea') { // Only for new ideas in 'idea' status
            (new ScheduleContentIdeaReminder($idea, 'follow_up'))
                ->delay(now()->addDays(3))
                ->dispatch();
        }

        return $idea;
    }

    public function updateIdea(ContentIdea $idea, string $title, string $description, string $contentType, ?string $scheduledFor = null): ContentIdea
    {
        $idea->setTitle($title);
        $idea->setDescription($description);
        $idea->setContentType($contentType);
        if ($scheduledFor !== null) {
            $idea->setScheduledFor(new \DateTimeImmutable($scheduledFor));
        } else {
            // If null is passed, we unschedule
            $idea->setScheduledFor(null);
        }
        $this->ideaRepository->save($idea);
        // Fire content idea updated event
        $this->events->dispatch(new ContentIdeaUpdated($idea));

        return $idea;
    }

    public function deleteIdea(ContentIdea $idea): void
    {
        $this->ideaRepository->delete($idea);
    }

    public function getIdeaById(string $ideaId): ?ContentIdea
    {
        return $this->ideaRepository->findById($ideaId);
    }

    public function getIdeasByProjectId(string $projectId): array
    {
        return $this->ideaRepository->findByProjectId($projectId);
    }

    public function getIdeasByStatus(string $status, string $projectId): array
    {
        return $this->ideaRepository->findByStatus($status, $projectId);
    }

    /**
     * Get content ideas by status across all projects.
     */
    public function getIdeasByStatusAcrossProjects(string $status): array
    {
        return $this->ideaRepository->findByStatusAcrossProjects($status);
    }

    public function submitForReview(ContentIdea $idea): void
    {
        $idea->submitForReview();
        $this->ideaRepository->save($idea);
    }

    public function approve(ContentIdea $idea): void
    {
        $idea->approve();
        $this->ideaRepository->save($idea);
        // When an idea is approved, we might want to process it immediately
        // and schedule some follow-up actions
        $this->processApprovedIdea($idea);
    }

    public function reject(ContentIdea $idea): void
    {
        $idea->reject();
        $this->ideaRepository->save($idea);
    }

    public function markInProgress(ContentIdea $idea): void
    {
        $idea->markInProgress();
        $this->ideaRepository->save($idea);
    }

    /**
     * Process an approved idea by creating content items and scheduling notifications
     */
    protected function processApprovedIdea(ContentIdea $idea): void
    {
        // Process the approved idea (this could be done asynchronously)
        // For example, create initial content items based on the idea
        // For now, we'll just log it
        Log::info("Processing approved idea: {$idea->getTitle()}");
        // Example: Schedule a reminder to check on the idea's progress in 7 days
        (new ScheduleContentIdeaReminder($idea, 'progress_check'))
            ->delay(now()->addDays(7))
            ->dispatch();
        // Example: Process the idea into content items immediately
        // (dispatching a job to handle the processing)
        (new ProcessContentItem($idea, 'approved'))
            ->delay(now()->addMinutes(5)) // Process after a short delay
            ->dispatch();
    }
}
