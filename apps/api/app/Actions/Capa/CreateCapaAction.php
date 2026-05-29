<?php

namespace App\Actions\Capa;

use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionUpdate;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateCapaAction
{
    public function execute(array $data, string $creatorId): CorrectiveAction
    {
        return DB::transaction(function () use ($data, $creatorId) {
            $capa = CorrectiveAction::create([
                'tenant_id' => $data['tenant_id'],
                'capa_number' => $data['capa_number'] ?? 'CAPA-' . strtoupper(Str::random(6)),
                'action_type' => $data['action_type'] ?? 'corrective',
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'source_type' => $data['source_type'],
                'source_id' => $data['source_id'],
                'owner_id' => $data['owner_id'] ?? null,
                'site_id' => $data['site_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'due_date' => isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
                'status' => 'open',
            ]);

            CorrectiveActionUpdate::create([
                'tenant_id' => $capa->tenant_id,
                'corrective_action_id' => $capa->id,
                'updater_id' => $creatorId,
                'update_type' => 'status_change',
                'previous_status' => null,
                'new_status' => 'open',
                'notes' => 'CAPA created'
            ]);

            AuditEvent::create([
                'tenant_id' => $capa->tenant_id,
                'domain' => 'capa',
                'entity_type' => CorrectiveAction::class,
                'entity_id' => $capa->id,
                'action_type' => 'capa.create',
                'actor_id' => $creatorId,
                'actor_type' => 'user',
                'occurred_at' => now(),
                'metadata_json' => json_encode([
                    'status' => ['new' => 'open'],
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'system'
                ])
            ]);

            return $capa;
        });
    }
}
