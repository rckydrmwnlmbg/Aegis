<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CopilotController extends Controller
{
    private AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle RAG Chat requests.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $request->input('message');

        // Logic handled strictly in Service (Architecture Rule)
        $reply = $this->aiService->answerWithRag($message);

        // Canonical JSON Envelope
        return response()->json([
            'data' => [
                'reply' => $reply,
            ],
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ]
        ], 200);
    }
}
