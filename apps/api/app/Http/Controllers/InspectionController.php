<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionTemplate;
use App\Services\InspectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InspectionController extends Controller
{
    protected $inspectionService;

    public function __construct(InspectionService $inspectionService)
    {
        $this->inspectionService = $inspectionService;
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Inspection::class);

        // Global TenantScope applies
        $inspections = Inspection::with(['template', 'site', 'conductor'])->get();

        return response()->json([
            'data' => $inspections,
            'meta' => [
                'count' => $inspections->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Inspection::class);

        $validated = $request->validate([
            'id' => 'required|uuid', // Client-side UUID rule
            'template_id' => 'required|uuid|exists:inspection_templates,id',
            'site_id' => 'nullable|uuid|exists:sites,id',
        ]);

        $template = InspectionTemplate::findOrFail($validated['template_id']);
        if ($template->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Template does not belong to your tenant.',
                    'details' => [],
                ]
            ], 403);
        }

        $inspection = Inspection::create([
            'id' => $validated['id'],
            'tenant_id' => $request->user()->tenant_id,
            'template_id' => $validated['template_id'],
            'site_id' => $validated['site_id'] ?? null,
            'conducted_by' => $request->user()->id,
            'status' => 'draft',
        ]);

        return response()->json([
            'data' => $inspection,
            'meta' => [
                'message' => 'Inspection created successfully.',
            ],
        ], 201);
    }

    public function show(Request $request, Inspection $inspection)
    {
        Gate::authorize('view', $inspection);

        return response()->json([
            'data' => $inspection->load(['template.items', 'responses', 'site', 'conductor']),
            'meta' => [],
        ]);
    }

    public function update(Request $request, Inspection $inspection)
    {
        Gate::authorize('update', $inspection);

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*.template_item_id' => 'required|uuid|exists:inspection_template_items,id',
            'responses.*.response_value' => 'nullable|string',
            'responses.*.response_boolean' => 'nullable|boolean',
            'responses.*.attachment_id' => 'nullable|uuid|exists:attachments,id',
        ]);

        if ($inspection->status === 'completed') {
            return response()->json([
                'error' => [
                    'code' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'Cannot update a completed inspection.',
                    'details' => [],
                ]
            ], 422);
        }

        $this->inspectionService->updateResponses($inspection, $validated['responses'], $request->user()->tenant_id);

        return response()->json([
            'data' => $inspection->load('responses'),
            'meta' => [
                'message' => 'Inspection responses updated successfully.',
            ],
        ]);
    }

    public function start(Request $request, Inspection $inspection)
    {
        Gate::authorize('update', $inspection);

        try {
            $inspection = $this->inspectionService->startInspection($inspection, []);
            return response()->json([
                'data' => $inspection,
                'meta' => [
                    'message' => 'Inspection started successfully.',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'code' => 'UNPROCESSABLE_ENTITY',
                    'message' => $e->getMessage(),
                    'details' => [],
                ]
            ], 422);
        }
    }

    public function complete(Request $request, Inspection $inspection)
    {
        Gate::authorize('update', $inspection);

        try {
            $inspection = $this->inspectionService->completeInspection($inspection);
            return response()->json([
                'data' => $inspection,
                'meta' => [
                    'message' => 'Inspection completed successfully.',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'code' => 'UNPROCESSABLE_ENTITY',
                    'message' => $e->getMessage(),
                    'details' => [],
                ]
            ], 422);
        }
    }
}
