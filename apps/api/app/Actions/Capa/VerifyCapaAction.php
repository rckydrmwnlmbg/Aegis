<?php

namespace App\Actions\Capa;

use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionUpdate;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\DB;

class VerifyCapaAction
{
    public function execute(CorrectiveAction $capa, string $verifierId, string $decision, ?string $notes = null): CorrectiveAction
    {
        return DB::transaction(function () use ($capa, $verifierId, $decision, $notes) {
            $previousStatus = $capa->status;

            // $decision is either 'close' or 'reject'
            $newStatus = $decision === 'close' ? 'closed' : 'open'; // 'open' or 'in_progress' could be the fallback for rejected.

            $capa->update([
                'status' => $newStatus,
                'version' => $capa->version + 1
            ]);

            CorrectiveActionUpdate::create([
                'tenant_id' => $capa->tenant_id,
                'corrective_action_id' => $capa->id,
                'updater_id' => $verifierId,
                'update_type' => 'verified',
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => $notes ?? "Evidence verification decision: $decision"
            ]);

            AuditEvent::create([
                'tenant_id' => $capa->tenant_id,
                'domain' => 'capa',
                'entity_type' => CorrectiveAction::class,
                'entity_id' => $capa->id,
                'action_type' => 'capa.verify',
                'actor_id' => $verifierId,
                'actor_type' => 'user',
                'occurred_at' => now(),
                'metadata_json' => json_encode([
                    'status' => ['old' => $previousStatus, 'new' => $newStatus],
                    'decision' => $decision,
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'system'
                ])
            ]);

            return $capa;
        });
    }
}
