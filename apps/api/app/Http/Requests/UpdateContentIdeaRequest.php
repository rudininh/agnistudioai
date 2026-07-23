<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContentIdeaRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'content_type' => 'sometimes|required|string|in:blog,social,video,podcast,newsletter',
            'project_id' => 'sometimes|required|string|exists:projects,id',
            'scheduled_for' => 'sometimes|nullable|date',
            'status' => 'sometimes|required|string|idea,draft,review,approved,rejected,in_progress,completed',
        ];
    }
}
