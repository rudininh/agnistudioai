<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogProjectCreation implements ShouldQueue
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
    public function handle(ProjectCreated $event)
    {
        // Log the project creation
        Log::info('Project created: '.$event->project->getName().' (ID: '.$event->project->getId()->toString().')');
        // In a real application, you might send notifications, update analytics, etc.
    }
}
