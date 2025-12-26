<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify reCAPTCHA token
     *
     * @param string $token
     * @param string $action
     * @param float $minScore
     * @return array
     */
    public function verify(string $token, string $action = 'submit', float $minScore = 0.5): array
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');
            return [
                'success' => false,
                'error' => 'reCAPTCHA not configured',
                'score' => 0
            ];
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $result = $response->json();

            if (!$result) {
                Log::error('reCAPTCHA: Invalid response from Google');
                return [
                    'success' => false,
                    'error' => 'Invalid response from reCAPTCHA service',
                    'score' => 0
                ];
            }

            // Log the verification attempt
            Log::info('reCAPTCHA verification', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? 0,
                'action' => $result['action'] ?? '',
                'expected_action' => $action,
            ]);

            // Check if verification was successful
            if (!($result['success'] ?? false)) {
                return [
                    'success' => false,
                    'error' => 'reCAPTCHA verification failed',
                    'error_codes' => $result['error-codes'] ?? [],
                    'score' => 0
                ];
            }

            // Check if action matches
            if (isset($result['action']) && $result['action'] !== $action) {
                Log::warning('reCAPTCHA: Action mismatch', [
                    'expected' => $action,
                    'received' => $result['action']
                ]);
                return [
                    'success' => false,
                    'error' => 'Action mismatch',
                    'score' => $result['score'] ?? 0
                ];
            }

            // Check score
            $score = $result['score'] ?? 0;
            if ($score < $minScore) {
                Log::warning('reCAPTCHA: Score too low', [
                    'score' => $score,
                    'min_score' => $minScore
                ]);
                return [
                    'success' => false,
                    'error' => 'Score too low (possible bot)',
                    'score' => $score
                ];
            }

            return [
                'success' => true,
                'score' => $score,
                'action' => $result['action'] ?? '',
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Verification failed: ' . $e->getMessage(),
                'score' => 0
            ];
        }
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return !empty(config('services.recaptcha.secret_key')) 
            && !empty(config('services.recaptcha.site_key'));
    }

    /**
     * Get site key for frontend
     *
     * @return string|null
     */
    public function getSiteKey(): ?string
    {
        return config('services.recaptcha.site_key');
    }
}
