<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Certification;
use App\Models\Tenant;
use App\Models\AppUser;
use App\Models\Worker;
use App\Models\AttachmentLink;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CertificationApiTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private AppUser $user;
    private Worker $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['id' => Str::uuid(), 'name' => 'Aegis Corp', 'tenant_code' => 'AEGIS']);
        $this->user = AppUser::factory()->create(['tenant_id' => $this->tenant->id]);

        $this->worker = Worker::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe',
            'company' => 'Contractor A',
        ]);
    }

    public function test_can_register_certification_with_attachment()
    {
        Sanctum::actingAs($this->user);

        $attachment = Attachment::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'media_type' => 'application/pdf',
            'size_bytes' => 1024,
            'storage_provider' => 's3',
            'storage_key' => 's3-key',
            'created_by' => $this->user->id,
        ]);

        $payload = [
            'certificate_type' => 'SIO Forklift',
            'certificate_number' => 'SIO-12345',
            'valid_until' => Carbon::now()->addMonths(6)->toDateString(),
            'attachment_id' => $attachment->id,
        ];

        $response = $this->postJson("/api/v1/workers/{$this->worker->id}/certifications", $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.certificate_type', 'SIO Forklift')
            ->assertJsonPath('data.worker_id', $this->worker->id);

        $certId = $response->json('data.id');

        $this->assertDatabaseHas('certifications', [
            'id' => $certId,
            'worker_id' => $this->worker->id,
            'certificate_type' => 'SIO Forklift',
        ]);

        $this->assertDatabaseHas('attachment_links', [
            'attachment_id' => $attachment->id,
            'entity_type' => Certification::class,
            'entity_id' => $certId,
        ]);
    }

    public function test_can_get_worker_certifications()
    {
        Sanctum::actingAs($this->user);

        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $this->worker->id,
            'certificate_type' => 'Crane Operator',
            'valid_until' => Carbon::now()->addYear(),
        ]);

        $response = $this->getJson("/api/v1/workers/{$this->worker->id}/certifications");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.certificate_type', 'Crane Operator');
    }

    public function test_can_track_expiring_certifications()
    {
        Sanctum::actingAs($this->user);

        $worker2 = Worker::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'name' => 'Jane Smith',
        ]);

        // Expiring in 10 days
        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $this->worker->id,
            'certificate_type' => 'Welder SIO',
            'valid_until' => Carbon::now()->addDays(10),
        ]);

        // Expiring in 60 days (should not be included)
        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $worker2->id,
            'certificate_type' => 'First Aid',
            'valid_until' => Carbon::now()->addDays(60),
        ]);

        $response = $this->getJson('/api/v1/certifications/expiring?days=30');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.certificate_type', 'Welder SIO');
    }

    public function test_can_track_expiring_certifications_across_timezones()
    {
        Sanctum::actingAs($this->user);

        // Expiring precisely around midnight in standard UTC
        Certification::create([
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'worker_id' => $this->worker->id,
            'certificate_type' => 'Timezone Sensitive SIO',
            'valid_until' => Carbon::now('Asia/Jakarta')->addDays(29)->toDateString(),
        ]);

        $response = $this->getJson('/api/v1/certifications/expiring?days=30');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.certificate_type', 'Timezone Sensitive SIO');
    }

    public function test_tenant_isolation_on_certifications()
    {
        Sanctum::actingAs($this->user);

        $otherTenant = Tenant::create(['id' => Str::uuid(), 'name' => 'Other Corp', 'tenant_code' => 'OTHER']);

        $otherWorker = Worker::create([
            'id' => Str::uuid(),
            'tenant_id' => $otherTenant->id,
            'name' => 'Other Worker',
        ]);

        $response = $this->getJson("/api/v1/workers/{$otherWorker->id}/certifications");

        // Ensure that a user from one tenant cannot access worker certifications from another tenant
        $response->assertStatus(404);
    }
}
