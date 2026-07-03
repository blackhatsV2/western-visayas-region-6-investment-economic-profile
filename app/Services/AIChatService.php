<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\AIChatException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatService
{
    /**
     * The Gemini API key.
     *
     * @var string|null
     */
    private ?string $geminiKey;

    /**
     * The Pinecone API key.
     *
     * @var string|null
     */
    private ?string $pineconeKey;

    /**
     * The Pinecone Host URL.
     *
     * @var string|null
     */
    private ?string $pineconeHost;

    /**
     * Create a new AIChatService instance.
     */
    public function __construct()
    {
        $this->geminiKey = env('GEMINI_API_KEY');
        $this->pineconeKey = env('PINECONE_API_KEY');
        $this->pineconeHost = env('PINECONE_HOST'); // e.g., "my-index-xxx.svc.pinecone.io"
    }

    /**
     * Generate embedding for a text using Gemini text-embedding-001.
     *
     * @param string $text The text to generate an embedding for.
     * @return array The embedding vector array.
     * @throws \App\Exceptions\AIChatException if the API key is missing or request fails.
     */
    public function getEmbedding(string $text): array
    {
        if (!$this->geminiKey) {
            throw new AIChatException("Gemini API key is not configured.");
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-embedding-001:embedContent?key={$this->geminiKey}";

        $maxRetries = 3;
        $retryDelay = 2; // seconds

        for ($i = 0; $i < $maxRetries; $i++) {
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

            if ($response->status() === 429) {
                Log::warning("Gemini Embedding Rate Limit Hit. Retrying in {$retryDelay}s...");
                if ($i === $maxRetries - 1) {
                    throw new AIChatException("Gemini Embedding API Error: Quota exceeded. Please try again later.");
                }
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
                continue;
            }

            if ($response->status() === 503) {
                Log::warning("Gemini Embedding Service Unavailable (503). Retrying in {$retryDelay}s...");
                if ($i === $maxRetries - 1) {
                    throw new AIChatException("Gemini Embedding API Error: Service is busy or experiencing high demand.");
                }
                sleep($retryDelay);
                $retryDelay *= 2;
                continue;
            }

            Log::error('Gemini Embedding Error: ' . $response->body());
            throw new AIChatException("AI service is temporarily unavailable. Please try again later.");
        }
    }

    /**
     * Upsert vectors to Pinecone.
     *
     * @param array $vectors Array of ['id' => string, 'values' => array, 'metadata' => array].
     * @return bool True if the upsert was successful.
     * @throws \App\Exceptions\AIChatException if configuration is missing or request fails.
     */
    public function upsertToPinecone(array $vectors): bool
    {
        if (!$this->pineconeKey || !$this->pineconeHost) {
            throw new AIChatException("Pinecone credentials are not configured.");
        }

        if (empty($vectors)) {
            return false;
        }

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
            throw new AIChatException("Pinecone Upsert Error: " . $response->status() . " - " . $response->body());
        }

        return true;
    }

    /**
     * Query Pinecone for similar text given an embedding vector.
     *
     * @param array $vector The query embedding vector.
     * @param int $topK The number of matches to retrieve.
     * @return array The matched vectors array.
     * @throws \App\Exceptions\AIChatException if configuration is missing or request fails.
     */
    public function queryPinecone(array $vector, int $topK = 3): array
    {
        if (!$this->pineconeKey || !$this->pineconeHost) {
            throw new AIChatException("Pinecone credentials are not configured.");
        }

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
        throw new AIChatException("AI service is temporarily unavailable. Please try again later.");
    }

    /**
     * Chat with Gemini using context retrieved from Pinecone.
     *
     * @param string $question The user's question.
     * @param string $context The context retrieved from Pinecone.
     * @return string The generated response text.
     * @throws \App\Exceptions\AIChatException if the API key is missing or request fails.
     */
    public function chatWithContext(string $question, string $context): string
    {
        if (!$this->geminiKey) {
            throw new AIChatException("AI services are not configured.");
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->geminiKey}";

        $now = \Carbon\Carbon::now('Asia/Manila');
        $currentDate = $now->format('F j, Y');
        $currentTime = $now->format('g:i A');

        $prompt = "You are an AI assistant for the Western Visayas Region 6 Investment Economic Profile. 
The current date is {$currentDate} and the current time is {$currentTime} (Philippine Standard Time).

Answer the user's question using ONLY the provided context below. Do not use outside knowledge. 

IMPORTANT DISAMBIGUATION: When the user asks about \"ports\", they mean seaports or maritime ports (e.g., Iloilo Commercial Port Complex, Port of Caticlan). Airports are entirely separate facilities. Do NOT confuse ports with airports. Answer only about what was specifically asked.

Exceptions:
1. If the user greets you (e.g. \"hi\", \"hello\", \"hey\"), reply with a warm welcome and politely guide them to ask about the Western Visayas Region 6 Investment Economic Profile.
2. If the user asks for the current time or date, answer it correctly using the time ({$currentTime}) and date ({$currentDate}) provided, but politely remind them to focus on the Region 6 economic and investment profile content.

Citation Rules:
- At the end of your response, always cite the source using markdown links.
- Use the slugified name of the specific card or item where possible (e.g., if info came from the \"152 Ports\" card, the anchor is \"#152-ports\"; if from the \"9 Airports\" card, the anchor is \"#9-airports\").
- If info came from a general section (not a specific named card), use the slugified section title as the anchor (e.g., \"#transportation-infrastructure\").
- Format: \"Reference: [Card or Section Name](#its-slug)\"
- List all citations clearly at the end.

If the answer is not in the context and it is not a greeting or date/time request, politely decline to answer, stating that you can only provide information related to the Region 6 profile content.

Context:
{$context}

Question: {$question}
Answer:";

        $maxRetries = 2;
        $retryDelay = 3;

        for ($i = 0; $i < $maxRetries; $i++) {
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
                    'temperature' => 0.1, 
                ]
            ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates', []);
                if (!empty($candidates)) {
                    return $candidates[0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate an answer.';
                }
            }

            if ($response->status() === 429) {
                Log::warning("Gemini Chat Rate Limit Hit. Retrying in {$retryDelay}s...");
                if ($i === $maxRetries - 1) {
                    throw new AIChatException("Gemini Chat API Error: Quota exceeded. Please try again later.");
                }
                sleep($retryDelay);
                $retryDelay *= 2;
                continue;
            }

            if ($response->status() === 503) {
                Log::warning("Gemini Chat Service Unavailable (503). Retrying in {$retryDelay}s...");
                if ($i === $maxRetries - 1) {
                    throw new AIChatException("Gemini Chat API Error: Service is busy or experiencing high demand.");
                }
                sleep($retryDelay);
                $retryDelay *= 2;
                continue;
            }

            Log::error('Gemini Chat Error: ' . $response->body());
            throw new AIChatException("AI service is temporarily unavailable. Please try again later.");
        }
    }

    /**
     * Retrieve matching context for a given question from Pinecone.
     *
     * @param string $question The question text.
     * @param int $topK The number of context segments to retrieve.
     * @return string The combined context string.
     * @throws \App\Exceptions\AIChatException if the embedding generation or Pinecone query fails.
     */
    public function getContextForQuestion(string $question, int $topK = 5): string
    {
        $questionEmbedding = $this->getEmbedding($question);

        $matches = $this->queryPinecone($questionEmbedding, $topK);

        $context = "";
        foreach ($matches as $match) {
            $context .= ($match['metadata']['text'] ?? '') . "\n\n";
        }

        return trim($context);
    }
}
