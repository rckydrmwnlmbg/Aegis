<?php

namespace Tests\Feature;

use App\Enums\PermitStatus;
use App\Models\AppUser;
use App\Models\PermitToWork;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use App\Models\Jsa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PermitToWorkFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected AppUser $requester;
    protected AppUser $approver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['name' => 'Test Tenant', 'tenant_code' => 'TEST']);

        $this->requester = AppUser::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'requester@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->approver = AppUser::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'approver@test.com',
            'password' => bcrypt('password'),
        ]);

        // Setup RBAC
        $permission = Permission::create(['name' => 'permit:approve']);
        $role = Role::create(['name' => 'HSE Officer']);
        $role->givePermissionTo($permission);
        $this->approver->assignRole($role);
    }

    public function test_can_create_draft_ptw()
    {
        Sanctum::actingAs($this->requester);

        $response = $this->postJson('/api/v1/ptw', [
            'title' => 'Hot Work in Area A',
            'work_scope' => 'Welding pipes',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Hot Work in Area A')
            ->assertJsonPath('data.status', PermitStatus::DRAFT->value);

        $this->assertDatabaseHas('permit_to_works', [
            'title' => 'Hot Work in Area A',
            'status' => PermitStatus::DRAFT->value,
            'requested_by' => $this->requester->id,
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_can_transition_from_draft_to_pending_approval()
    {
        Sanctum::actingAs($this->requester);

        $permit = PermitToWork::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Confined Space',
            'status' => PermitStatus::DRAFT->value,
            'requested_by' => $this->requester->id,
        ]);

        $response = $this->patchJson("/api/v1/ptw/{$permit->id}/status", [
            'status' => PermitStatus::PENDING_APPROVAL->value,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', PermitStatus::PENDING_APPROVAL->value);

        $this->assertDatabaseHas('permit_to_works', [
            'id' => $permit->id,
            'status' => PermitStatus::PENDING_APPROVAL->value,
        ]);
    }

    public function test_unauthorized_user_cannot_approve_ptw()
    {
        Sanctum::actingAs($this->requester); // Requester does not have `permit:approve`

        $jsa = Jsa::create(['tenant_id' => $this->tenant->id, 'title' => 'Welding JSA', 'status' => 'approved']);

        $permit = PermitToWork::create([
            'tenant_id' => $this->tenant->id,
            'jsa_id' => $jsa->id,
            'title' => 'Confined Space',
            'status' => PermitStatus::PENDING_APPROVAL->value,
            'requested_by' => $this->requester->id,
        ]);

        $response = $this->patchJson("/api/v1/ptw/{$permit->id}/status", [
            'status' => PermitStatus::APPROVED->value,
        ]);

        $response->assertStatus(403);
    }

    public function test_authorized_user_can_approve_ptw()
    {
        Sanctum::actingAs($this->approver); // Approver HAS `permit:approve`

        $jsa = Jsa::create(['tenant_id' => $this->tenant->id, 'title' => 'Welding JSA', 'status' => 'approved']);

        $permit = PermitToWork::create([
            'tenant_id' => $this->tenant->id,
            'jsa_id' => $jsa->id,
            'title' => 'Confined Space',
            'status' => PermitStatus::PENDING_APPROVAL->value,
            'requested_by' => $this->requester->id,
        ]);

        $response = $this->patchJson("/api/v1/ptw/{$permit->id}/status", [
            'status' => PermitStatus::APPROVED->value,
            'decision_notes' => 'All safety measures are verified.',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', PermitStatus::APPROVED->value);

        $this->assertDatabaseHas('permit_to_works', [
            'id' => $permit->id,
            'status' => PermitStatus::APPROVED->value,
        ]);

        // Verify digital signature/log is recorded
        $this->assertDatabaseHas('permit_approvals', [
            'permit_id' => $permit->id,
            'approver_id' => $this->approver->id,
            'role_saat_menyetujui' => 'HSE Officer',
            'decision' => 'approved',
            'decision_notes' => 'All safety measures are verified.',
        ]);
    }

    public function test_cannot_approve_ptw_without_jsa()
    {
        Sanctum::actingAs($this->approver);

        $permit = PermitToWork::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Confined Space',
            'status' => PermitStatus::PENDING_APPROVAL->value,
            'requested_by' => $this->requester->id,
            'jsa_id' => null, // JSA is missing
        ]);

        $response = $this->patchJson("/api/v1/ptw/{$permit->id}/status", [
            'status' => PermitStatus::APPROVED->value,
            'decision_notes' => 'I am trying to bypass JSA rule.',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'INVALID_STATE_TRANSITION')
            ->assertJsonPath('error.message', 'Permit must be linked to a valid JSA.');
    }

    public function test_illegal_state_transition_is_rejected()
    {
        Sanctum::actingAs($this->approver);

        $permit = PermitToWork::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Confined Space',
            'status' => PermitStatus::DRAFT->value, // From DRAFT directly to ACTIVE should fail
            'requested_by' => $this->requester->id,
        ]);

        $response = $this->patchJson("/api/v1/ptw/{$permit->id}/status", [
            'status' => PermitStatus::ACTIVE->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'INVALID_STATE_TRANSITION');
    }
}
