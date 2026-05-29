<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SyncHazardRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array { return [ 'id' => ['required', 'uuid'], 'attachment_ids' => ['nullable', 'array'], 'attachment_ids.*' => ['uuid', 'exists:attachments,id'], ]; }
}
