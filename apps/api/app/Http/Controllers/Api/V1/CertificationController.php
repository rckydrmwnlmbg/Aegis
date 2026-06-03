<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Certifications\GetExpiringCertificationsAction;
use App\Actions\Certifications\RegisterCertificationAction;
use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function store(Request $request, string $workerId, RegisterCertificationAction $action): JsonResponse
    {
        // Explicitly load the worker to make sure it exists for the tenant
        Worker::findOrFail($workerId);

        $validated = $request->validate([
            'id' => ['nullable', 'uuid'],
            'certificate_type' => ['required', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'valid_until' => ['required', 'date'],
            'attachment_id' => ['nullable', 'uuid', 'exists:attachments,id'],
        ]);

        $certification = $action->execute($workerId, $validated);

        return response()->json([
            'data' => $certification,
            'meta' => [
                'message' => 'Certification registered successfully.'
            ]
        ], 201);
    }

    public function indexWorker(string $workerId): JsonResponse
    {
        // Ensure worker belongs to tenant
        Worker::findOrFail($workerId);

        $certifications = Certification::where('worker_id', $workerId)
            ->orderBy('valid_until', 'desc')
            ->get();

        return response()->json([
            'data' => $certifications,
            'meta' => [
                'count' => $certifications->count()
            ]
        ]);
    }

    public function expiring(Request $request, GetExpiringCertificationsAction $action): JsonResponse
    {
        $days = (int) $request->input('days', 30);
        $perPage = (int) $request->input('per_page', 15);

        $paginator = $action->execute($days, $perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}
