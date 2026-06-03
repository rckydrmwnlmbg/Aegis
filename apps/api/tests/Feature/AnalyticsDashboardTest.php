<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\CorrectiveAction;
use App\Models\HazardObservation;
use App\Models\Incident;
use App\Models\PermitToWork;
use App\Models\Tenant;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_returns_analytics_summary_with_tenant_isolation()
    {
        // Setup Tenant A
        $tenantA = Tenant::factory()->create();
        $userA = AppUser::factory()->create(['tenant_id' => $tenantA->id]);

        // Setup Tenant B
        $tenantB = Tenant::factory()->create();

        // 1. Incidents
        Incident::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'draft']);
        Incident::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'in_progress']);
        Incident::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'closed']);

        Incident::factory()->create(['tenant_id' => $tenantB->id, 'status' => 'draft']);

        // 2. CAPAs
        CorrectiveAction::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'open', 'due_date' => Carbon::now()->subDays(2)]); // Overdue
        CorrectiveAction::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'open', 'due_date' => Carbon::now()->addDays(2)]); // Not overdue
        CorrectiveAction::factory()->create(['tenant_id' => $tenantA->id, 'status' => 'closed', 'due_date' => Carbon::now()->subDays(2)]); // Closed but old

        CorrectiveAction::factory()->create(['tenant_id' => $tenantB->id, 'status' => 'open', 'due_date' => Carbon::now()->subDays(2)]); // Overdue

        // 3. PTWs
        PermitToWork::factory()->create([
            'tenant_id' => $tenantA->id,
            'status' => 'active',
            'valid_from' => Carbon::today(),
            'valid_until' => Carbon::today()->addDays(2),
        ]); // Active today
        PermitToWork::factory()->create([
            'tenant_id' => $tenantA->id,
            'status' => 'expired',
            'valid_from' => Carbon::today()->subDays(5),
            'valid_until' => Carbon::today()->subDays(2),
        ]); // Expired

        PermitToWork::factory()->create([
            'tenant_id' => $tenantB->id,
            'status' => 'active',
            'valid_from' => Carbon::today(),
            'valid_until' => Carbon::today()->addDays(2),
        ]); // Active today

        // 4. Hazard Participation Rate
        // Total workers Tenant A: 5
        for ($i = 0; $i < 5; $i++) {
            Worker::factory()->create(['tenant_id' => $tenantA->id]);
        }

        // Hazard observations this week Tenant A: 4
        for ($i = 0; $i < 4; $i++) {
            HazardObservation::factory()->create([
                'tenant_id' => $tenantA->id,
                'observed_at' => Carbon::now()->startOfWeek()->addDay(),
            ]);
        }

        // Hazard observation outside this week Tenant A: 2
        for ($i = 0; $i < 2; $i++) {
            HazardObservation::factory()->create([
                'tenant_id' => $tenantA->id,
                'observed_at' => Carbon::now()->subWeeks(2),
            ]);
        }

        Worker::factory()->create(['tenant_id' => $tenantB->id]);
        HazardObservation::factory()->create(['tenant_id' => $tenantB->id, 'observed_at' => Carbon::now()]);

        // Expected for Tenant A:
        // total_open_incidents: 2 (draft, in_progress)
        // total_overdue_capas: 1
        // active_ptws: 1
        // hazard_participation_rate: 4 / 5 * 100 = 80.00%

        $response = $this->actingAs($userA)->getJson('/api/v1/analytics/summary');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'total_open_incidents' => 2,
                    'total_overdue_capas' => 1,
                    'active_ptws' => 1,
                    'hazard_participation_rate' => 80.00,
                ]
            ]);
    }
}
