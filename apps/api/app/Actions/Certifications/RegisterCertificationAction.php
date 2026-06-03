<?php

namespace App\Actions\Certifications;

use App\Models\Certification;
use App\Models\AttachmentLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterCertificationAction
{
    public function execute(string $workerId, array $data): Certification
    {
        return DB::transaction(function () use ($workerId, $data) {
            $certification = Certification::create([
                'id' => $data['id'] ?? (string) Str::uuid(),
                'worker_id' => $workerId,
                'certificate_type' => $data['certificate_type'],
                'certificate_number' => $data['certificate_number'] ?? null,
                'valid_until' => $data['valid_until'],
            ]);

            if (!empty($data['attachment_id'])) {
                AttachmentLink::create([
                    'attachment_id' => $data['attachment_id'],
                    'entity_type' => Certification::class,
                    'entity_id' => $certification->id,
                    'domain' => 'certification',
                    'linkage_type' => 'document',
                    'linked_at' => now(),
                    'linked_by' => auth()->id(),
                ]);
            }

            return $certification->load('worker');
        });
    }
}
