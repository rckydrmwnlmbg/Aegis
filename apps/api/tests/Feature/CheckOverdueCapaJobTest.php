<?php

namespace Tests\Feature;

use App\Jobs\CheckOverdueCapaJob;
use App\Models\CorrectiveAction;
use App\Models\Site;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckOverdueCapaJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_capa_is_marked_overdue_based_on_timezone()
    {
        $tenant = Tenant::create(['tenant_code' => 'TEST-002', 'name' => 'Test Tenant']);

        $site = Site::create([
            'tenant_id' => $tenant->id,
            'name' => 'Asia Site',
            'timezone' => 'Asia/Jakarta' // UTC+7
        ]);

        // Scenario: Due date is May 29th.
        // It should be overdue when the local time in Asia/Jakarta is > May 29th end of day.
        $dueDate = Carbon::parse('2026-05-29');

        $capa = CorrectiveAction::create([
            'tenant_id' => $tenant->id,
            'title' => 'Test',
            'site_id' => $site->id,
            'status' => 'open',
            'due_date' => $dueDate,
            'source_type' => 'test',
            'source_id' => '123'
        ]);

        // Mock current time to be 2026-05-30 00:01:00 in Asia/Jakarta
        Carbon::setTestNow(Carbon::parse('2026-05-30 00:01:00', 'Asia/Jakarta'));

        // Since we are mocking now() to be after due_date's endOfDay in local timezone, it should be marked overdue
        $job = new CheckOverdueCapaJob();
        $job->handle();

        $this->assertEquals('overdue', $capa->fresh()->status);

        $this->assertDatabaseHas('corrective_action_updates', [
            'corrective_action_id' => $capa->id,
            'new_status' => 'overdue'
        ]);

        $this->assertDatabaseHas('audit_events', [
            'entity_type' => CorrectiveAction::class,
            'entity_id' => $capa->id,
            'action_type' => 'capa.sla_breach'
        ]);

        Carbon::setTestNow();
    }

    public function test_capa_is_not_marked_overdue_if_not_passed_in_timezone()
    {
        $tenant = Tenant::create(['tenant_code' => 'TEST-004', 'name' => 'Test Tenant']);

        $site = Site::create([
            'tenant_id' => $tenant->id,
            'name' => 'US Site',
            'timezone' => 'America/New_York' // UTC-4
        ]);

        $dueDate = Carbon::parse('2026-05-29');

        $capa = CorrectiveAction::create([
            'tenant_id' => $tenant->id,
            'title' => 'Test',
            'site_id' => $site->id,
            'status' => 'open',
            'due_date' => $dueDate,
            'source_type' => 'test',
            'source_id' => '123'
        ]);

        // Mock current time to be 2026-05-29 23:59:00 in America/New_York
        Carbon::setTestNow(Carbon::parse('2026-05-29 23:59:00', 'America/New_York'));

        $job = new CheckOverdueCapaJob();
        $job->handle();

        $this->assertEquals('open', $capa->fresh()->status);

        Carbon::setTestNow();
    }
}
