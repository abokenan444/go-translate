<?php

use App\Http\Controllers\GovernmentPortalController;
use App\Http\Middleware\GovernmentPortalMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Government Portal Routes
|--------------------------------------------------------------------------
|
| These routes handle government-specific document translation portals.
| They support both path-based routing (gov.culturaltranslate.com/nl)
| and subdomain-based routing (nl-gov.culturaltranslate.com)
|
*/

Route::middleware(['web', GovernmentPortalMiddleware::class])->group(function () {
    
    // Path-based government portal routes
    // Example: gov.culturaltranslate.com/nl or culturaltranslate.com/gov/nl
    Route::prefix('gov/{country}')->group(function () {
        
        // Portal home page
        Route::get('/', [GovernmentPortalController::class, 'index'])
            ->name('gov.country.portal.index')
            ->where('country', '[a-zA-Z]{2,3}');
        
        // Document submission
        Route::get('/submit', [GovernmentPortalController::class, 'showSubmitForm'])
            ->name('gov.portal.submit');
        
        Route::post('/submit', [GovernmentPortalController::class, 'submitDocument'])
            ->name('gov.portal.submit.post');
        
        // Document tracking
        Route::get('/track/{reference}', [GovernmentPortalController::class, 'trackDocument'])
            ->name('gov.portal.track');
        
        // Document verification
        Route::get('/verify/{certificate}', [GovernmentPortalController::class, 'verifyDocument'])
            ->name('gov.portal.verify');
        
        // Portal information pages
        Route::get('/requirements', [GovernmentPortalController::class, 'requirements'])
            ->name('gov.portal.requirements');
        
        Route::get('/pricing', [GovernmentPortalController::class, 'pricing'])
            ->name('gov.portal.pricing');
        
        Route::get('/contact', [GovernmentPortalController::class, 'contact'])
            ->name('gov.portal.contact');
        
        // API endpoints for the portal
        Route::prefix('api')->group(function () {
            Route::post('/upload', [GovernmentPortalController::class, 'uploadDocument'])
                ->name('gov.portal.api.upload');
            
            Route::get('/status/{reference}', [GovernmentPortalController::class, 'getStatus'])
                ->name('gov.portal.api.status');
            
            Route::get('/estimate', [GovernmentPortalController::class, 'getEstimate'])
                ->name('gov.portal.api.estimate');
        });
    });
    
    // Government portal directory (list all available portals)
    // COMMENTED OUT: Conflicts with /gov route in web.php
    // Route::get('/gov', [GovernmentPortalController::class, 'directory'])
    //     ->name('gov.directory');
    
    // Redirect old format URLs
    Route::get('/government/{country}', function ($country) {
        return redirect()->route('gov.country.portal.index', ['country' => $country]);
    });
});

/*
|--------------------------------------------------------------------------
| Subdomain-based Government Portal Routes
|--------------------------------------------------------------------------
|
| These routes handle subdomain patterns like:
| - nl-gov.culturaltranslate.com
| - gov-nl.culturaltranslate.com
|
| Note: DNS wildcard must be configured for these to work
|
*/

// Only register subdomain routes in production with proper DNS
if (config('ct.enable_gov_subdomains', false)) {
    Route::domain('{country}-gov.' . config('app.domain', 'culturaltranslate.com'))
        ->middleware(['web', GovernmentPortalMiddleware::class])
        ->group(function () {
            Route::get('/', [GovernmentPortalController::class, 'index'])->name('gov.subdomain.index');
            Route::get('/submit', [GovernmentPortalController::class, 'showSubmitForm'])->name('gov.subdomain.submit');
            Route::post('/submit', [GovernmentPortalController::class, 'submitDocument'])->name('gov.subdomain.submit.post');
            Route::get('/track/{reference}', [GovernmentPortalController::class, 'trackDocument'])->name('gov.subdomain.track');
            Route::get('/verify/{certificate}', [GovernmentPortalController::class, 'verifyDocument'])->name('gov.subdomain.verify');
        });
}
