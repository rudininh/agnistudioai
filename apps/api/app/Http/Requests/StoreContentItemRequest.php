<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContentItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your auth logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'content_type' => 'required|string|in:article,social_post,video,image,podcast',
            'idea_id' => 'sometimes|required|string|exists:content_ideas,id',
            'project_id' => 'sometimes|required|string|exists:projects,id',
            'status' => 'sometimes|required|string|draft,review,approved,scheduled,published,archived',
            'scheduled_for' => 'nullable|date',
        ];
    }
}
