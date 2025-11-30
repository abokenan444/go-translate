Route::middleware(['web', 'auth'])
    ->prefix('admin/cultural-prompts')
    ->group(function () {
        Route::view('/', 'admin.cultural_prompts.index')
            ->name('admin.cultural-prompts.index');

        Route::post('/preview', [\App\Http\Controllers\CulturalPromptController::class, 'preview'])
            ->name('admin.cultural-prompts.preview');
    });
