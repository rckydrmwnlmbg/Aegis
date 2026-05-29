<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHazardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('hazard:verify') || $this->user()->can('hazard:assign');
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
            'description' => 'sometimes|string',
            'category_name' => 'sometimes|string|max:255',
            'severity_level' => 'sometimes|string|max:255',
            'risk_score' => 'sometimes|numeric',
            'metadata' => 'sometimes|array',
            // Allow other fields as needed based on the schema
        ];
    }
}
