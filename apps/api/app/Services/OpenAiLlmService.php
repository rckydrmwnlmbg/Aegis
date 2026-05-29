<?php

namespace App\Services;

use App\Services\Contracts\AiLlmInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class OpenAiLlmService implements AiLlmInterface
{
    public function generateResponse(string $systemPrompt, string $userPrompt): string
    {
        $response = Http::withToken(config('services.openai.api_key'))
            ->timeout(120) // Set high timeout for AI
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o', // Or whatever model is specified
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                // Setting response format to JSON object if supported/required, but prompt should also enforce it
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            throw new Exception("LLM Provider Error: " . $response->body());
        }

        $data = $response->json();

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new Exception("Invalid response format from LLM Provider.");
        }

        return $data['choices'][0]['message']['content'];
    }
}
