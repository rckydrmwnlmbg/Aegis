<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use App\Actions\Permits\UpdatePermitStatusAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class PermitToWorkController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'permit_type_id' => 'nullable|uuid|exists:permit_types,id',
            'jsa_id' => 'nullable|uuid|exists:jsas,id',
            'work_scope' => 'nullable|string',
            'location_reference' => 'nullable|uuid',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $validated['status'] = 'draft';
        $validated['requested_by'] = $request->user()->id;
        $validated['tenant_id'] = $request->user()->tenant_id;

        $permit = PermitToWork::create($validated);

        return response()->json([
            'data' => $permit,
            'meta' => [
                'message' => 'Permit To Work drafted successfully',
            ]
        ], 201);
    }

    public function updateStatus(Request $request, PermitToWork $permit, UpdatePermitStatusAction $action): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(\App\Enums\PermitStatus::values())],
            'decision_notes' => ['nullable', 'string']
        ]);

        $newStatus = $validated['status'];
        $notes = $validated['decision_notes'] ?? null;

        try {
            $updatedPermit = $action->execute($permit, $newStatus, $notes);

            return response()->json([
                'data' => $updatedPermit,
                'meta' => [
                    'message' => "Permit status updated to {$newStatus}",
                ]
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => $e->getMessage()
                ]
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'code' => 'INVALID_STATE_TRANSITION',
                    'message' => $e->getMessage()
                ]
            ], 422);
        }
    }
}
