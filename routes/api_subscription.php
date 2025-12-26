<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionTranslationController;

// Subscription-based API Translation (Requires Auth + Active Subscription)
Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckSubscriptionLimits::class])
    ->prefix('subscription')
    ->group(function () {
        // Translation endpoint
        Route::post('/translate', [SubscriptionTranslationController::class, 'translate']);
        
        // Subscription management
        Route::get('/status', [SubscriptionTranslationController::class, 'getStatus']);
        Route::get('/usage', [SubscriptionTranslationController::class, 'getUsageStats']);
        Route::post('/credits/add', [SubscriptionTranslationController::class, 'addCredits']);
    });
