<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\Attachment;
use App\Models\CorrectiveAction;
use App\Models\Incident;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CapaFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $incident;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['tenant_code' => 'TEST-001', 'name' => 'Test Tenant']);
        $this->user = AppUser::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->incident = Incident::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Test Incident',
            'status' => 'open'
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_can_create_capa_from_incident()
    {
        $response = $this->postJson('/api/v1/capa', [
            'tenant_id' => $this->tenant->id,
            'title' => 'Fix the leakage',
            'source_type' => Incident::class,
            'source_id' => $this->incident->id,
            'owner_id' => $this->user->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'Fix the leakage')
                 ->assertJsonPath('data.status', 'open');

        $this->assertDatabaseHas('corrective_actions', [
            'title' => 'Fix the leakage',
            'source_type' => Incident::class,
            'source_id' => $this->incident->id,
            'owner_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('audit_events', [
            'entity_type' => CorrectiveAction::class,
            'action_type' => 'capa.create'
        ]);
    }

    public function test_assignee_can_view_my_tasks()
    {
        CorrectiveAction::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'My task',
            'source_type' => Incident::class,
            'source_id' => $this->incident->id,
            'owner_id' => $this->user->id,
        ]);

        $response = $this->getJson('/api/v1/capa/my-tasks');

        $response->assertStatus(200)
                 ->assertJsonPath('data.0.title', 'My task');
    }

    public function test_upload_evidence_and_verify_capa()
    {
        $capa = CorrectiveAction::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'My task',
            'source_type' => Incident::class,
            'source_id' => $this->incident->id,
            'owner_id' => $this->user->id,
            'status' => 'open',
        ]);

        $attachment = Attachment::create([
            'tenant_id' => $this->tenant->id,
            'storage_provider' => 'local',
            'storage_key' => 'path/to/evidence.jpg',
            'media_type' => 'image/jpeg',
            'size_bytes' => 1024,
            'created_by' => $this->user->id,
            'status' => 'uploaded'
        ]);

        $uploadResponse = $this->postJson("/api/v1/capa/{$capa->id}/evidence", [
            'attachment_id' => $attachment->id,
            'notes' => 'Fixed it'
        ]);

        $uploadResponse->assertStatus(200)
                       ->assertJsonPath('data.status', 'pending_verification');

        $this->assertDatabaseHas('attachment_links', [
            'attachment_id' => $attachment->id,
            'entity_type' => CorrectiveAction::class,
            'entity_id' => $capa->id,
            'linkage_type' => 'evidence'
        ]);

        $verifyResponse = $this->patchJson("/api/v1/capa/{$capa->id}/verify", [
            'decision' => 'close'
        ]);

        $verifyResponse->assertStatus(200)
                       ->assertJsonPath('data.status', 'closed');

        $this->assertDatabaseHas('corrective_action_updates', [
            'corrective_action_id' => $capa->id,
            'new_status' => 'closed'
        ]);

        $this->assertDatabaseHas('audit_events', [
            'entity_type' => CorrectiveAction::class,
            'entity_id' => $capa->id,
            'action_type' => 'capa.verify'
        ]);
    }
}
