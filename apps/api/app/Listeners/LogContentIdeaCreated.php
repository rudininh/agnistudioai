<?php

namespace App\Listeners;

use App\Events\ContentIdeaCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogContentIdeaCreated implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContentIdeaCreated $event)
    {
        // Log the content idea creation
        Log::info('Content idea created: '.$event->idea->getTitle().' (ID: '.$event->idea->getId()->toString().')', [
            'idea_id' => $event->idea->getId()->toString(),
            'title' => $event->idea->getTitle(),
            'project_id' => $event->idea->getProjectId()->toString(),
        ]);
    }
}
