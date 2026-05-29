<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\Incident;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class IncidentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST01', 'name' => 'Test Tenant']);
        $this->user = AppUser::create(['id' => Str::uuid()->toString(), 'tenant_id' => $this->tenant->id, 'email' => 'test@example.com', 'password' => bcrypt('password')]);

        $this->tenant2 = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST02', 'name' => 'Test Tenant 2']);

        // Setup Roles and Permissions
        $permission = Permission::firstOrCreate(['id' => Str::uuid()->toString(), 'name' => 'incident:investigate', 'guard_name' => 'web']);
        $role = Role::firstOrCreate(['id' => Str::uuid()->toString(), 'name' => 'HSE Officer', 'tenant_id' => $this->tenant->id, 'guard_name' => 'web']);
        $role->givePermissionTo($permission);
    }

    public function test_can_list_incidents_with_pagination()
    {
        Sanctum::actingAs($this->user);

        Incident::factory()->count(20)->create(['tenant_id' => $this->tenant->id]);

        $response = $this->getJson('/api/v1/incidents?per_page=15');

        $response->assertStatus(200)
                 ->assertJsonCount(15, 'data')
                 ->assertJsonPath('meta.current_page', 1)
                 ->assertJsonPath('meta.total', 20);
    }

    public function test_can_filter_incidents_by_status()
    {
        Sanctum::actingAs($this->user, [], 'sanctum');

        Incident::factory()->count(5)->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);
        Incident::factory()->count(3)->create(['tenant_id' => $this->tenant->id, 'status' => 'open']);

        $response = $this->getJson('/api/v1/incidents?status=draft');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data')
                 ->assertJsonPath('data.0.status', 'draft');
    }

    public function test_tenant_isolation_on_incident_listing()
    {
        Sanctum::actingAs($this->user, [], 'sanctum');

        // Incident in user's tenant
        Incident::factory()->create(['tenant_id' => $this->tenant->id]);

        // Incident in another tenant
        Incident::factory()->create(['tenant_id' => $this->tenant2->id]);

        $response = $this->getJson('/api/v1/incidents');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_can_get_incident_details()
    {
        Sanctum::actingAs($this->user, [], 'sanctum');

        $incident = Incident::factory()->create(['tenant_id' => $this->tenant->id, 'title' => 'Test Incident']);

        $response = $this->getJson("/api/v1/incidents/{$incident->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Test Incident')
                 ->assertJsonPath('data.id', $incident->id);
    }

    public function test_can_update_incident_status_and_audit_log_created_when_has_permission()
    {
        $role = Role::where('name', 'HSE Officer')->first();
        $this->user->assignRole($role);
        Sanctum::actingAs($this->user, ['*']);

        $incident = Incident::factory()->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);

        $response = $this->putJson("/api/v1/incidents/{$incident->id}", [
            'status' => 'open',
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'open')
                 ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'status' => 'open',
            'title' => 'Updated Title'
        ]);

        $this->assertDatabaseHas('audit_events', [
            'tenant_id' => $this->tenant->id,
            'entity_type' => 'incidents',
            'entity_id' => $incident->id,
            'action_type' => 'status_change_from_draft',
            'actor_id' => $this->user->id
        ]);
    }

    public function test_cannot_update_incident_without_permission()
    {
        // User does not have 'HSE Officer' role or 'incident:investigate' permission
        Sanctum::actingAs($this->user);

        $incident = Incident::factory()->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);

        $response = $this->putJson("/api/v1/incidents/{$incident->id}", [
            'status' => 'open'
        ]);

        $response->assertStatus(403);
    }
}
