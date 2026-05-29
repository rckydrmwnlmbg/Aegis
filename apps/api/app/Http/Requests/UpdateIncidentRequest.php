<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('incident:investigate') || $this->user()->can('incident:create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|string|in:draft,open,in_progress,resolved,closed',
            'title' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string',
            'incident_type' => 'sometimes|string|max:255',
            'severity_status' => 'sometimes|string|max:255',
            'metadata' => 'sometimes|array',
            // Allow other fields as needed based on the schema
        ];
    }
}
