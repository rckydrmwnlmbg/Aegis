<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCapaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_type' => ['required', 'string'],
            'source_id' => ['required', 'uuid'],
            'owner_id' => ['required', 'uuid'],
            'site_id' => ['nullable', 'uuid'],
            'project_id' => ['nullable', 'uuid'],
            'due_date' => ['nullable', 'date'],
            'action_type' => ['nullable', 'string'],
        ];
    }
}
