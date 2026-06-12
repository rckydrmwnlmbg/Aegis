<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreJsaRequest extends FormRequest
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
            'project_reference' => ['nullable', 'uuid', 'exists:projects,id'],
            'linked_permit_id' => ['nullable', 'uuid', 'exists:permit_to_works,id'],
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.id' => ['required', 'uuid'],
            'tasks.*.task_order' => ['required', 'integer', 'min:1'],
            'tasks.*.description' => ['required', 'string'],
            'tasks.*.hazards' => ['nullable', 'array'],
            'tasks.*.hazards.*.id' => ['required', 'uuid'],
            'tasks.*.hazards.*.description' => ['required', 'string'],
            'tasks.*.hazards.*.likelihood_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'tasks.*.hazards.*.severity_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'tasks.*.hazards.*.residual_score' => ['nullable', 'integer', 'min:1', 'max:25'],
            'tasks.*.hazards.*.controls' => ['nullable', 'array'],
            'tasks.*.hazards.*.controls.*.id' => ['required', 'uuid'],
            'tasks.*.hazards.*.controls.*.control_type' => ['required', 'string', 'in:elimination,substitution,engineering,administrative,ppe'],
            'tasks.*.hazards.*.controls.*.description' => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('id')) {
            $this->merge([
                'id' => Str::uuid()->toString()
            ]);
        }
    }
}
