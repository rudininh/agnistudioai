<?php

namespace App\Jobs;

use App\Domain\Content\Entities\ContentItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessContentItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contentItem;

    protected $processingType;

    /**
     * Create a new job instance.
     */
    public function __construct(ContentItem $contentItem, string $processingType = 'standard')
    {
        $this->contentItem = $contentItem;
        $this->processingType = $processingType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log the processing start
        Log::info("Starting {$this->processingType} processing for content item {$this->contentItem->getId()->toString()}", [
            'content_item_id' => $this->contentItem->getId()->toString(),
            'content_item_title' => $this->contentItem->getTitle(),
            'processing_type' => $this->processingType,
        ]);
        // Simulate processing based on type
        switch ($this->processingType) {
            case 'image_resize':
                // In a real app, you would resize images here
                // Intervention\Image::make($path)->resize(300, 200)->save($path);
                sleep(2); // Simulate work
                break;
            case 'video_transcode':
                // In a real app, you would transcode videos here
                // ffmpeg -i input.mp4 -vcodec libx264 output.mp4
                sleep(5); // Simulate work
                break;
            case 'document_scan':
                // In a real app, you would scan documents for text/virus scanning here
                sleep(3); // Simulate work
                break;
            default:
                // Standard processing
                sleep(1); // Simulate work
                break;
        }
        // Update the content item status if needed
        // $this->contentItem->setStatus('processed');
        // You would need to save this through a repository or service
        // Log completion
        Log::info("Completed {$this->processingType} processing for content item {$this->contentItem->getId()->toString()}");
    }
}
