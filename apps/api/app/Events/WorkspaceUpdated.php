<?php

namespace App\Events;

use App\Domain\Workspace\Entities\Workspace;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkspaceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Workspace $workspace;

    /**
     * Create a new event instance.
     */
    public function __construct(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }
}
