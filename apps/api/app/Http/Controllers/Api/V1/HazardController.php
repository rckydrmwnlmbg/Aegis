<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HazardObservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Actions\Hazard\UpdateHazardAction;
use App\Http\Requests\UpdateHazardRequest;
use Illuminate\Support\Str;

class HazardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = HazardObservation::query();

        // Optional filtering by status
        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        // Apply sorting
        $sortField = $request->query('sort_by', 'created_at');
        $sortDirection = $request->query('sort_dir', 'desc');

        $allowedSortFields = ['created_at', 'updated_at', 'observed_at', 'status', 'risk_score'];
        if (in_array($sortField, $allowedSortFields) && in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->query('per_page', 15);
        $hazards = $query->paginate($perPage);

        return response()->json([
            'data' => $hazards->items(),
            'meta' => [
                'current_page' => $hazards->currentPage(),
                'last_page' => $hazards->lastPage(),
                'per_page' => $hazards->perPage(),
                'total' => $hazards->total(),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $hazard = HazardObservation::findOrFail($id);

        return response()->json([
            'data' => $hazard,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHazardRequest $request, string $id, UpdateHazardAction $action): JsonResponse
    {
        $hazard = HazardObservation::findOrFail($id);

        $updatedHazard = $action->execute($hazard, $request->validated(), $request->user());

        return response()->json([
            'data' => $updatedHazard,
            'meta' => [
                'correlation_id' => request()->header('X-Correlation-ID', Str::uuid()->toString()),
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }
}
