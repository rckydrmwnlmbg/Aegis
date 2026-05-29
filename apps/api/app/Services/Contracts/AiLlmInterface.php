<?php

namespace App\Services\Contracts;

interface AiLlmInterface
{
    /**
     * Send a prompt to the LLM and return the string response.
     *
     * @param string $systemPrompt
     * @param string $userPrompt
     * @return string
     */
    public function generateResponse(string $systemPrompt, string $userPrompt): string;
}
