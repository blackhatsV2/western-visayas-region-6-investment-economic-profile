<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_rate_limiting_is_active(): void
    {
        // Global rate limit is 60 per minute
        for ($i = 0; $i < 61; $i++) {
            $response = $this->get('/');
            if ($i == 60) {
                $response->assertStatus(429);
            } else {
                $response->assertStatus(200);
            }
        }
    }
}
