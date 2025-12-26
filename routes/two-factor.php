<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;

Route::middleware('auth')->prefix('two-factor')->group(function () {
    Route::get('status', [TwoFactorController::class, 'status'])->name('two-factor.status');
    Route::post('enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::post('recovery-codes', [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery-codes');
});
