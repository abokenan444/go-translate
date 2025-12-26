<?php

use App\Http\Controllers\Api\PublicTranslationController;
use Illuminate\Support\Facades\Route;

// Public Translation Routes (with rate limiting)
Route::prefix('public/translation')->group(function () {
    Route::post('/translate', [PublicTranslationController::class, 'translate']);
    Route::get('/usage', [PublicTranslationController::class, 'checkUsage']);
});
