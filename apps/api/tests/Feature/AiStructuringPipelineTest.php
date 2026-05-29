<?php

namespace Tests\Feature;

use App\Jobs\ProcessHazardDataJob;
use App\Jobs\ProcessIncidentDataJob;
use App\Models\AiRun;
use App\Models\AppUser;
use App\Models\HazardObservation;
use App\Models\Incident;
use App\Models\Tenant;
use App\Services\Contracts\AiLlmInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiStructuringPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_incident_data_job_processes_successfully_and_creates_draft_incident()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create(['tenant_id' => $tenant->id]);

        $aiRun = AiRun::create([
            'tenant_id' => $tenant->id,
            'actor_id' => $user->id,
            'use_case' => 'incident_structuring',
            'provider_reference' => 'openai',
            'workflow_domain' => 'incident',
            'payload' => ['text' => 'A worker fell from a ladder near the south entrance.'],
            'status' => 'pending',
        ]);

        // Mock the LLM service
        $mockResponse = json_encode([
            'title' => 'Fall from ladder',
            'summary' => 'A worker fell from a ladder.',
            'what' => 'Fall',
            'who' => 'Worker',
            'where' => 'South entrance',
            'when' => 'Today',
            'why' => 'Unstable ladder',
            'how' => 'Fell while climbing',
            'ai_confidence_score' => 95,
        ]);

        $mockLlm = \Mockery::mock(AiLlmInterface::class);
        $mockLlm->shouldReceive('generateResponse')
                ->once()
                ->andReturn($mockResponse);

        $this->app->instance(AiLlmInterface::class, $mockLlm);

        // Execute job
        $job = new ProcessIncidentDataJob($aiRun->id);
        $job->handle(app(\App\Services\AiStructuringService::class));

        // Assert AiRun updated
        $aiRun->refresh();
        $this->assertEquals('completed', $aiRun->status);

        // Assert Incident created
        $this->assertDatabaseHas('incidents', [
            'tenant_id' => $tenant->id,
            'status' => 'draft',
            'title' => 'Fall from ladder',
            'ai_confidence_score' => 95,
        ]);

        $incident = Incident::first();
        $this->assertEquals('Fall', $incident->metadata['what']);
    }

    public function test_hazard_data_job_processes_successfully_and_creates_draft_hazard()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create(['tenant_id' => $tenant->id]);

        $aiRun = AiRun::create([
            'tenant_id' => $tenant->id,
            'actor_id' => $user->id,
            'use_case' => 'hazard_structuring',
            'provider_reference' => 'openai',
            'workflow_domain' => 'hazard',
            'payload' => ['text' => 'Exposed wiring near the water tank.'],
            'status' => 'pending',
        ]);

        // Mock the LLM service
        $mockResponse = json_encode([
            'title' => 'Exposed wiring',
            'description' => 'Exposed electrical wiring near a water source.',
            'category_name' => 'Electrical',
            'risk_score' => 'High',
            'ai_confidence_score' => 88,
        ]);

        $mockLlm = \Mockery::mock(AiLlmInterface::class);
        $mockLlm->shouldReceive('generateResponse')
                ->once()
                ->andReturn($mockResponse);

        $this->app->instance(AiLlmInterface::class, $mockLlm);

        // Execute job
        $job = new ProcessHazardDataJob($aiRun->id);
        $job->handle(app(\App\Services\AiStructuringService::class));

        // Assert AiRun updated
        $aiRun->refresh();
        $this->assertEquals('completed', $aiRun->status);

        // Assert HazardObservation created
        $this->assertDatabaseHas('hazard_observations', [
            'tenant_id' => $tenant->id,
            'status' => 'draft',
            'title' => 'Exposed wiring',
            'category_name' => 'Electrical',
            'risk_score' => 'High',
            'ai_confidence_score' => 88,
        ]);
    }

    public function test_job_fails_and_throws_exception_if_json_invalid()
    {
        $tenant = Tenant::factory()->create();
        $user = AppUser::factory()->create(['tenant_id' => $tenant->id]);

        $aiRun = AiRun::create([
            'tenant_id' => $tenant->id,
            'actor_id' => $user->id,
            'use_case' => 'incident_structuring',
            'provider_reference' => 'openai',
            'workflow_domain' => 'incident',
            'payload' => ['text' => 'A worker fell...'],
            'status' => 'pending',
        ]);

        // Mock the LLM service returning invalid JSON
        $mockResponse = "This is not JSON.";

        $mockLlm = \Mockery::mock(AiLlmInterface::class);
        $mockLlm->shouldReceive('generateResponse')
                ->once()
                ->andReturn($mockResponse);

        $this->app->instance(AiLlmInterface::class, $mockLlm);

        // Expect Exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/AI failed to return valid JSON/');

        // Execute job
        $job = new ProcessIncidentDataJob($aiRun->id);
        $job->handle(app(\App\Services\AiStructuringService::class));
    }
}
