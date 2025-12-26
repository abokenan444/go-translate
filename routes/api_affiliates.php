<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AffiliateApiController;

/*
|--------------------------------------------------------------------------
| Affiliate API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('affiliates')->group(function () {
    // Marketer API endpoints (Bearer token authentication via API key)
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/me', [AffiliateApiController::class, 'me']);
        Route::get('/stats', [AffiliateApiController::class, 'stats']);
        Route::get('/links', [AffiliateApiController::class, 'links']);
        Route::post('/links', [AffiliateApiController::class, 'createLink']);
        Route::get('/payouts', [AffiliateApiController::class, 'payouts']);
    });
});
