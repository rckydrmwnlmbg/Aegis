<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AiRun;
use App\Services\HseCopilotService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CopilotController extends Controller
{
    private HseCopilotService $copilotService;

    public function __construct(HseCopilotService $copilotService)
    {
        $this->copilotService = $copilotService;
    }

    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $question = $validated['question'];

        $startTime = microtime(true);
        $answer = $this->copilotService->askQuestion($question);
        $latencyMs = round((microtime(true) - $startTime) * 1000);

        // Track in ai_runs
        $aiRunId = Str::uuid();
        AiRun::create([
            'id' => $aiRunId,
            'tenant_id' => auth()->user()->tenant_id,
            'actor_id' => auth()->id(),
            'use_case' => 'copilot',
            'workflow_entity_id' => $aiRunId, // Using run ID since copilot is not tied to a specific entity creation like incident
            'payload' => [
                'input_summary' => Str::limit($question, 500),
                'output_summary' => Str::limit($answer, 500),
                'model_used' => 'claude-3-haiku-20240307',
                'latency_ms' => $latencyMs,
            ],
            'status' => 'completed',
        ]);

        return response()->json([
            'data' => [
                'answer' => $answer,
            ],
            'meta' => [
                'correlation_id' => (string) Str::uuid(), // Using UUID directly instead of Log context for now
                'latency_ms' => $latencyMs,
            ]
        ]);
    }
}
