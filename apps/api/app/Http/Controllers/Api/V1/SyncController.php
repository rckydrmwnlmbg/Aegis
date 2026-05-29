<?php
namespace App\Http\Controllers\Api\V1;
use App\Actions\Sync\SyncPayloadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SyncHazardRequest;
use App\Http\Requests\SyncIncidentRequest;
use App\Jobs\ProcessHazardDataJob;
use App\Jobs\ProcessIncidentDataJob;
use Illuminate\Http\JsonResponse;

class SyncController extends Controller {
    public function incidents(SyncIncidentRequest $request, SyncPayloadAction $action): JsonResponse {
        $uuid = $request->input('id');
        $action->execute(
            'incident_report',
            'incidents',
            $uuid,
            $request->except(['id', 'attachment_ids']),
            $request->input('attachment_ids', []),
            $request->user()->tenant_id,
            $request->user()->id,
            function ($aiRun) {
                ProcessIncidentDataJob::dispatch($aiRun)->afterCommit();
            }
        );
        return response()->json([
            'meta' => [
                'status' => 202,
                'message' => 'Accepted',
                'correlation_id' => request()->header('X-Correlation-ID'),
                'timestamp' => now()->toIso8601String()
            ]
        ], 202);
    }
    public function hazards(SyncHazardRequest $request, SyncPayloadAction $action): JsonResponse {
        $uuid = $request->input('id');
        $action->execute(
            'hazard_report',
            'hazards',
            $uuid,
            $request->except(['id', 'attachment_ids']),
            $request->input('attachment_ids', []),
            $request->user()->tenant_id,
            $request->user()->id,
            function ($aiRun) {
                ProcessHazardDataJob::dispatch($aiRun)->afterCommit();
            }
        );
        return response()->json([
            'meta' => [
                'status' => 202,
                'message' => 'Accepted',
                'correlation_id' => request()->header('X-Correlation-ID'),
                'timestamp' => now()->toIso8601String()
            ]
        ], 202);
    }
}
