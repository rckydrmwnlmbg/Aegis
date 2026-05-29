<?php

namespace App\Actions\Hazard;

use App\Models\HazardObservation;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateHazardAction
{
    /**
     * Update a hazard observation and record audit event if status changes.
     */
    public function execute(HazardObservation $hazard, array $data, $user): HazardObservation
    {
        $oldStatus = $hazard->status;

        DB::transaction(function () use ($hazard, $data, $user, $oldStatus) {
            $hazard->update($data);

            $newStatus = $hazard->status;

            if ($oldStatus === 'draft' && $newStatus !== 'draft') {
                $this->recordAuditEvent($hazard, $user, $oldStatus, $newStatus, 'status_change_from_draft');
            } elseif ($oldStatus !== $newStatus) {
                $this->recordAuditEvent($hazard, $user, $oldStatus, $newStatus, 'status_change');
            } else {
                 $this->recordAuditEvent($hazard, $user, $oldStatus, $newStatus, 'update');
            }
        });

        return $hazard->refresh();
    }

    private function recordAuditEvent(HazardObservation $hazard, $user, string $oldStatus, string $newStatus, string $actionType): void
    {
        AuditEvent::create([
            'id' => Str::uuid()->toString(),
            'tenant_id' => $hazard->tenant_id,
            'domain' => 'hazard',
            'entity_type' => 'hazards',
            'entity_id' => $hazard->id,
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
