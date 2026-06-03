<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\CorrectiveAction;
use App\Models\PermitToWork;
use App\Models\HazardObservation;
use App\Models\Worker;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get analytics summary for the dashboard.
     *
     * @return array
     */
    public function getSummary(): array
    {
        // Incident Model uses TenantScope implicitly
        $totalOpenIncidents = Incident::where('status', '!=', 'closed')->count();

        // CAPAs overdue
        $totalOverdueCapas = CorrectiveAction::where('status', '!=', 'closed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->count();

        // Active PTWs today
        $today = Carbon::today();
        $activePtws = PermitToWork::where('status', 'active')
            ->where(function ($query) use ($today) {
                $query->whereDate('valid_from', '<=', $today)
                      ->whereDate('valid_until', '>=', $today);
            })
            ->count();

        // Hazard Participation Rate
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $hazardsThisWeek = HazardObservation::whereBetween('observed_at', [$startOfWeek, $endOfWeek])->count();
        $activeWorkers = Worker::count(); // Assuming all workers are active, could add 'status' if exists

        $hazardParticipationRate = 0;
        if ($activeWorkers > 0) {
            $hazardParticipationRate = round(($hazardsThisWeek / $activeWorkers) * 100, 2);
        }

        return [
            'total_open_incidents' => $totalOpenIncidents,
            'total_overdue_capas' => $totalOverdueCapas,
            'active_ptws' => $activePtws,
            'hazard_participation_rate' => $hazardParticipationRate,
        ];
    }
}
