<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CorrectiveAction;
use App\Actions\Capa\CreateCapaAction;
use App\Actions\Capa\UploadCapaEvidenceAction;
use App\Actions\Capa\VerifyCapaAction;
use App\Http\Requests\StoreCapaRequest;
use App\Http\Requests\UpdateCapaStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CapaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CorrectiveAction::query();

        // Optional filtering by status
        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $perPage = $request->query('per_page', 15);
        $capas = $query->paginate($perPage);

        return response()->json([
            'data' => $capas->items(),
            'meta' => [
                'current_page' => $capas->currentPage(),
                'last_page' => $capas->lastPage(),
                'per_page' => $capas->perPage(),
                'total' => $capas->total(),
            ]
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $capa = CorrectiveAction::findOrFail($id);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
    }

    public function store(StoreCapaRequest $request, CreateCapaAction $action)
    {
        $validatedData = $request->validated();

        // Security Fix: Override any provided tenant_id with the authenticated user's tenant_id
        // to prevent Cross-Tenant Leakage via Mass Assignment.
        $validatedData['tenant_id'] = $request->user()->tenant_id;

        $capa = $action->execute($validatedData, $request->user()->id);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ], 201);
    }

    public function updateStatus(UpdateCapaStatusRequest $request, string $id): JsonResponse
    {
        $capa = CorrectiveAction::findOrFail($id);
        $capa->status = $request->validated('status');
        $capa->save();

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
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

        $request->validate([
            'attachment_id' => 'required|uuid',
            'notes' => 'nullable|string',
        ]);

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

        $request->validate([
            'decision' => ['required', \Illuminate\Validation\Rule::in(['close', 'reject'])],
            'notes' => 'nullable|string',
        ]);

        $capa = $action->execute($capa, $request->user()->id, $request->decision, $request->notes);

        return response()->json([
            'data' => $capa,
            'meta' => ['timestamp' => now()->toIso8601String()]
        ]);
    }
}
