<?php

namespace App\Listeners;

use App\Events\ContentIdeaUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogContentIdeaUpdated implements ShouldQueue
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
    public function handle(ContentIdeaUpdated $event)
    {
        // Log the content idea update
        Log::info('Content idea updated: '.$event->idea->getTitle().' (ID: '.$event->idea->getId()->toString().')', [
            'idea_id' => $event->idea->getId()->toString(),
            'title' => $event->idea->getTitle(),
            'project_id' => $event->idea->getProjectId()->toString(),
        ]);
    }
}
