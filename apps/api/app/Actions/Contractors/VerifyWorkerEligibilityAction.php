<?php

namespace App\Actions\Contractors;

use App\Models\Worker;
use Exception;
use Illuminate\Support\Carbon;

class VerifyWorkerEligibilityAction
{
    public function execute(string $workerId, ?string $requiredCertificateType = null): bool
    {
        $worker = Worker::with('certifications')->findOrFail($workerId);

        $certifications = $worker->certifications;

        if ($requiredCertificateType) {
            $certifications = $certifications->where('certificate_type', $requiredCertificateType);
        }

        if ($certifications->isEmpty()) {
            throw new Exception("Worker does not have the required certifications.");
        }

        $now = Carbon::now()->startOfDay();

        $hasValidCertification = $certifications->contains(function ($cert) use ($now) {
            return Carbon::parse($cert->valid_until)->startOfDay()->gte($now);
        });

        if (!$hasValidCertification) {
            throw new Exception("Worker's certification has expired.");
        }

        return true;
    }
}
