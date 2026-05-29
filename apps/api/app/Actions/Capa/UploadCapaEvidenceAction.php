<?php

namespace App\Actions\Capa;

use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionUpdate;
use App\Models\AttachmentLink;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\DB;

class UploadCapaEvidenceAction
{
    public function execute(CorrectiveAction $capa, string $attachmentId, string $uploaderId, ?string $notes = null): CorrectiveAction
    {
        return DB::transaction(function () use ($capa, $attachmentId, $uploaderId, $notes) {

            AttachmentLink::create([
                'tenant_id' => $capa->tenant_id,
                'attachment_id' => $attachmentId,
                'domain' => 'capa',
                'entity_type' => CorrectiveAction::class,
                'entity_id' => $capa->id,
                'linkage_type' => 'evidence',
                'linked_at' => now(),
                'linked_by' => $uploaderId
            ]);

            $previousStatus = $capa->status;
            $newStatus = 'pending_verification';

            $capa->update([
                'status' => $newStatus,
                'version' => $capa->version + 1
            ]);

            CorrectiveActionUpdate::create([
                'tenant_id' => $capa->tenant_id,
                'corrective_action_id' => $capa->id,
                'updater_id' => $uploaderId,
                'update_type' => 'evidence_uploaded',
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => $notes ?? 'Evidence uploaded for verification'
            ]);

            AuditEvent::create([
                'tenant_id' => $capa->tenant_id,
                'domain' => 'capa',
                'entity_type' => CorrectiveAction::class,
                'entity_id' => $capa->id,
                'action_type' => 'capa.upload_evidence',
                'actor_id' => $uploaderId,
                'actor_type' => 'user',
                'occurred_at' => now(),
                'metadata_json' => json_encode([
                    'status' => ['old' => $previousStatus, 'new' => $newStatus],
                    'attachment_id' => $attachmentId,
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'system'
                ])
            ]);

            return $capa;
        });
    }
}
