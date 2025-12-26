<?php

use App\Http\Controllers\Partner\PartnerDashboardController;
use App\Http\Controllers\Partner\SubscriptionController;
use App\Http\Controllers\Partner\WhiteLabelController;
use Illuminate\Support\Facades\Route;

// Partner Dashboard Routes
Route::prefix('partner')->name('partner.')->middleware(['auth', 'account.type:partner'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api-keys', [PartnerDashboardController::class, 'apiKeys'])->name('api-keys');
    Route::get('/earnings', [PartnerDashboardController::class, 'earnings'])->name('earnings');
    
    // Subscription
    Route::get('/subscription', [PartnerDashboardController::class, 'subscription'])->name('subscription');
    Route::get('/subscription/plans', [SubscriptionController::class, 'index'])->name('subscription.plans');
    Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    
    // White Label
    Route::get('/white-label', [WhiteLabelController::class, 'index'])->name('white-label');
    Route::post('/white-label', [WhiteLabelController::class, 'update'])->name('white-label.update');
    Route::post('/white-label/verify-domain', [WhiteLabelController::class, 'verifyDomain'])->name('white-label.verify-domain');
});
