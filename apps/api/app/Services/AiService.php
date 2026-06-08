<?php

namespace App\Services;

use App\Contracts\LlmGatewayInterface;
use App\Models\KnowledgeDocument;
use Illuminate\Support\Facades\Log;

class AiService
{
    private LlmGatewayInterface $llmGateway;

    public function __construct(LlmGatewayInterface $llmGateway)
    {
        $this->llmGateway = $llmGateway;
    }

    /**
     * Answer a user query using RAG over KnowledgeDocuments.
     *
     * @param string $query
     * @return string
     */
    public function answerWithRag(string $query): string
    {
        try {
            // 1. Get embedding for the user's query
            $embedding = $this->llmGateway->getEmbedding($query);

            // 2. Perform Vector Similarity Search on KnowledgeDocument
            // The global TenantScope applies automatically
            // using pgvector similarity (e.g. Euclidean distance or cosine similarity)
            // L2 Distance (Euclidean) -> order by embedding <-> '[vector]'
            $documents = KnowledgeDocument::query()
                ->select(['id', 'title', 'content'])
                ->orderByRaw('embedding <-> ?', [json_encode($embedding)])
                ->limit(5)
                ->get();

            // 3. Construct Context
            $context = "Relevant Knowledge Base Context:\n";
            if ($documents->isEmpty()) {
                $context .= "No highly relevant documents found. Answer generally based on your pre-trained knowledge but state that you don't have specific company policy for this.\n";
            } else {
                foreach ($documents as $doc) {
                    $context .= "\n--- Document: {$doc->title} ---\n{$doc->content}\n";
                }
            }

            // 4. Construct Prompt
            $systemPrompt = "You are Aegis Copilot, an expert AI EHS (Environment, Health, and Safety) Assistant. "
                          . "Use the provided Knowledge Base Context to answer the user's question accurately. "
                          . "Do NOT invent rules or company policies that are not stated in the context. "
                          . "If the context doesn't have the answer, state that clearly.\n\n"
                          . $context;

            // 5. Query LLM
            $response = $this->llmGateway->askQuestion($systemPrompt, $query);

            return $response;

        } catch (\Exception $e) {
            Log::error('AiService RAG Error: ' . $e->getMessage());
            return "I'm sorry, I encountered an error while trying to process your request.";
        }
    }
}
