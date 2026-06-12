<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Jsa;
use App\Models\JsaTask;
use App\Models\JsaHazard;
use App\Models\JsaControl;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreJsaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JsaController extends Controller
{
    /**
     * Store a newly created JSA along with tasks, hazards, and controls.
     */
    public function store(StoreJsaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        try {
            // Create JSA
            $jsa = Jsa::create([
                'id' => $validated['id'],
                'tenant_id' => $user->tenant_id,
                'title' => $validated['title'],
                'status' => 'draft',
                'prepared_by' => $user->id,
                'project_reference' => $validated['project_reference'] ?? null,
                'linked_permit_id' => $validated['linked_permit_id'] ?? null,
                'version' => 1,
            ]);

            // Process Tasks
            if (isset($validated['tasks']) && is_array($validated['tasks'])) {
                foreach ($validated['tasks'] as $taskData) {
                    $task = JsaTask::create([
                        'id' => $taskData['id'],
                        'tenant_id' => $user->tenant_id,
                        'jsa_id' => $jsa->id,
                        'task_order' => $taskData['task_order'],
                        'description' => $taskData['description'],
                    ]);

                    // Process Hazards
                    if (isset($taskData['hazards']) && is_array($taskData['hazards'])) {
                        foreach ($taskData['hazards'] as $hazardData) {
                            $hazard = JsaHazard::create([
                                'id' => $hazardData['id'],
                                'tenant_id' => $user->tenant_id,
                                'jsa_task_id' => $task->id,
                                'description' => $hazardData['description'],
                                'likelihood_score' => $hazardData['likelihood_score'] ?? null,
                                'severity_score' => $hazardData['severity_score'] ?? null,
                                'residual_score' => $hazardData['residual_score'] ?? null,
                            ]);

                            // Process Controls
                            if (isset($hazardData['controls']) && is_array($hazardData['controls'])) {
                                foreach ($hazardData['controls'] as $controlData) {
                                    JsaControl::create([
                                        'id' => $controlData['id'],
                                        'tenant_id' => $user->tenant_id,
                                        'hazard_id' => $hazard->id,
                                        'control_type' => $controlData['control_type'],
                                        'description' => $controlData['description'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            // Reload with relations to return full object
            $jsa->load(['tasks.hazards.controls']);

            return response()->json([
                'data' => $jsa,
                'meta' => [
                    'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                    'timestamp' => now()->toIso8601String()
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'code' => 'JSA_CREATION_FAILED',
                    'message' => 'Failed to create JSA',
                    'details' => $e->getMessage()
                ],
                'meta' => [
                    'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                    'timestamp' => now()->toIso8601String()
                ]
            ], 500);
        }
    }
}
