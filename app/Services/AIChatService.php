<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatService
{
    private $geminiKey;
    private $pineconeKey;
    private $pineconeHost;

    public function __construct()
    {
        $this->geminiKey = env('GEMINI_API_KEY');
        $this->pineconeKey = env('PINECONE_API_KEY');
        $this->pineconeHost = env('PINECONE_HOST'); // e.g., "my-index-xxx.svc.pinecone.io"
    }

    /**
     * Generate embedding for a text using Gemini text-embedding-004.
     */
    public function getEmbedding(string $text): array
    {
        if (!$this->geminiKey) return [];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-embedding-001:embedContent?key={$this->geminiKey}";

        $response = Http::post($url, [
            'model' => 'models/gemini-embedding-001',
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ]);

        if ($response->successful()) {
            return $response->json('embedding.values', []);
        }

        Log::error('Gemini Embedding Error: ' . $response->body());
        return [];
    }

    /**
     * Upsert vectors to Pinecone.
     * @param array $vectors Array of ['id' => string, 'values' => array, 'metadata' => array]
     */
    public function upsertToPinecone(array $vectors)
    {
        if (!$this->pineconeKey || !$this->pineconeHost || empty($vectors)) return false;

        $host = preg_replace('#^https?://#', '', rtrim($this->pineconeHost, '/'));
        $url = "https://{$host}/vectors/upsert";

        $response = Http::withHeaders([
            'Api-Key' => $this->pineconeKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vectors' => $vectors,
            'namespace' => 'region6-profile'
        ]);

        if (!$response->successful()) {
            Log::error('Pinecone Upsert Error: ' . $response->body());
            return false;
        }

        return true;
    }

    /**
     * Query Pinecone for similar text given an embedding vector.
     */
    public function queryPinecone(array $vector, int $topK = 3): array
    {
        if (!$this->pineconeKey || !$this->pineconeHost) return [];

        $host = preg_replace('#^https?://#', '', rtrim($this->pineconeHost, '/'));
        $url = "https://{$host}/query";

        $response = Http::withHeaders([
            'Api-Key' => $this->pineconeKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'namespace' => 'region6-profile',
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true
        ]);

        if ($response->successful()) {
            return $response->json('matches', []);
        }

        Log::error('Pinecone Query Error: ' . $response->body());
        return [];
    }

    /**
     * Chat with Gemini using context retrieved from Pinecone.
     */
    public function chatWithContext(string $question, string $context): string
    {
        if (!$this->geminiKey) return "AI services are not configured.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->geminiKey}";

        $prompt = "You are an AI assistant for the Western Visayas Region 6 Investment Economic Profile. 
Answer the user's question using ONLY the provided context below. Do not use outside knowledge. 
If the answer is not in the context, politely decline to answer, stating that you can only provide information related to the Region 6 profile content.

Context:
{$context}

Question: {$question}
Answer:";

        $response = Http::post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1, // Low temp for more factual/strict responses
            ]
        ]);

        if ($response->successful()) {
            $candidates = $response->json('candidates', []);
            if (!empty($candidates)) {
                return $candidates[0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate an answer.';
            }
        }

        Log::error('Gemini Chat Error: ' . $response->body());
        return "Sorry, I encountered an error while trying to answer your question. Please try again later.";
    }
}
