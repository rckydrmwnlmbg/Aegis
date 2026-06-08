<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Actions\Incident\UpdateIncidentAction;
use App\Http\Requests\UpdateIncidentRequest;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentSeverityRequest;
use Illuminate\Support\Str;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Incident::query();

        // Optional filtering by status
        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        // Apply sorting
        $sortField = $request->query('sort_by', 'created_at');
        $sortDirection = $request->query('sort_dir', 'desc');

        $allowedSortFields = ['created_at', 'updated_at', 'occurred_at', 'reported_at', 'status'];
        if (in_array($sortField, $allowedSortFields) && in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->query('per_page', 15);
        $incidents = $query->paginate($perPage);

        return response()->json([
            'data' => $incidents->items(),
            'meta' => [
                'current_page' => $incidents->currentPage(),
                'last_page' => $incidents->lastPage(),
                'per_page' => $incidents->perPage(),
                'total' => $incidents->total(),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $incident = Incident::findOrFail($id);

        return response()->json([
            'data' => $incident,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage (Triage).
     */
    public function store(StoreIncidentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $incident = Incident::create([
            'id' => $validated['id'],
            'tenant_id' => $request->user()->tenant_id,
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'incident_type' => $validated['incident_type'],
            'occurred_at' => $validated['occurred_at'],
            'reported_at' => now(),
            'reported_by' => $request->user()->id,
            'location_reference' => $validated['location_reference'] ?? null,
            'status' => 'draft',
        ]);

        return response()->json([
            'data' => $incident,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncidentRequest $request, string $id, UpdateIncidentAction $action): JsonResponse
    {
        $incident = Incident::findOrFail($id);

        $updatedIncident = $action->execute($incident, $request->validated(), $request->user());

        return response()->json([
            'data' => $updatedIncident,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * Update the severity of the incident.
     */
    public function updateSeverity(UpdateIncidentSeverityRequest $request, string $id): JsonResponse
    {
        $incident = Incident::findOrFail($id);
        $incident->severity_status = $request->validated('severity_status');
        $incident->save();

        return response()->json([
            'data' => $incident,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }
}
