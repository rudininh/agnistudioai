<?php

namespace App\Events;

use App\Domain\Content\Entities\ContentIdea;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentIdeaCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ContentIdea $idea;

    /**
     * Create a new event instance.
     */
    public function __construct(ContentIdea $idea)
    {
        $this->idea = $idea;
    }
}
