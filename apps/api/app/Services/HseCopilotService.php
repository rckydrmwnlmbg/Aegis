<?php

namespace App\Services;

use App\Contracts\LlmGatewayInterface;
use App\Models\KnowledgeDocument;
use Pgvector\Laravel\Vector;
use App\Models\Tenant;

class HseCopilotService
{
    private LlmGatewayInterface $llmGateway;

    public function __construct(LlmGatewayInterface $llmGateway)
    {
        $this->llmGateway = $llmGateway;
    }

    /**
     * Ingest a document, chunk it, and save embeddings to the database.
     */
    public function ingestDocument(Tenant $tenant, string $title, string $content, array $metadata = []): void
    {
        $chunks = $this->chunkContent($content);

        foreach ($chunks as $index => $chunk) {
            $embedding = $this->llmGateway->getEmbedding($chunk);

            KnowledgeDocument::create([
                'tenant_id' => $tenant->id,
                'title' => $title . ' (Chunk ' . ($index + 1) . ')',
                'content' => $chunk,
                'metadata' => array_merge($metadata, ['chunk_index' => $index]),
                'embedding' => new Vector($embedding),
            ]);
        }
    }

    /**
     * Search documents using vector similarity.
     */
    public function searchDocuments(string $query, int $limit = 5)
    {
        $queryEmbedding = $this->llmGateway->getEmbedding($query);

        // Vector similarity search filtered by current tenant (applied via BelongsToTenant scope automatically)
        return KnowledgeDocument::query()
            ->orderByRaw('embedding <-> ?', [new Vector($queryEmbedding)])
            ->limit($limit)
            ->get();
    }

    /**
     * Ask the Copilot a question based on retrieved context.
     */
    public function askQuestion(string $question): string
    {
        $documents = $this->searchDocuments($question);

        $context = $documents->map(function ($doc) {
            return "Title: {$doc->title}\nContent: {$doc->content}";
        })->implode("\n\n");

        $systemPrompt = "You are an HSE Copilot. Answer the user's question using ONLY the provided context. If the answer is not in the context, say you don't know.\n\nContext:\n{$context}";

        return $this->llmGateway->askQuestion($systemPrompt, $question);
    }

    private function chunkContent(string $content, int $chunkSize = 1000): array
    {
        // simplistic chunking by splitting text
        $words = explode(' ', $content);
        $chunks = [];
        $currentChunk = [];

        foreach ($words as $word) {
            $currentChunk[] = $word;
            if (count($currentChunk) >= $chunkSize) {
                $chunks[] = implode(' ', $currentChunk);
                $currentChunk = [];
            }
        }

        if (count($currentChunk) > 0) {
            $chunks[] = implode(' ', $currentChunk);
        }

        return $chunks;
    }
}
