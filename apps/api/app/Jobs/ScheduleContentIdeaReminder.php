<?php

namespace App\Jobs;

use App\Domain\Content\Entities\ContentIdea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScheduleContentIdeaReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $idea;

    protected $reminderType;

    /**
     * Create a new job instance.
     */
    public function __construct(ContentIdea $idea, string $reminderType = 'general')
    {
        $this->idea = $idea;
        $this->reminderType = $reminderType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log the reminder
        Log::info("Sending {$this->reminderType} reminder for idea {$this->idea->getId()->toString()}", [
            'idea_id' => $this->idea->getId()->toString(),
            'idea_title' => $this->idea->getTitle(),
            'reminder_type' => $this->reminderType,
        ]);
        // In a real application, you would send a notification here
        // For example, you might send an email, Slack message, or in-app notification
        // Example (pseudo-code):
        // Notification::send($this->idea->getAssignedUser(), new IdeaReminder($this->idea, $this->reminderType));
    }
}
