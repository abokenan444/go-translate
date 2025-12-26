<?php
// Update routes/web.php to add internal audit routes

$webRoutesPath = __DIR__ . '/routes/web.php';
$content = file_get_contents($webRoutesPath);

// Check if already added
if (strpos($content, 'internal.audit') !== false) {
    echo "✅ Routes already added\n";
    exit(0);
}

// Add the routes
$routesToAdd = <<<'PHP'

// Internal Audit Routes (Admin Only)
use App\Http\Controllers\Internal\AuditInternalController;

Route::middleware(['web','auth','internal.admin'])
    ->prefix('internal/audit')
    ->group(function () {
        Route::get('/', [AuditInternalController::class, 'index'])->name('internal.audit.index');
        Route::post('/run', [AuditInternalController::class, 'run'])->name('internal.audit.run');
        Route::get('/download', [AuditInternalController::class, 'download'])->name('internal.audit.download');
        Route::get('/view', [AuditInternalController::class, 'viewHtml'])->name('internal.audit.view');
    });
PHP;

file_put_contents($webRoutesPath, $content . "\n" . $routesToAdd);

echo "✅ Routes added successfully\n";
