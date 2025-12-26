<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RecaptchaService;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    protected $recaptcha;

    public function __construct(RecaptchaService $recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action = 'submit', float $minScore = 0.5): Response
    {
        // Skip verification if reCAPTCHA is not enabled
        if (!$this->recaptcha->isEnabled()) {
            \Log::warning('reCAPTCHA middleware triggered but reCAPTCHA is not configured');
            return $next($request);
        }

        // Get token from request
        $token = $request->input('recaptcha_token');

        if (empty($token)) {
            return $this->failedResponse($request, 'reCAPTCHA token is missing');
        }

        // Verify token
        $result = $this->recaptcha->verify($token, $action, (float)$minScore);

        if (!$result['success']) {
            return $this->failedResponse($request, $result['error'] ?? 'reCAPTCHA verification failed', $result);
        }

        // Add score to request for logging
        $request->merge(['recaptcha_score' => $result['score']]);

        return $next($request);
    }

    /**
     * Return failed response
     */
    protected function failedResponse(Request $request, string $message, array $details = [])
    {
        \Log::warning('reCAPTCHA verification failed', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'message' => $message,
            'details' => $details
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Security verification failed. Please try again.',
                'error' => $message
            ], 422);
        }

        return back()->withErrors([
            'recaptcha' => 'Security verification failed. Please try again.'
        ])->withInput();
    }
}
