<?php

namespace App\Actions\Incident;

use App\Models\Incident;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateIncidentAction
{
    /**
     * Update an incident and record audit event if status changes.
     */
    public function execute(Incident $incident, array $data, $user): Incident
    {
        $oldStatus = $incident->status;

        DB::transaction(function () use ($incident, $data, $user, $oldStatus) {
            $incident->update($data);

            $newStatus = $incident->status;

            if ($oldStatus === 'draft' && $newStatus !== 'draft') {
                $this->recordAuditEvent($incident, $user, $oldStatus, $newStatus, 'status_change_from_draft');
            } elseif ($oldStatus !== $newStatus) {
                $this->recordAuditEvent($incident, $user, $oldStatus, $newStatus, 'status_change');
            } else {
                 $this->recordAuditEvent($incident, $user, $oldStatus, $newStatus, 'update');
            }
        });

        return $incident->refresh();
    }

    private function recordAuditEvent(Incident $incident, $user, string $oldStatus, string $newStatus, string $actionType): void
    {
        AuditEvent::create([
            'id' => Str::uuid()->toString(),
            'tenant_id' => $incident->tenant_id,
            'domain' => 'incident',
            'entity_type' => 'incidents',
            'entity_id' => $incident->id,
            'action_type' => $actionType,
            'actor_id' => $user->id,
            'actor_type' => 'users',
            'occurred_at' => now(),
            'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
            'metadata_json' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);
    }
}
