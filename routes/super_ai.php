<?php

use App\Http\Controllers\SuperAIAgentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SuperAI Agent Routes - نظام الطوارئ
|--------------------------------------------------------------------------
| 
| هذه المسارات توفر وصول طارئ لنظام AI Agent عندما لا يمكن
| الوصول إلى لوحة الإدارة الرئيسية
|
*/

// مسار تسجيل الدخول الطارئ (غير محمي)
Route::get('/emergency-ai-access', [SuperAIAgentController::class, 'showDirectLogin'])
    ->name('super-ai.login');

Route::post('/emergency-ai-access', [SuperAIAgentController::class, 'directLogin'])
    ->name('super-ai.login.post');

// المسارات المحمية
Route::middleware(['web'])->prefix('emergency-ai')->name('super-ai.')->group(function () {
    
    // لوحة التحكم
    Route::get('/dashboard', [SuperAIAgentController::class, 'dashboard'])
        ->name('dashboard');
    
    // معالجة الطلبات
    Route::post('/process', [SuperAIAgentController::class, 'processRequest'])
        ->name('process');
    
    // تحليل السجلات
    Route::post('/analyze-logs', [SuperAIAgentController::class, 'analyzeLogs'])
        ->name('analyze-logs');
    
    // فحص الصحة
    Route::get('/health', [SuperAIAgentController::class, 'healthCheck'])
        ->name('health');
    
    // اقتراح تحسينات
    Route::get('/improvements', [SuperAIAgentController::class, 'suggestImprovements'])
        ->name('improvements');
    
    // تنفيذ أوامر
    Route::post('/execute', [SuperAIAgentController::class, 'executeCommand'])
        ->name('execute');
    
    // تنظيف الكاش
    Route::post('/clear-cache', [SuperAIAgentController::class, 'clearCache'])
        ->name('clear-cache');
    
    // إعادة التشغيل
    Route::post('/restart', [SuperAIAgentController::class, 'restart'])
        ->name('restart');
    
    // تسجيل الخروج
    Route::post('/logout', [SuperAIAgentController::class, 'logout'])
        ->name('logout');
});
