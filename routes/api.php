<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiTranslationController;
use App\Http\Controllers\API\ImageTranslationController;
use App\Http\Controllers\API\VoiceTranslationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Cultural Translate Platform API v2.0
| Base URL: https://culturaltranslate.com/api/v2
|
*/
// Public Demo Translation (no authentication required)
Route::post('/demo-translate', [ApiTranslationController::class, 'demoTranslate']);

// API v1 routes - ALL using auth:sanctum (no web middleware)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Translation API
    Route::post('/translate', [ApiTranslationController::class, 'translate'])->name('api.translate');
    
    // User Integrations API
    Route::get('/integrations', [App\Http\Controllers\API\UserIntegrationController::class, 'index']);
    Route::post('/integrations/{platform}/disconnect', [App\Http\Controllers\API\UserIntegrationController::class, 'disconnect']);
    
    // Visual Translation API (Image)
    Route::post('/visual/image', [ImageTranslationController::class, 'translateImage']);
    
    // Voice Translation API
    Route::post('/visual/voice', [VoiceTranslationController::class, 'translateVoice']);
});

Route::prefix('v2')->group(function () {
    
    // Health check
    Route::get('/health', [ApiTranslationController::class, 'health']);
    
    // Get supported languages
    Route::get('/languages', [ApiTranslationController::class, 'languages']);
    
    // Get available tones
    Route::get('/tones', [ApiTranslationController::class, 'tones']);
    
    // Protected endpoints (require API key)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Translate text
        Route::post('/translate', [ApiTranslationController::class, 'translate']);
        
        // Detect language
        Route::post('/detect', [ApiTranslationController::class, 'detectLanguage']);
        
        // Get usage statistics
        Route::get('/stats', [ApiTranslationController::class, 'stats']);
    });
});

// Feedback API
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/feedback', [App\Http\Controllers\API\FeedbackController::class, 'submitFeedback']);
    Route::get('/feedback/{translationId}', [App\Http\Controllers\API\FeedbackController::class, 'getFeedback']);
    Route::get('/versions/{translationId}', [App\Http\Controllers\API\FeedbackController::class, 'getSuggestedVersions']);
    Route::post('/versions/{versionId}/approve', [App\Http\Controllers\API\FeedbackController::class, 'approveVersion']);
    Route::get('/user/stats', [App\Http\Controllers\API\FeedbackController::class, 'getUserStats']);
});

Route::post('/translate/demo', [App\Http\Controllers\DemoTranslationController::class, 'translate']);

// Dashboard API endpoints are defined in routes/web.php under session-auth

// User Integrations API (requires authentication)
Route::middleware('auth:web')->prefix('integrations')->group(function () {
    Route::get('/', [App\Http\Controllers\API\UserIntegrationController::class, 'index']);
    Route::get('/stats', [App\Http\Controllers\API\UserIntegrationController::class, 'stats']);
    Route::get('/{platform}', [App\Http\Controllers\API\UserIntegrationController::class, 'show']);
});

// Company-scoped API endpoints protected by Company API Key
Route::middleware([\App\Http\Middleware\AuthenticateCompanyApiKey::class])
    ->prefix('company')
    ->group(function () {
        Route::post('{company}/translate', [App\Http\Controllers\API\ApiTranslationController::class, 'translate'])
            ->name('company.translate');
        // Company webhook receiver
        Route::post('{company}/webhooks/{provider}', [App\Http\Controllers\API\CompanyWebhookController::class, 'handle'])
            ->name('company.webhook');
    });

// Training Data API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/training-data/{id}/rate', [App\Http\Controllers\API\TrainingDataController::class, 'rateTranslation']);
    Route::get('/training-data/recent', [App\Http\Controllers\API\TrainingDataController::class, 'getRecentTranslations']);
    Route::get('/training-data/statistics', [App\Http\Controllers\API\TrainingDataController::class, 'getStatistics']);
    Route::get('/training-data/export', [App\Http\Controllers\API\TrainingDataController::class, 'exportTrainingData']);
    Route::post('/training-data/bulk-approve', [App\Http\Controllers\API\TrainingDataController::class, 'bulkApprove']);
});
