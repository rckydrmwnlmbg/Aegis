<?php
namespace App\Actions\Attachment;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class UploadAttachmentAction {
    public function execute(UploadedFile $file, ?string $tenantId = null, ?string $userId = null): Attachment {
        $id = Str::uuid()->toString();
        $path = "tenant_{$tenantId}/attachments/{$id}";
        $filename = $file->getClientOriginalName();
        $storedPath = $file->storeAs($path, $filename, 'local');
        return Attachment::create([
            'id' => $id, 'tenant_id' => $tenantId, 'storage_provider' => 'local', 'storage_key' => $storedPath,
            'media_type' => $file->getMimeType(), 'size_bytes' => $file->getSize(), 'created_by' => $userId, 'status' => 'uploaded',
        ]);
    }
}
