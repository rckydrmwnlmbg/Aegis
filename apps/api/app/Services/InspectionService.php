<?php

namespace App\Services;

use App\Models\Inspection;
use App\Models\InspectionResponse;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    public function startInspection(Inspection $inspection, array $data): Inspection
    {
        if ($inspection->status !== 'draft') {
            throw new \Exception('Inspection is not in draft state.');
        }

        $inspection->update([
            'status' => 'in_progress',
            'started_at' => now(),
            // Ensure conducted_by is updated if necessary, though it might be set at creation
        ]);

        return $inspection;
    }

    public function completeInspection(Inspection $inspection): Inspection
    {
        if ($inspection->status !== 'in_progress') {
            throw new \Exception('Only in_progress inspections can be completed.');
        }

        $inspection->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $inspection;
    }

    public function updateResponses(Inspection $inspection, array $responses, string $tenantId): void
    {
        DB::transaction(function () use ($inspection, $responses, $tenantId) {
            foreach ($responses as $response) {
                InspectionResponse::updateOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'inspection_id' => $inspection->id,
                        'template_item_id' => $response['template_item_id'],
                    ],
                    [
                        'response_value' => $response['response_value'] ?? null,
                        'response_boolean' => $response['response_boolean'] ?? null,
                        'attachment_id' => $response['attachment_id'] ?? null,
                    ]
                );
            }
        });
    }
}
