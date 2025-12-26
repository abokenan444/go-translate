<?php

use App\Http\Controllers\OfficialDocumentController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\Api\CertificateApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Official Documents Routes
|--------------------------------------------------------------------------
|
| These routes handle the certified official document translation service.
| Includes upload, payment, verification, and API endpoints.
|
*/

// Public verification routes (no authentication required)
// // Route::get('/verify/{certId}', [CertificateVerificationController::class, 'show'])
//     ->name('verify.certificate');

// API verification endpoint (no authentication required)
Route::prefix('api')->group(function () {
    Route::get('/certificates/{certId}', [CertificateApiController::class, 'show'])
        ->name('api.certificates.show');
});

// Authenticated user routes
Route::middleware(['auth'])->prefix('official-documents')->name('official.documents.')->group(function () {
    
    // Service landing page
    Route::get('/', [OfficialDocumentController::class, 'index'])
        ->name('index');
    
    // Upload document
    Route::get('/upload', [OfficialDocumentController::class, 'create'])
        ->name('upload');
    
    Route::post('/upload', [OfficialDocumentController::class, 'store'])
        ->name('store');
    
    // Checkout and payment
    Route::get('/checkout/{order}', [OfficialDocumentController::class, 'checkout'])
        ->name('checkout');
    
    Route::post('/checkout/{order}', [OfficialDocumentController::class, 'createCheckoutSession'])
        ->name('checkout.create');
    
    Route::get('/payment/success/{order}', [OfficialDocumentController::class, 'paymentSuccess'])
        ->name('payment.success');
    
    // My documents
    Route::get('/my-documents', [OfficialDocumentController::class, 'myDocuments'])
        ->name('my-documents');
    
    // Download certified document
    Route::get('/download/{certId}', [OfficialDocumentController::class, 'download'])
        ->name('download');
});
