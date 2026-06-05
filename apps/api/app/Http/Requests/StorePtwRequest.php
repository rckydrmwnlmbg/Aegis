<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePtwRequest extends FormRequest
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
            'job_title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'work_type' => ['required', 'in:hot_work,cold_work,confined_space,excavation'],
            'jsa_steps' => ['sometimes', 'array'],
            'jsa_steps.*.id' => ['required_with:jsa_steps', 'uuid'],
            'jsa_steps.*.job_step' => ['required_with:jsa_steps', 'string'],
            'jsa_steps.*.potential_hazards' => ['required_with:jsa_steps', 'string'],
            'jsa_steps.*.risk_level' => ['required_with:jsa_steps', 'in:low,medium,high,extreme'],
            'jsa_steps.*.control_measures' => ['required_with:jsa_steps', 'string'],
        ];
    }
}
