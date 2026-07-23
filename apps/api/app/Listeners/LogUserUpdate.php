<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserUpdate implements ShouldQueue
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
    public function handle(UserUpdated $event)
    {
        // Log the user update
        Log::info('User updated: '.$event->user->getFullName().' ('.$event->user->getEmailValue().')', [
            'user_id' => $event->user->getId()->toString(),
            'email' => $event->user->getEmailValue(),
        ]);
    }
}
