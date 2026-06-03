<?php

namespace App\Actions\Certifications;

use App\Models\Certification;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class GetExpiringCertificationsAction
{
    public function execute(int $days = 30, int $perPage = 15): LengthAwarePaginator
    {
        $targetDate = Carbon::now()->addDays($days);

        return Certification::with('worker')
            ->where('valid_until', '<=', $targetDate)
            ->orderBy('valid_until', 'asc')
            ->paginate($perPage);
    }
}
