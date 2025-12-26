<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SandboxPreviewController;

// Sandbox Preview - Works without wildcard DNS using query parameter
Route::get('/sandbox/preview/{subdomain}', [SandboxPreviewController::class, 'show'])
    ->name('sandbox.preview.show');

Route::get('/sandbox/preview/{subdomain}/{path}', [SandboxPreviewController::class, 'show'])
    ->where('path', '.*')
    ->name('sandbox.preview.path');
