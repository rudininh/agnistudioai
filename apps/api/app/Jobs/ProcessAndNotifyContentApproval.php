<?php

namespace App\Jobs;

use App\Domain\Authentication\Entities\User;
use App\Domain\Content\Entities\ContentItem;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ProcessAndNotifyContentApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contentItem;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(ContentItem $contentItem, User $user)
    {
        $this->contentItem = $contentItem;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting processing and notification for content item {$this->contentItem->getId()->toString()} approved by user {$this->user->getId()->toString()}");
        // Create a batch of jobs to process the content and send notifications
        $batch = Bus::batch([
            new ProcessContentItem($this->contentItem, 'standard'),
            new SendNotificationEmail($this->user, 'content_approved', [
                'content_title' => $this->contentItem->getTitle(),
                'content_id' => $this->contentItem->getId()->toString(),
            ]),
        ])->then(function (Batch $batch) {
            // All jobs completed successfully
            Log::info("Batch completed successfully for content item {$this->contentItem->getId()->toString()}");
        })->catch(function (Batch $batch, \Throwable $e) {
            // First batch failure
            Log::error("Batch failed for content item {$this->contentItem->getId()->toString()}: {$e->getMessage()}");
        })->finally(function (Batch $batch) {
            // The batch has finished executing
            Log::info("Batch finished execution for content item {$this->contentItem->getId()->toString()}");
        })->dispatch();
        // For simplicity in this example, we won't wait for the batch
        // In a real application, you might want to store the batch ID and check status later
        Log::info("Dispatched batch for content item {$this->contentItem->getId()->toString()}");
    }

    /**
     * The batch canceled.
     */
    public function cancelled()
    {
        // Handle the batch being canceled
        Log::warning("Batch was cancelled for content item {$this->contentItem->getId()->toString()}");
    }
}
