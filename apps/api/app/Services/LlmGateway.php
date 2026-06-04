<?php

namespace App\Services;

use App\Contracts\LlmGatewayInterface;
use Illuminate\Support\Facades\Http;
use OpenAI;

class LlmGateway implements LlmGatewayInterface
{
    private \OpenAI\Client $openAI;

    public function __construct()
    {
        $apiKey = config('services.openai.api_key') ?: 'dummy_key';

        $this->openAI = class_exists(\OpenAI\Laravel\Facades\OpenAI::class)
            ? \OpenAI\Laravel\Facades\OpenAI::client()
            : OpenAI::client($apiKey);
    }

    public function getEmbedding(string $text): array
    {
        $response = $this->openAI->embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public function askQuestion(string $systemPrompt, string $question): string
    {
        try {
            // Primary Generation: Claude Haiku (via Anthropic API)
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-haiku-20240307',
                'max_tokens' => 1024,
                'system' => $systemPrompt,
                'messages' => [
                    ['role' => 'user', 'content' => $question],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'][0]['text'] ?? 'No response content';
            }
        } catch (\Exception $e) {
            // Fallback to OpenAI
        }

        // Fallback Generation: OpenAI (GPT-4o-mini)
        $response = $this->openAI->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $question],
            ],
        ]);

        return $response->choices[0]->message->content ?? 'No response content';
    }
}
