<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SandboxApiController;

/*
|--------------------------------------------------------------------------
| Sandbox API Routes
|--------------------------------------------------------------------------
|
| API endpoints for Sandbox/Integration environment
| All routes require API key authentication via X-API-Key header
|
*/

Route::prefix('sandbox/v1')->group(function () {
    
    // Public endpoint - no auth required
    Route::get('languages', [SandboxApiController::class, 'languages'])
        ->name('sandbox.api.languages');

    // Protected endpoints - require API key
    Route::middleware(['sandbox.api.auth', 'sandbox.ratelimit'])->group(function () {
        
        // Translation
        Route::post('translate', [SandboxApiController::class, 'translate'])
            ->name('sandbox.api.translate');

        // Webhook testing
        Route::post('webhooks/test', [SandboxApiController::class, 'webhookTest'])
            ->name('sandbox.api.webhook.test');

        Route::get('webhooks/logs', [SandboxApiController::class, 'webhookLogs'])
            ->name('sandbox.api.webhook.logs');

        // Usage & Stats
        Route::get('usage', [SandboxApiController::class, 'usage'])
            ->name('sandbox.api.usage');

        // Cache Statistics
        Route::get('cache/stats', [SandboxApiController::class, 'cacheStats'])
            ->name('sandbox.api.cache.stats');

        // Quality Score Evaluation
        Route::post('quality/score', [SandboxApiController::class, 'qualityScore'])
            ->name('sandbox.api.quality.score');

        // Rate limit testing
        Route::get('rate-limit/test', [SandboxApiController::class, 'rateLimitTest'])
            ->name('sandbox.api.ratelimit.test');
    });
});
