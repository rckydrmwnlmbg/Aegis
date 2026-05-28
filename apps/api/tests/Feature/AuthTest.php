<?php

namespace Tests\Feature;

use App\Models\AppUser;
use App\Models\AuditEvent;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_fails_if_credentials_wrong_and_emits_audit()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error' => ['code', 'message', 'details']]);

        $this->assertDatabaseHas('audit_events', [
            'actor_id' => $user->id,
            'action_type' => 'login_failed',
            'tenant_id' => $tenant->id
        ]);
    }

    public function test_login_success_and_emits_audit()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'iphone_12'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['user', 'token'], 'meta']);

        $this->assertDatabaseHas('audit_events', [
            'actor_id' => $user->id,
            'action_type' => 'login',
            'tenant_id' => $tenant->id
        ]);
    }

    public function test_me_endpoint_returns_envelope_with_roles()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'tenant_id',
                'email',
                'status',
                'roles',
                'permissions'
            ],
            'meta'
        ]);
    }
}