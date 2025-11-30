<?php
use App\Http\Controllers\AIDeveloperController;
use Illuminate\Support\Facades\Route;

// Health endpoints (بسيطة للمراقبة)
Route::get('/health', [AIDeveloperController::class, 'publicHealth'])->name('health.public');
Route::get('/api/health', [AIDeveloperController::class, 'apiHealth'])->name('health.api');

// صفحة تسجيل الدخول (غير محمية)
Route::get('/ai-developer/login', [AIDeveloperController::class, 'showLogin'])->name('ai-developer.login');
Route::post('/ai-developer/login', [AIDeveloperController::class, 'login'])->name('ai-developer.login.post');
Route::post('/ai-developer/logout', [AIDeveloperController::class, 'logout'])->name('ai-developer.logout');

// لوحة AI Developer (محميّة بـ ai.dev.auth فقط)
Route::middleware(['web', 'ai.dev.auth'])
    ->prefix('ai-developer')
    ->name('ai-developer.')
    ->group(function () {
        Route::get('/', [AIDeveloperController::class, 'index'])->name('index');
        Route::post('/chat', [AIDeveloperController::class, 'chat'])->name('chat');
        Route::post('/changes/{change}/apply', [AIDeveloperController::class, 'applyChange'])->name('changes.apply');
        Route::post('/changes/{change}/cancel', [AIDeveloperController::class, 'cancelChange'])->name('changes.cancel');
        Route::post('/deploy', [AIDeveloperController::class, 'deploy'])->name('deploy');
    });
