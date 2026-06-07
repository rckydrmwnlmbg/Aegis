<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CorrectiveAction;
use App\Actions\Capa\CreateCapaAction;
use App\Actions\Capa\UploadCapaEvidenceAction;
use App\Actions\Capa\VerifyCapaAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CapaController extends Controller
{
    public function store(Request $request, CreateCapaAction $action)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source_type' => 'required|string',
            'source_id' => 'required|uuid',
            'owner_id' => 'required|uuid',
            'site_id' => 'nullable|uuid',
            'project_id' => 'nullable|uuid',
            'due_date' => 'nullable|date',
            'action_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Invalid data provided.',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $validatedData = $validator->validated();

        // Security Fix: Override any provided tenant_id with the authenticated user's tenant_id
        // to prevent Cross-Tenant Leakage via Mass Assignment.
        $validatedData['tenant_id'] = $request->user()->tenant_id;

        $capa = $action->execute($validatedData, $request->user()->id);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ], 201);
    }

    public function myTasks(Request $request)
    {
        $capas = CorrectiveAction::where('owner_id', $request->user()->id)->get();

        return response()->json([
            'data' => $capas,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
    }

    public function uploadEvidence(Request $request, string $id, UploadCapaEvidenceAction $action)
    {
        $capa = CorrectiveAction::findOrFail($id);

        // In a real app, authorize user

        $validator = Validator::make($request->all(), [
            'attachment_id' => 'required|uuid',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Invalid data provided.',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $capa = $action->execute($capa, $request->attachment_id, $request->user()->id, $request->notes);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
    }

    public function verify(Request $request, string $id, VerifyCapaAction $action)
    {
        $capa = CorrectiveAction::findOrFail($id);

        // In a real app, authorize HSE officer role

        $validator = Validator::make($request->all(), [
            'decision' => ['required', Rule::in(['close', 'reject'])],
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Invalid data provided.',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $capa = $action->execute($capa, $request->user()->id, $request->decision, $request->notes);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
    }
}
