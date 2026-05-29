<?php
namespace App\Actions\Sync;
use App\Models\AiRun;
use App\Models\AttachmentLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class SyncPayloadAction {
    public function execute(string $useCase, string $workflowDomain, string $uuid, array $payload, ?array $attachmentIds, string $tenantId, string $userId, \Closure $dispatchJob): bool {

        // Shield 1: Eloquent Check
        $existing = AiRun::where('workflow_entity_id', $uuid)
            ->where('use_case', $useCase)
            ->first();

        if ($existing) { return false; }

        // Shield 2: DB Transaction & Unique Constraint Catch
        try {
            DB::beginTransaction();

            $aiRunId = Str::uuid()->toString();
            $aiRun = new AiRun();
            $aiRun->id = $aiRunId;
            $aiRun->tenant_id = $tenantId;
            $aiRun->actor_id = $userId;
            $aiRun->use_case = $useCase;
            $aiRun->workflow_domain = $workflowDomain;
            $aiRun->workflow_entity_id = $uuid;
            $aiRun->payload = $payload;
            $aiRun->status = 'pending';
            $aiRun->occurred_at = now();
            $aiRun->save();

            if ($attachmentIds) {
                $attachmentIds = array_unique($attachmentIds);
                foreach ($attachmentIds as $attachmentId) {
                    $link = new AttachmentLink();
                    $link->id = Str::uuid()->toString();
                    $link->tenant_id = $tenantId;
                    $link->attachment_id = $attachmentId;
                    $link->domain = $workflowDomain;
                    $link->entity_type = 'ai_runs';
                    $link->entity_id = $aiRunId;
                    $link->linkage_type = 'sync_evidence';
                    $link->linked_by = $userId;
                    $link->save();
                }
            }

            DB::commit();

            // Critical: dispatch after transaction successfully commits
            $dispatchJob($aiRun);

            return true;
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            DB::rollBack();
            return false;
        }
    }
}
