<?php

use Illuminate\Support\Facades\Route;

/**
 * Missing Routes - Auto-generated to fix RouteNotFoundException errors
 * These routes are used in views but were not defined in routes/web.php
 */

// API Documentation Routes
Route::get('/api-docs', function () {
    return view('api-docs');
})->name('api-docs');

Route::get('/api/docs', function () {
    return view('api-docs');
})->name('api.docs');

Route::get('/docs/api', function () {
    return view('api-docs');
})->name('docs.api');

// Affiliate Program
Route::get('/affiliate', function () {
    return view('affiliate');
})->name('affiliate');

// Partners Page
Route::get('/partners', function () {
    return view('partners');
})->name('partners');

// Terms Page
Route::get('/terms', function () {
    return view('pages.terms');
})->name('pages.terms');

// Integration Pages
Route::prefix('integrations')->name('integrations.')->group(function () {
    Route::get('/wordpress', function () {
        return view('integrations.wordpress');
    })->name('wordpress');
    
    Route::get('/shopify', function () {
        return view('integrations.shopify');
    })->name('shopify');
    
    Route::get('/slack', function () {
        return view('integrations.slack');
    })->name('slack');
    
    Route::get('/github', function () {
        return view('integrations.github');
    })->name('github');
});

// Official Documents Routes (alternative names)
Route::middleware(['auth'])->prefix('official-documents')->name('official-documents.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('official.documents.index');
    })->name('index');
    
    Route::get('/upload', function () {
        return redirect()->route('official.documents.upload');
    })->name('upload');
    
    Route::get('/my-documents', function () {
        return redirect()->route('official.documents.my-documents');
    })->name('my-documents');
});

// Certificate Verification Check (POST)
Route::post('/verify-certificate', function () {
    $code = request('code');
    return redirect()->route('certificates.verify');
})->name('certificates.verify.check');

// Admin Dashboard (alternative name)
Route::middleware(['auth', 'admin'])->get('/admin/dashboard', function () {
    return redirect('/admin');
})->name('admin.dashboard');

// AI Agent Chat Send (alternative name)
Route::middleware(['auth', 'admin'])->post('/admin/ai-agent-chat/send', function () {
    return response()->json(['error' => 'Not implemented'], 501);
})->name('admin.ai-agent-chat.send');
