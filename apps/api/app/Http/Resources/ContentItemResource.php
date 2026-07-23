<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ideaId' => $this->idea_id,
            'projectId' => $this->project_id,
            'title' => $this->title,
            'content' => $this->content,
            'contentType' => $this->content_type,
            'status' => $this->status,
            'scheduledFor' => $this->scheduled_for,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
