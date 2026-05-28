<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\Tenant;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TenantScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_scope_filters_data_by_tenant()
    {
        $tenantA = Tenant::create([
            'tenant_code' => 'TENANT-A',
            'name' => 'Tenant A'
        ]);

        $tenantB = Tenant::create([
            'tenant_code' => 'TENANT-B',
            'name' => 'Tenant B'
        ]);

        $userA = AppUser::factory()->create([
            'tenant_id' => $tenantA->id,
        ]);

        $userB = AppUser::factory()->create([
            'tenant_id' => $tenantB->id,
        ]);

        Site::create(['tenant_id' => $tenantA->id, 'name' => 'Site A1']);
        Site::create(['tenant_id' => $tenantA->id, 'name' => 'Site A2']);
        Site::create(['tenant_id' => $tenantB->id, 'name' => 'Site B1']);

        // Assert user A only sees Site A1, A2
        $this->actingAs($userA);
        $sitesA = Site::all();
        $this->assertCount(2, $sitesA);
        $this->assertEquals('Site A1', $sitesA[0]->name);

        // Assert user B only sees Site B1
        $this->actingAs($userB);
        $sitesB = Site::all();
        $this->assertCount(1, $sitesB);
        $this->assertEquals('Site B1', $sitesB[0]->name);
    }
}
