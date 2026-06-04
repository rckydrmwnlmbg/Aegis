<?php

namespace App\Contracts;

interface LlmGatewayInterface
{
    /**
     * Generate an embedding vector for the given text.
     *
     * @param string $text
     * @return array<float>
     */
    public function getEmbedding(string $text): array;

    /**
     * Ask a question based on a provided context string using the primary model.
     *
     * @param string $systemPrompt
     * @param string $question
     * @return string
     */
    public function askQuestion(string $systemPrompt, string $question): string;
}
