<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the environment for the test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache to ensure clean test states
        Cache::clear();
    }

    /**
     * Test validation fails when question is missing.
     */
    public function test_chat_requires_question(): void
    {
        $response = $this->postJson('/api/chat', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['question']);
    }

    /**
     * Test validation fails when question exceeds the maximum length.
     */
    public function test_chat_question_exceeds_max_length(): void
    {
        $longQuestion = str_repeat('a', 1001);
        $response = $this->postJson('/api/chat', ['question' => $longQuestion]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['question']);
    }

    /**
     * Test successful chat flow and subsequent caching.
     */
    public function test_chat_success_and_caching(): void
    {
        $question = 'What is the GRDP growth of Region 6?';

        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models/gemini-embedding-001*' => Http::response([
                'embedding' => ['values' => array_fill(0, 768, 0.1)]
            ], 200),
            'my-index-xxx.svc.pinecone.io/query' => Http::response([
                'matches' => [
                    [
                        'metadata' => [
                            'text' => 'Western Visayas recorded a GRDP growth of 6.8% in 2024.'
                        ]
                    ]
                ]
            ], 200),
            'generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Western Visayas recorded a GRDP growth of 6.8% in 2024.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        // First Request: Uncached
        $response1 = $this->postJson('/api/chat', ['question' => $question]);

        $response1->assertStatus(200)
            ->assertJson([
                'answer' => 'Western Visayas recorded a GRDP growth of 6.8% in 2024.',
                'cached' => false
            ]);

        // Assert response is cached
        $cacheKey = 'ai_chat_response_' . md5(strtolower(trim($question)));
        $this->assertEquals('Western Visayas recorded a GRDP growth of 6.8% in 2024.', Cache::get($cacheKey));

        // Clear HTTP fake recorder to ensure no external requests are made
        Http::fake();

        // Second Request: Cached
        $response2 = $this->postJson('/api/chat', ['question' => $question]);

        $response2->assertStatus(200)
            ->assertJson([
                'answer' => 'Western Visayas recorded a GRDP growth of 6.8% in 2024.',
                'cached' => true
            ]);
    }

    /**
     * Test chat API rate limit (14 requests/minute).
     */
    public function test_chat_rate_limiting(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'embedding' => ['values' => [0.1]],
                'candidates' => [
                    ['content' => ['parts' => [['text' => 'Answer']]]]
                ]
            ]),
            'my-index-xxx.svc.pinecone.io/*' => Http::response([
                'matches' => []
            ])
        ]);

        // Hit limit of 14 requests
        for ($i = 0; $i < 14; $i++) {
            $response = $this->postJson('/api/chat', ['question' => 'Rate limit test ' . $i]);
            $response->assertStatus(200);
        }

        // 15th request should be rate limited
        $response = $this->postJson('/api/chat', ['question' => 'Rate limit test 15']);
        $response->assertStatus(429);
    }

    /**
     * Test failure propagation when the AI service encounters an error.
     */
    public function test_chat_fails_gracefully_on_api_error(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response('API Key Invalid', 400),
        ]);

        $response = $this->postJson('/api/chat', ['question' => 'Will fail']);

        $response->assertStatus(500)
            ->assertJsonStructure(['error']);
    }
}
