<?php

namespace App\Listeners;

use App\Events\ProjectUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogProjectUpdate implements ShouldQueue
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
    public function handle(ProjectUpdated $event)
    {
        // Log the project update
        Log::info('Project updated: '.$event->project->getName().' (ID: '.$event->project->getId()->toString().')');
        // In a real application, you might send notifications, update search indexes, etc.
    }
}
