<?php

namespace App\Events;

use App\Domain\Authentication\Entities\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class LoginSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public string $ipAddress;

    public string $userAgent;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->ipAddress = $request->ip();
        $this->userAgent = $request->header('User-Agent', 'unknown');
    }
}
