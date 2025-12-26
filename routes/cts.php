<?php

use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\CtsVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CTS System Routes
|--------------------------------------------------------------------------
|
| Routes for CTS (CulturalTranslate Standard) system including
| Government Mode and Certificate Verification
|
*/

// Government Mode Routes
Route::prefix('gov')->name('government.')->group(function () {
    Route::get('/', [GovernmentController::class, 'index'])->name('index');
    Route::get('/standard', [GovernmentController::class, 'standard'])->name('standard');
    Route::get('/compliance', [GovernmentController::class, 'compliance'])->name('compliance');
    Route::get('/audit', [GovernmentController::class, 'audit'])->name('audit');
    Route::get('/partners', [GovernmentController::class, 'partners'])->name('partners');
});

// CTS Certificate Verification Routes
Route::prefix('cts-verify')->name('cts-verify.')->group(function () {
    Route::get('/', [CtsVerificationController::class, 'index'])->name('index');
    Route::get('/{certificateId}', [CtsVerificationController::class, 'verify'])->name('show');
    Route::get('/{certificateId}/download', [CtsVerificationController::class, 'download'])->name('download');
});
