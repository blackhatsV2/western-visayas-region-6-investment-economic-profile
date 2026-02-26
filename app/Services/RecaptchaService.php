<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify the reCAPTCHA token.
     *
     * @param string|null $token
     * @return bool
     */
    public function verify(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $secret = config('services.recaptcha.secret_key');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);

        if ($response->failed()) {
            Log::error('reCAPTCHA verification failed to connect to Google API.');
            return false;
        }

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            Log::warning('reCAPTCHA verification failed.', ['errors' => $result['error-codes'] ?? []]);
            return false;
        }

        // For v3, we can also check the score. Defaulting to 0.5 as a reasonable threshold.
        if (($result['score'] ?? 0) < 0.5) {
            Log::warning('reCAPTCHA score too low.', ['score' => $result['score'] ?? 0]);
            return false;
        }

        return true;
    }
}
