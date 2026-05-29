<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Actions\Incident\UpdateIncidentAction;
use App\Http\Requests\UpdateIncidentRequest;
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
}
