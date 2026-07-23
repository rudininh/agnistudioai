<?php

namespace App\Listeners;

use App\Events\WorkspaceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogWorkspaceUpdate implements ShouldQueue
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
    public function handle(WorkspaceUpdated $event)
    {
        // Log the workspace update
        Log::info('Workspace updated: '.$event->workspace->getName().' (ID: '.$event->workspace->getId()->toString().')');
        // In a real application, you might send notifications, update search indexes, etc.
    }
}
