<?php

namespace Tests\Feature;

use App\Contracts\LlmGatewayInterface;
use App\Models\AppUser;
use App\Models\KnowledgeDocument;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class HseCopilotTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant1;
    private Tenant $tenant2;
    private AppUser $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant1 = Tenant::create(['name' => 'Tenant 1', 'tenant_code' => 'T1']);
        $this->tenant2 = Tenant::create(['name' => 'Tenant 2', 'tenant_code' => 'T2']);

        $this->user = AppUser::factory()->create([
            'tenant_id' => $this->tenant1->id,
        ]);
    }

    public function test_copilot_ask_endpoint_mocks_and_filters_by_tenant()
    {
        // Add doc for tenant 1
        KnowledgeDocument::create([
            'tenant_id' => $this->tenant1->id,
            'title' => 'T1 Doc',
            'content' => 'Tenant 1 confidential info about safety.',
            'embedding' => new \Pgvector\Laravel\Vector(array_fill(0, 1536, 0.1)),
        ]);

        // Add doc for tenant 2
        KnowledgeDocument::create([
            'tenant_id' => $this->tenant2->id,
            'title' => 'T2 Doc',
            'content' => 'Tenant 2 top secret data.',
            'embedding' => new \Pgvector\Laravel\Vector(array_fill(0, 1536, 0.1)),
        ]);

        $this->mock(LlmGatewayInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getEmbedding')
                 ->once()
                 ->with('What is the safety info?')
                 ->andReturn(array_fill(0, 1536, 0.1));

            $mock->shouldReceive('askQuestion')
                 ->once()
                 ->withArgs(function ($systemPrompt, $question) {
                     return str_contains($systemPrompt, 'Tenant 1 confidential info about safety.') &&
                            !str_contains($systemPrompt, 'Tenant 2 top secret data.') &&
                            $question === 'What is the safety info?';
                 })
                 ->andReturn('Based on the context, here is the safety info.');
        });

        $response = $this->actingAs($this->user)->postJson('/api/v1/copilot/ask', [
            'question' => 'What is the safety info?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.answer', 'Based on the context, here is the safety info.');

        $this->assertDatabaseHas('ai_runs', [
            'use_case' => 'copilot',
            'tenant_id' => $this->tenant1->id,
            'actor_id' => $this->user->id,
        ]);
    }
}
