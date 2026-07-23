<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
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
    public function handle(UserRegistered $event)
    {
        // Log that we're sending a welcome email
        // In a real application, you would actually send an email here
        Log::info('Sending welcome email to: '.$event->user->getEmailValue(), [
            'user_id' => $event->user->getId()->toString(),
            'email' => $event->user->getEmailValue(),
        ]);
        // You can also dispatch other events, send notifications, etc.
    }
}
