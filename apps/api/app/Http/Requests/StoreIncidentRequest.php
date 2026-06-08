<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
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
            'summary' => ['required', 'string'],
            'incident_type' => ['required', 'string'],
            'occurred_at' => ['required', 'date'],
            'location_reference' => ['nullable', 'string', 'max:255'],
        ];
    }
}
