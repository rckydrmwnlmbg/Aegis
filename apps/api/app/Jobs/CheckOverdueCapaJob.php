<?php

namespace App\Jobs;

use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionUpdate;
use App\Models\AuditEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckOverdueCapaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $capas = CorrectiveAction::whereIn('status', ['open', 'in_progress'])
            ->whereNotNull('due_date')
            ->with(['site', 'project'])
            ->get();

        foreach ($capas as $capa) {
            $timezone = 'UTC';

            if ($capa->site && $capa->site->timezone) {
                $timezone = $capa->site->timezone;
            } elseif ($capa->project && $capa->project->timezone) {
                $timezone = $capa->project->timezone;
            }

            // Current time in the entity's timezone
            $localTime = Carbon::now($timezone);

            // Due date is usually stored in DB in UTC or without timezone explicitly bound in a single way,
            // we treat due_date as a representation of "the end of that day in that timezone" or "that exact time in that timezone".
            // Since the problem says: "only changing status to overdue if the local time of that site has passed 00:00 on the due_date".
            // This means we check if the current local time has started a new day after the due date.

            // Actually, "melewati jam 00:00 pada due_date" usually means the due date has passed.
            // Example: Due Date is 2026-06-01. At 2026-06-02 00:00:01 local time, it is overdue.
            // So if $localTime is strictly greater than the $due_date's end of day in that timezone.

            // To be precise: let's convert the stored due_date to that timezone and compare.
            $dueDateTime = Carbon::parse($capa->due_date, $timezone)->endOfDay();

            if ($localTime->greaterThan($dueDateTime)) {

                $previousStatus = $capa->status;

                $capa->update([
                    'status' => 'overdue',
                    'version' => $capa->version + 1
                ]);

                CorrectiveActionUpdate::create([
                    'tenant_id' => $capa->tenant_id,
                    'corrective_action_id' => $capa->id,
                    'update_type' => 'status_change',
                    'previous_status' => $previousStatus,
                    'new_status' => 'overdue',
                    'notes' => 'System marked as overdue'
                ]);

                AuditEvent::create([
                    'tenant_id' => $capa->tenant_id,
                    'domain' => 'capa',
                    'entity_type' => CorrectiveAction::class,
                    'entity_id' => $capa->id,
                    'action_type' => 'capa.sla_breach',
                    'actor_type' => 'system',
                    'occurred_at' => now(),
                    'metadata_json' => json_encode([
                        'status' => ['old' => $previousStatus, 'new' => 'overdue'],
                        'ip_address' => '127.0.0.1',
                        'user_agent' => 'system-scheduler'
                    ])
                ]);

                Log::info("CAPA {$capa->id} marked as overdue (Timezone: {$timezone})");
            }
        }
    }
}
