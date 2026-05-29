<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\HazardObservation;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class HazardDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST01', 'name' => 'Test Tenant']);
        $this->user = AppUser::create(['id' => Str::uuid()->toString(), 'tenant_id' => $this->tenant->id, 'email' => 'test@example.com', 'password' => bcrypt('password')]);

        $this->tenant2 = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST02', 'name' => 'Test Tenant 2']);

        // Setup Roles and Permissions
        $permission = Permission::firstOrCreate(['id' => Str::uuid()->toString(), 'name' => 'hazard:verify', 'guard_name' => 'web']);
        $role = Role::firstOrCreate(['id' => Str::uuid()->toString(), 'name' => 'HSE Officer', 'tenant_id' => $this->tenant->id, 'guard_name' => 'web']);
        $role->givePermissionTo($permission);
    }

    public function test_can_list_hazards_with_pagination()
    {
        Sanctum::actingAs($this->user);

        HazardObservation::factory()->count(20)->create(['tenant_id' => $this->tenant->id]);

        $response = $this->getJson('/api/v1/hazards?per_page=15');

        $response->assertStatus(200)
                 ->assertJsonCount(15, 'data')
                 ->assertJsonPath('meta.current_page', 1)
                 ->assertJsonPath('meta.total', 20);
    }

    public function test_can_filter_hazards_by_status()
    {
        Sanctum::actingAs($this->user);

        HazardObservation::factory()->count(5)->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);
        HazardObservation::factory()->count(3)->create(['tenant_id' => $this->tenant->id, 'status' => 'open']);

        $response = $this->getJson('/api/v1/hazards?status=draft');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data')
                 ->assertJsonPath('data.0.status', 'draft');
    }

    public function test_tenant_isolation_on_hazard_listing()
    {
        Sanctum::actingAs($this->user);

        // Hazard in user's tenant
        HazardObservation::factory()->create(['tenant_id' => $this->tenant->id]);

        // Hazard in another tenant
        HazardObservation::factory()->create(['tenant_id' => $this->tenant2->id]);

        $response = $this->getJson('/api/v1/hazards');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_can_get_hazard_details()
    {
        Sanctum::actingAs($this->user);

        $hazard = HazardObservation::factory()->create(['tenant_id' => $this->tenant->id, 'title' => 'Test Hazard']);

        $response = $this->getJson("/api/v1/hazards/{$hazard->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Test Hazard')
                 ->assertJsonPath('data.id', $hazard->id);
    }

    public function test_can_update_hazard_status_and_audit_log_created_when_has_permission()
    {
        $role = Role::where('name', 'HSE Officer')->first();
        $this->user->assignRole($role);
        Sanctum::actingAs($this->user, ['*']);

        $hazard = HazardObservation::factory()->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);

        $response = $this->putJson("/api/v1/hazards/{$hazard->id}", [
            'status' => 'open',
            'title' => 'Updated Hazard Title'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'open')
                 ->assertJsonPath('data.title', 'Updated Hazard Title');

        $this->assertDatabaseHas('hazard_observations', [
            'id' => $hazard->id,
            'status' => 'open',
            'title' => 'Updated Hazard Title'
        ]);

        $this->assertDatabaseHas('audit_events', [
            'tenant_id' => $this->tenant->id,
            'entity_type' => 'hazards',
            'entity_id' => $hazard->id,
            'action_type' => 'status_change_from_draft',
            'actor_id' => $this->user->id
        ]);
    }

    public function test_cannot_update_hazard_without_permission()
    {
        // User does not have 'HSE Officer' role or 'hazard:verify' permission
        Sanctum::actingAs($this->user);

        $hazard = HazardObservation::factory()->create(['tenant_id' => $this->tenant->id, 'status' => 'draft']);

        $response = $this->putJson("/api/v1/hazards/{$hazard->id}", [
            'status' => 'open'
        ]);

        $response->assertStatus(403);
    }
}
