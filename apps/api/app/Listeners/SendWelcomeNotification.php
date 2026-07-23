<?php

namespace App\Listeners;

use App\Events\WorkspaceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeNotification implements ShouldQueue
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
    public function handle(WorkspaceCreated $event)
    {
        // For example, log a welcome message. In a real app, you might send an email or notification.
        Log::info('Welcome to the workspace: '.$event->workspace->getName());
        // You can also dispatch other events, send emails, etc.
        // This is queued because the class implements ShouldQueue.
    }
}
