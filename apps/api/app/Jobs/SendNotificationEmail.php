<?php

namespace App\Jobs;

use App\Domain\Authentication\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $notificationType;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $notificationType, array $data = [])
    {
        $this->user = $user;
        $this->notificationType = $notificationType;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log the notification sending (in a real app, you would actually send the email)
        Log::info("Sending {$this->notificationType} notification to user {$this->user->getEmailValue()}", [
            'user_id' => $this->user->getId()->toString(),
            'notification_type' => $this->notificationType,
            'data' => $this->data,
        ]);
        // Simulate some processing time
        // In a real application, you would use a mail service here
        // For example: Mail::to($this->user->getEmailValue())->send(new NotificationMail($this->data));
        // Mark as processed (you might want to update a database record here)
        // For demo purposes, we just log completion
        Log::info("Notification sent successfully to {$this->user->getEmailValue()}");
    }
}
