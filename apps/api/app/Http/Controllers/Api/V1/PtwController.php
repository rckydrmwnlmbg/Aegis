<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePtwRequest;
use App\Http\Requests\UpdatePtwStatusRequest;
use App\Models\PtwDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PtwController extends Controller
{
    public function store(StorePtwRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $ptw = PtwDocument::create([
                'id' => $validated['id'],
                'job_title' => $validated['job_title'],
                'location' => $validated['location'],
                'work_type' => $validated['work_type'],
                'applicant_id' => Auth::id(),
                'status' => 'draft',
            ]);

            if (!empty($validated['jsa_steps'])) {
                foreach ($validated['jsa_steps'] as $step) {
                    $ptw->jsaSteps()->create([
                        'id' => $step['id'],
                        'job_step' => $step['job_step'],
                        'potential_hazards' => $step['potential_hazards'],
                        'risk_level' => $step['risk_level'],
                        'control_measures' => $step['control_measures'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'data' => $ptw->load('jsaSteps'),
                'meta' => ['message' => 'PTW draft created successfully']
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'code' => 'creation_failed',
                    'message' => 'Failed to create PTW document.',
                    'details' => $e->getMessage()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(UpdatePtwStatusRequest $request, $id): JsonResponse
    {
        $ptw = PtwDocument::findOrFail($id);
        $newStatus = $request->validated('status');
        $user = Auth::user();

        $isAssessor = $user && ($user->hasRole('Assessor') || $user->hasRole('SAFETY_OFFICER'));
        $isManager = $user && ($user->hasRole('Manager') || $user->hasRole('SITE_MANAGER'));

        // Logic check for Assessor
        if ($newStatus === 'pending_review' || ($newStatus === 'approved' && $ptw->status === 'draft')) {
            if (!$isAssessor) {
                return response()->json([
                    'error' => [
                        'code' => 'unauthorized',
                        'message' => 'Only Assessors can review or approve draft PTWs.'
                    ]
                ], Response::HTTP_FORBIDDEN);
            }
            $ptw->assessor_id = $user->id;
        }

        // Logic check for Manager
        if ($newStatus === 'approved' && $ptw->status === 'pending_review') {
            if (!$isManager) {
                return response()->json([
                    'error' => [
                        'code' => 'unauthorized',
                        'message' => 'Only Managers can give final approval.'
                    ]
                ], Response::HTTP_FORBIDDEN);
            }
            $ptw->manager_id = $user->id;
        }

        // Rejection logic
        if ($newStatus === 'rejected') {
            if (!$isAssessor && !$isManager) {
                return response()->json([
                    'error' => [
                        'code' => 'unauthorized',
                        'message' => 'Only Assessors or Managers can reject PTWs.'
                    ]
                ], Response::HTTP_FORBIDDEN);
            }
        }

        $ptw->status = $newStatus;
        $ptw->save();

        return response()->json([
            'data' => $ptw,
            'meta' => ['message' => 'PTW status updated successfully']
        ]);
    }
}
