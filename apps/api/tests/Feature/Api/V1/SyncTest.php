<?php
namespace Tests\Feature\Api\V1;

use App\Models\AppUser;
use App\Models\Attachment;
use App\Models\AiRun;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Jobs\ProcessIncidentDataJob;
use App\Jobs\ProcessHazardDataJob;

class SyncTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        Queue::fake();
        $this->tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST01', 'name' => 'Test Tenant']);
        $this->user = AppUser::factory()->create(['id' => Str::uuid()->toString(), 'tenant_id' => $this->tenant->id, 'email' => 'test@example.com', 'password' => bcrypt('password')]);
        Sanctum::actingAs($this->user);
    }

    public function test_can_sync_incident_and_dispatch_job() {
        $uuid = Str::uuid()->toString();
        $attachment = Attachment::create(['id' => Str::uuid()->toString(), 'tenant_id' => $this->tenant->id, 'storage_provider' => 'local', 'storage_key' => 'test.jpg', 'media_type' => 'image/jpeg', 'size_bytes' => 100, 'created_by' => $this->user->id]);
        $payload = ['id' => $uuid, 'attachment_ids' => [$attachment->id], 'notes' => 'Worker fell from scaffolding.', 'location' => 'Block C'];

        $response = $this->postJson('/api/v1/sync/incidents', $payload);
        $response->assertStatus(202)->assertJsonPath('meta.status', 202);

        $this->assertDatabaseHas('ai_runs', ['workflow_entity_id' => $uuid, 'use_case' => 'incident_report', 'workflow_domain' => 'incidents']);

        $aiRun = AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->first();
        $this->assertDatabaseHas('attachment_links', ['entity_id' => $aiRun->id, 'attachment_id' => $attachment->id]);

        Queue::assertPushed(ProcessIncidentDataJob::class);
    }

    public function test_incident_sync_idempotency() {
        $uuid = Str::uuid()->toString();
        $payload = ['id' => $uuid, 'notes' => 'Worker fell'];

        $this->postJson('/api/v1/sync/incidents', $payload)->assertStatus(202);
        $this->postJson('/api/v1/sync/incidents', $payload)->assertStatus(202);

        $this->assertDatabaseCount('ai_runs', 1);
        Queue::assertPushed(ProcessIncidentDataJob::class, 1);
    }

    public function test_can_sync_hazard_and_dispatch_job() {
        $uuid = Str::uuid()->toString();
        $payload = ['id' => $uuid, 'notes' => 'Exposed wiring', 'location' => 'Block B'];

        $response = $this->postJson('/api/v1/sync/hazards', $payload);
        $response->assertStatus(202);

        $this->assertDatabaseHas('ai_runs', ['workflow_entity_id' => $uuid, 'use_case' => 'hazard_report']);
        Queue::assertPushed(ProcessHazardDataJob::class);
    }

    public function test_tenant_isolation_prevents_idempotency_leak() {
        $uuid = Str::uuid()->toString();
        $payload = ['id' => $uuid, 'notes' => 'Worker fell'];

        $this->postJson('/api/v1/sync/incidents', $payload)->assertStatus(202);

        $tenant2 = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST02', 'name' => 'Tenant 2']);
        $user2 = AppUser::factory()->create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenant2->id, 'email' => 'user2@example.com', 'password' => bcrypt('password')]);

        auth()->forgetGuards();
        Sanctum::actingAs($user2);

        $this->postJson('/api/v1/sync/incidents', $payload)->assertStatus(202);

        $this->assertDatabaseCount('ai_runs', 2);
        Queue::assertPushed(ProcessIncidentDataJob::class, 2);
    }
}
