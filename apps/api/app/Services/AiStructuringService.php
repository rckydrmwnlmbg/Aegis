<?php

namespace App\Services;

use App\Services\Contracts\AiLlmInterface;
use Exception;

class AiStructuringService
{
    public function __construct(private AiLlmInterface $llm)
    {
    }

    /**
     * Process unstructured text/data into a strictly formatted JSON structure.
     *
     * @param string $systemPrompt The prompt defining the expected JSON structure.
     * @param string $rawInput The unstructured data (e.g. transcript, user input).
     * @return array The decoded JSON payload as associative array.
     * @throws Exception If LLM fails to return valid JSON.
     */
    public function structureData(string $systemPrompt, string $rawInput): array
    {
        // Enforce JSON output in the system prompt
        $enforcedSystemPrompt = $systemPrompt . "\n\nCRITICAL INSTRUCTION: You must respond ONLY with valid JSON. Do not include markdown formatting like ```json. Do not include any conversational text.";

        $rawResponse = $this->llm->generateResponse($enforcedSystemPrompt, $rawInput);

        // Attempt to parse JSON
        $decoded = json_decode($rawResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Throw exception so the job can retry
            throw new Exception('AI failed to return valid JSON. Error: ' . json_last_error_msg() . '. Raw Response: ' . $rawResponse);
        }

        return $decoded;
    }
}
