<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AiRun;
use App\Models\Incident;
use App\Services\AiStructuringService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcessIncidentDataJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $aiRunId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AiStructuringService $aiStructuringService): void
    {
        $aiRun = AiRun::find($this->aiRunId);

        if (!$aiRun) {
            Log::error("ProcessIncidentDataJob: AiRun not found", ['id' => $this->aiRunId]);
            return;
        }

        if ($aiRun->status !== 'pending' && $aiRun->status !== 'processing') {
            Log::info("ProcessIncidentDataJob: AiRun already processed", ['id' => $this->aiRunId, 'status' => $aiRun->status]);
            return;
        }

        $aiRun->update(['status' => 'processing']);

        $rawPayload = json_encode($aiRun->payload);

        $systemPrompt = <<<EOT
You are an expert EHS (Environment, Health, and Safety) Incident Parser.
Your task is to extract the 5W1H (What, Who, Where, When, Why, How) details from the given unstructured report.
Additionally, you must calculate an `ai_confidence_score` between 0 and 100 representing how confident you are in your extraction.

You MUST return a JSON object with exactly the following structure:
{
    "title": "A short, concise title for the incident",
    "summary": "A brief summary of the incident",
    "what": "What happened?",
    "who": "Who was involved?",
    "where": "Where did it happen?",
    "when": "When did it happen?",
    "why": "Why did it happen? (Initial thought)",
    "how": "How did it happen?",
    "ai_confidence_score": 85
}
EOT;

        try {
            $structuredData = $aiStructuringService->structureData($systemPrompt, $rawPayload);

            $validator = Validator::make($structuredData, [
                'title' => 'nullable|string',
                'summary' => 'nullable|string',
                'what' => 'nullable|string',
                'who' => 'nullable|string',
                'where' => 'nullable|string',
                'when' => 'nullable|string',
                'why' => 'nullable|string',
                'how' => 'nullable|string',
                'ai_confidence_score' => 'nullable|integer|min:0|max:100',
            ]);

            if ($validator->fails()) {
                throw new Exception("Validation failed for LLM JSON output: " . json_encode($validator->errors()->toArray()));
            }

            // Create draft Incident
            Incident::create([
                'tenant_id' => $aiRun->tenant_id,
                'status' => 'draft',
                'title' => $structuredData['title'] ?? null,
                'summary' => $structuredData['summary'] ?? null,
                'metadata' => [
                    'what' => $structuredData['what'] ?? null,
                    'who' => $structuredData['who'] ?? null,
                    'where' => $structuredData['where'] ?? null,
                    'when' => $structuredData['when'] ?? null,
                    'why' => $structuredData['why'] ?? null,
                    'how' => $structuredData['how'] ?? null,
                ],
                'ai_confidence_score' => $structuredData['ai_confidence_score'] ?? null,
                'created_by' => $aiRun->actor_id,
            ]);

            $aiRun->update(['status' => 'completed']);
        } catch (Exception $e) {
            Log::error("ProcessIncidentDataJob: Error structuring data", ['id' => $this->aiRunId, 'error' => $e->getMessage()]);
            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $aiRun = AiRun::find($this->aiRunId);
        if ($aiRun) {
            $aiRun->update(['status' => 'failed']);
            Log::error("ProcessIncidentDataJob: Job completely failed after retries", ['id' => $this->aiRunId, 'error' => $exception->getMessage()]);
        }
    }
}
