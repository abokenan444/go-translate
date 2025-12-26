<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoiceTranslationController;

// Voice Translation API (Requires Auth + Active Subscription)
// Supports both Sanctum token (API) and web session (dashboard) authentication
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckSubscriptionLimits::class])
    ->prefix('voice')
    ->group(function () {
        // Translate voice to voice
        Route::post('/translate', [VoiceTranslationController::class, 'translateVoice']);
        
        // Get translated audio
        Route::get('/audio/{id}', [VoiceTranslationController::class, 'getAudio'])->name('voice.audio');
        
        // Statistics
        Route::get('/statistics', [VoiceTranslationController::class, 'getStatistics']);
    });
