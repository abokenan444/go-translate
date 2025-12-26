<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Api\ApiTranslationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API v1 routes
Route::prefix('v1')->group(function () {
    // API information
    Route::get('/', [ApiController::class, 'v1Info'])->name('api.v1.info');
    
    // Authentication endpoints
    Route::post('/register', [AuthController::class, 'register'])->name('api.v1.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.v1.login');
    
    // Public translation endpoint (with guest rate limiting)
    Route::middleware('throttle:60,1')->post('/translate', [ApiController::class, 'publicTranslate'])->name('api.v1.translate.public');
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.v1.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.v1.me');

        // Users (for mobile contacts / calling)
        Route::get('/users', [UsersController::class, 'index'])->name('api.v1.users.index');
        
        // Translation endpoints (authenticated - higher limits)
        Route::post('/translate/authenticated', [ApiController::class, 'translate'])->name('api.v1.translate');
        Route::post('/translate/batch', [ApiController::class, 'translateBatch'])->name('api.v1.translate.batch');
        Route::get('/translations', [ApiController::class, 'translationHistory'])->name('api.v1.translations');
        
        // Device tokens (for push notifications)
        Route::post('/devices/register', [\App\Http\Controllers\Api\DeviceTokenController::class, 'register'])->name('api.devices.register');
        Route::post('/devices/unregister', [\App\Http\Controllers\Api\DeviceTokenController::class, 'unregister'])->name('api.devices.unregister');
        
        // Partner mobile API endpoints
        Route::prefix('partner')->middleware('permission:partner.access')->group(function () {
            Route::get('/offers', [\App\Http\Controllers\Api\PartnerOffersController::class, 'index'])->name('api.partner.offers');
            Route::post('/offers/{assignment}/accept', [\App\Http\Controllers\Api\PartnerOffersController::class, 'accept'])->name('api.partner.offers.accept');
            Route::post('/offers/{assignment}/decline', [\App\Http\Controllers\Api\PartnerOffersController::class, 'decline'])->name('api.partner.offers.decline');
        });
    });
    
    // Public endpoints
    Route::post('/detect', [ApiController::class, 'detect'])->name('api.v1.detect');
    Route::get('/languages', [ApiController::class, 'languages'])->name('api.v1.languages');
    Route::post('/certificate/validate', [ApiController::class, 'validateCertificate'])->name('api.v1.certificate.validate');
    Route::get('/stats', [ApiController::class, 'stats'])->name('api.v1.stats');
    Route::get('/health', [ApiController::class, 'health'])->name('api.v1.health');
});

// API v2 routes - Advanced translation with cultural adaptation
Route::prefix('v2')->group(function () {
    // Translation endpoints
    Route::middleware('throttle:60,1')
        ->post('/translate', [ApiTranslationController::class, 'translate'])
        ->name('api.v2.translate');
    
    // Languages with native names
    Route::get('/languages', [ApiTranslationController::class, 'languages'])->name('api.v2.languages');
    
    // Tones
    Route::get('/tones', [ApiTranslationController::class, 'tones'])->name('api.v2.tones');
    
    // Batch translation
    Route::middleware('throttle:30,1')
        ->post('/translate/batch', [ApiTranslationController::class, 'batchTranslate'])
        ->name('api.v2.translate.batch');
});

// Government API routes
Route::prefix('government')->middleware('government_api')->group(function () {
    Route::post('/verify-document', [\App\Http\Controllers\Api\GovernmentVerificationController::class, 'verifyDocument'])->name('api.gov.verify');
    Route::get('/document/{documentId}/status', [\App\Http\Controllers\Api\GovernmentVerificationController::class, 'getDocumentStatus'])->name('api.gov.document.status');
    Route::get('/stats', [\App\Http\Controllers\Api\GovernmentVerificationController::class, 'getStats'])->name('api.gov.stats');
});

// Partner Public Registry API
Route::prefix('partners')->group(function () {
    Route::get('/registry', [\App\Http\Controllers\Api\PartnerRegistryController::class, 'index'])->name('api.partners.registry');
    Route::get('/registry/{partner}', [\App\Http\Controllers\Api\PartnerRegistryController::class, 'show'])->name('api.partners.registry.show');
    Route::get('/certified', [\App\Http\Controllers\Api\PartnerRegistryController::class, 'certified'])->name('api.partners.certified');
});

// Legacy route for backward compatibility
Route::get('/culturaltranslate/v1', [ApiController::class, 'v1Info']);

// LiveCall WebRTC + Billing Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('livecall')->group(function () {
        Route::post('/sessions', [\App\Http\Controllers\Api\LiveCallController::class, 'createSession']);
        Route::post('/sessions/{roomId}/join', [\App\Http\Controllers\Api\LiveCallController::class, 'joinSession']);
        Route::post('/signal', [\App\Http\Controllers\Api\LiveCallController::class, 'signal']);
        Route::post('/sessions/{roomId}/bill-tick', [\App\Http\Controllers\Api\LiveCallController::class, 'billTick']);
    });
    
    Route::prefix('billing/minutes')->group(function () {
        Route::get('/packages', [\App\Http\Controllers\Api\MinutesBillingController::class, 'packages']);
        Route::post('/checkout', [\App\Http\Controllers\Api\MinutesBillingController::class, 'createCheckout']);
    });
});

// Stripe Webhook (no auth, no CSRF)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle']);

// Subscription and voice translation APIs
require base_path('routes/api_subscription.php');
Route::prefix('subscription')->group(base_path('routes/api_voice.php'));
