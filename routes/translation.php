<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

// Translation routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/translate', [TranslationController::class, 'index'])->name('dashboard.translate');
    Route::get('/dashboard/history', [TranslationController::class, 'history'])->name('dashboard.history');
    
    // API routes for translation (session-auth). Use unique names to avoid conflicts with API routes.
    Route::post('/api/translate', [TranslationController::class, 'translate'])->name('dashboard.api.translate');
    Route::post('/api/detect-language', [TranslationController::class, 'detectLanguage'])->name('dashboard.api.detect-language');
});
