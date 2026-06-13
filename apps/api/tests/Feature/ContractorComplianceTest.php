<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\Certification;
use App\Models\PermitToWork;
use App\Models\Tenant;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class ContractorComplianceTest extends TestCase
{
    use RefreshDatabase;

    private AppUser $user;
    private Tenant $tenant;
    private PermitToWork $permit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'id' => Str::uuid(),
            'name' => 'Test Tenant',
            'tenant_code' => 'TEST-01',
        ]);

        $this->user = AppUser::factory()->create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'name' => 'Safety Officer',
            'email' => 'safety@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->permit = PermitToWork::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'title' => 'High Risk Job',
            'status' => 'draft',
            'requested_by' => $this->user->id,
        ]);
    }

    public function test_can_add_compliant_worker_to_ptw(): void
    {
        $worker = Worker::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe',
        ]);

        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $worker->id,
            'certificate_type' => 'SIO Rigger',
            'valid_until' => Carbon::now()->addDays(30),
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/v1/ptw/{$this->permit->id}/workers", [
            'worker_id' => $worker->id,
            'role' => 'Rigger',
            'required_certificate_type' => 'SIO Rigger'
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('meta.message', 'Worker successfully added to Permit To Work');

        $this->assertDatabaseHas('permit_workers', [
            'permit_id' => $this->permit->id,
            'worker_id' => $worker->id,
            'role' => 'Rigger',
        ]);
    }

    public function test_cannot_add_worker_with_expired_certification_to_ptw(): void
    {
        $worker = Worker::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'name' => 'Jane Doe',
        ]);

        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $worker->id,
            'certificate_type' => 'SIO Welder',
            'valid_until' => Carbon::now()->subDays(1), // Expired yesterday
        ]);

        $response = $this->actingAs($this->user)->postJson("/api/v1/ptw/{$this->permit->id}/workers", [
            'worker_id' => $worker->id,
            'role' => 'Welder',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => [
                    'code',
                    'message'
                ]
            ])
            ->assertJsonPath('error.code', 'WORKER_NOT_COMPLIANT');

        $this->assertDatabaseMissing('permit_workers', [
            'permit_id' => $this->permit->id,
            'worker_id' => $worker->id,
        ]);
    }
}
