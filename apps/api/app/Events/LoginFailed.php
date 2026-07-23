<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class LoginFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;

    public string $ipAddress;

    public string $userAgent;

    /**
     * Create a new event instance.
     */
    public function __construct(string $email, Request $request)
    {
        $this->email = $email;
        $this->ipAddress = $request->ip();
        $this->userAgent = $request->header('User-Agent', 'unknown');
    }
}
