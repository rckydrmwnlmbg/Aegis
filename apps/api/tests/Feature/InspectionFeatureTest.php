<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\Inspection;
use App\Models\InspectionTemplate;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InspectionFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup base permissions
        \App\Models\Permission::create(['name' => 'inspection:create', 'guard_name' => 'web']);
        \App\Models\Permission::create(['name' => 'inspection:execute', 'guard_name' => 'web']);
        \App\Models\Permission::create(['name' => 'inspection:view_scope', 'guard_name' => 'web']);
    }

    public function test_hse_officer_can_create_template()
    {
        $tenant = Tenant::create(['id' => Str::uuid(), 'name' => 'Test Tenant', 'tenant_code' => 'test-tenant']);
        $user = AppUser::factory()->create(['tenant_id' => $tenant->id]);
        $user->givePermissionTo(['inspection:create', 'inspection:view_scope']);

        $payload = [
            'name' => 'Fire Extinguisher Checks',
            'description' => 'Monthly inspection of fire safety equipment.',
            'category' => 'Safety',
            'items' => [
                [
                    'question_text' => 'Is the extinguisher visible?',
                    'response_type' => 'yes_no',
                    'is_required' => true,
                    'order_index' => 1,
                ],
                [
                    'question_text' => 'Condition photo',
                    'response_type' => 'photo',
                    'is_required' => false,
                    'order_index' => 2,
                ],
            ]
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/inspection-templates', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Fire Extinguisher Checks')
                 ->assertJsonCount(2, 'data.items');

        $this->assertDatabaseHas('inspection_templates', [
            'name' => 'Fire Extinguisher Checks',
            'tenant_id' => $tenant->id,
        ]);
        $this->assertDatabaseHas('inspection_template_items', [
            'question_text' => 'Is the extinguisher visible?',
            'response_type' => 'yes_no',
        ]);
    }

    public function test_tenant_isolation_works()
    {
        $tenantA = Tenant::create(['id' => Str::uuid(), 'name' => 'Tenant A', 'tenant_code' => 'tenant-a']);
        $tenantB = Tenant::create(['id' => Str::uuid(), 'name' => 'Tenant B', 'tenant_code' => 'tenant-b']);

        $userA = AppUser::factory()->create(['tenant_id' => $tenantA->id]);
        $userA->givePermissionTo('inspection:view_scope');

        $userB = AppUser::factory()->create(['tenant_id' => $tenantB->id]);
        $userB->givePermissionTo('inspection:view_scope');

        $templateA = InspectionTemplate::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantA->id,
            'name' => 'Template A',
        ]);

        $inspectionA = Inspection::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantA->id,
            'template_id' => $templateA->id,
            'status' => 'draft',
        ]);

        // User B attempts to access User A's inspection
        $response = $this->actingAs($userB)->getJson("/api/v1/inspections/{$inspectionA->id}");

        $response->assertStatus(404); // Or 403 based on how Laravel handles implicitly scoped routes/policies

        // Ensure user B lists only their own inspections
        $listResponse = $this->actingAs($userB)->getJson('/api/v1/inspections');
        $listResponse->assertStatus(200)
                     ->assertJsonCount(0, 'data');
    }

    public function test_inspection_completion_flow()
    {
        $tenant = Tenant::create(['id' => Str::uuid(), 'name' => 'Tenant Flow', 'tenant_code' => 'tenant-flow']);
        $user = AppUser::factory()->create(['tenant_id' => $tenant->id]);
        $user->givePermissionTo(['inspection:create', 'inspection:execute', 'inspection:view_scope']);

        $template = InspectionTemplate::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'name' => 'Execution Flow Template',
        ]);
        $item = $template->items()->create([
            'tenant_id' => $tenant->id,
            'question_text' => 'All good?',
            'response_type' => 'yes_no',
            'order_index' => 1,
        ]);

        // Create
        $inspectionId = Str::uuid()->toString();
        $createResp = $this->actingAs($user)->postJson('/api/v1/inspections', [
            'id' => $inspectionId,
            'template_id' => $template->id,
        ]);
        $createResp->assertStatus(201);
        $inspectionId = $createResp->json('data.id');
        $this->assertDatabaseHas('inspections', ['id' => $inspectionId, 'status' => 'draft']);

        // Start
        $startResp = $this->actingAs($user)->postJson("/api/v1/inspections/{$inspectionId}/start");
        $startResp->assertStatus(200);
        $this->assertDatabaseHas('inspections', ['id' => $inspectionId, 'status' => 'in_progress']);

        // Update Responses
        $updateResp = $this->actingAs($user)->patchJson("/api/v1/inspections/{$inspectionId}", [
            'responses' => [
                [
                    'template_item_id' => $item->id,
                    'response_boolean' => true,
                ]
            ]
        ]);
        $updateResp->assertStatus(200);
        $this->assertDatabaseHas('inspection_responses', [
            'inspection_id' => $inspectionId,
            'template_item_id' => $item->id,
            'response_boolean' => 1,
        ]);

        // Complete
        $completeResp = $this->actingAs($user)->postJson("/api/v1/inspections/{$inspectionId}/complete");
        $completeResp->assertStatus(200);
        $this->assertDatabaseHas('inspections', ['id' => $inspectionId, 'status' => 'completed']);
        $this->assertNotNull(Inspection::find($inspectionId)->completed_at);

        // Cannot complete again
        $reCompleteResp = $this->actingAs($user)->postJson("/api/v1/inspections/{$inspectionId}/complete");
        $reCompleteResp->assertStatus(422); // Handled by exception catch in controller
    }
}
