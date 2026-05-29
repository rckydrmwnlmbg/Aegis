<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AiRun;
use App\Models\HazardObservation;
use App\Services\AiStructuringService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcessHazardDataJob implements ShouldQueue
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
            Log::error("ProcessHazardDataJob: AiRun not found", ['id' => $this->aiRunId]);
            return;
        }

        if ($aiRun->status !== 'pending' && $aiRun->status !== 'processing') {
            Log::info("ProcessHazardDataJob: AiRun already processed", ['id' => $this->aiRunId, 'status' => $aiRun->status]);
            return;
        }

        $aiRun->update(['status' => 'processing']);

        $rawPayload = json_encode($aiRun->payload);

        $systemPrompt = <<<EOT
You are an expert EHS (Environment, Health, and Safety) Hazard Parser.
Your task is to extract hazard information from the given unstructured report.
You need to identify the 'category_name' (e.g., Electrical, Slip/Trip/Fall, Ergonomic) and estimate an initial 'risk_score' (e.g., Low, Medium, High, Critical).
Additionally, calculate an `ai_confidence_score` between 0 and 100 representing how confident you are.

You MUST return a JSON object with exactly the following structure:
{
    "title": "A short, concise title for the hazard",
    "description": "A detailed description of the observed hazard",
    "category_name": "Identified category",
    "risk_score": "Estimated risk score",
    "ai_confidence_score": 90
}
EOT;

        try {
            $structuredData = $aiStructuringService->structureData($systemPrompt, $rawPayload);

            $validator = Validator::make($structuredData, [
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'category_name' => 'nullable|string',
                'risk_score' => 'nullable|string',
                'ai_confidence_score' => 'nullable|integer|min:0|max:100',
            ]);

            if ($validator->fails()) {
                throw new Exception("Validation failed for LLM JSON output: " . json_encode($validator->errors()->toArray()));
            }

            // Create draft Hazard Observation
            HazardObservation::create([
                'tenant_id' => $aiRun->tenant_id,
                'status' => 'draft',
                'title' => $structuredData['title'] ?? null,
                'description' => $structuredData['description'] ?? null,
                'category_name' => $structuredData['category_name'] ?? null,
                'risk_score' => $structuredData['risk_score'] ?? null,
                'ai_confidence_score' => $structuredData['ai_confidence_score'] ?? null,
                'observed_by' => $aiRun->actor_id,
            ]);

            $aiRun->update(['status' => 'completed']);
        } catch (Exception $e) {
            Log::error("ProcessHazardDataJob: Error structuring data", ['id' => $this->aiRunId, 'error' => $e->getMessage()]);
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
            Log::error("ProcessHazardDataJob: Job completely failed after retries", ['id' => $this->aiRunId, 'error' => $exception->getMessage()]);
        }
    }
}
