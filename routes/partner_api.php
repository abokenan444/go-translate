<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Partner\TranslationApiController;
use App\Http\Controllers\Api\Partner\LawFirmApiController;
use App\Http\Controllers\Api\Partner\AgencyApiController;
use App\Http\Controllers\Api\Partner\UniversityApiController;
use App\Http\Controllers\Api\Partner\CorporateApiController;
use App\Http\Controllers\Api\Partner\PartnerManagementController;

/*
|--------------------------------------------------------------------------
| Partner API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1/partner')->middleware(['partner.api'])->group(function () {
    
    // Common endpoints for all partners
    Route::prefix('account')->group(function () {
        Route::get('/', [PartnerManagementController::class, 'getAccount']);
        Route::get('/subscription', [PartnerManagementController::class, 'getSubscription']);
        Route::get('/usage', [PartnerManagementController::class, 'getUsage']);
        Route::get('/earnings', [PartnerManagementController::class, 'getEarnings']);
        Route::get('/api-keys', [PartnerManagementController::class, 'listApiKeys']);
        Route::post('/api-keys', [PartnerManagementController::class, 'createApiKey']);
        Route::delete('/api-keys/{id}', [PartnerManagementController::class, 'revokeApiKey']);
    });

    // Translation endpoints (all partners)
    Route::prefix('translations')->group(function () {
        Route::post('/', [TranslationApiController::class, 'create']);
        Route::post('/bulk', [TranslationApiController::class, 'bulk']);
        Route::get('/', [TranslationApiController::class, 'index']);
        Route::get('/{id}', [TranslationApiController::class, 'show']);
    });

    // Projects
    Route::apiResource('projects', PartnerManagementController::class . '@projects');

    // Law Firm specific
    Route::prefix('legal')->middleware('partner.type:law_firm')->group(function () {
        Route::post('/translate', [LawFirmApiController::class, 'translate']);
        Route::post('/bulk-translate', [LawFirmApiController::class, 'bulkTranslate']);
        Route::get('/templates', [LawFirmApiController::class, 'getTemplates']);
        Route::post('/clients', [LawFirmApiController::class, 'createClient']);
        Route::get('/clients', [LawFirmApiController::class, 'listClients']);
        Route::get('/cases', [LawFirmApiController::class, 'listCases']);
    });

    // Translation Agency specific
    Route::prefix('agency')->middleware('partner.type:translation_agency')->group(function () {
        Route::post('/orders', [AgencyApiController::class, 'createOrder']);
        Route::get('/orders', [AgencyApiController::class, 'listOrders']);
        Route::get('/orders/{id}', [AgencyApiController::class, 'getOrder']);
        Route::post('/quotes', [AgencyApiController::class, 'createQuote']);
        Route::get('/clients', [AgencyApiController::class, 'listClients']);
        Route::post('/clients', [AgencyApiController::class, 'createClient']);
        Route::post('/invoices', [AgencyApiController::class, 'generateInvoice']);
        Route::get('/analytics', [AgencyApiController::class, 'getAnalytics']);
    });

    // University specific
    Route::prefix('university')->middleware('partner.type:university')->group(function () {
        Route::post('/students', [UniversityApiController::class, 'createStudent']);
        Route::post('/bulk-accounts', [UniversityApiController::class, 'createBulkAccounts']);
        Route::get('/students', [UniversityApiController::class, 'listStudents']);
        Route::get('/departments', [UniversityApiController::class, 'listDepartments']);
        Route::post('/departments', [UniversityApiController::class, 'createDepartment']);
        Route::post('/research', [UniversityApiController::class, 'translateResearch']);
        Route::get('/usage-reports', [UniversityApiController::class, 'getUsageReports']);
    });

    // Corporate specific
    Route::prefix('corporate')->middleware('partner.type:corporate')->group(function () {
        Route::post('/teams', [CorporateApiController::class, 'createTeam']);
        Route::get('/teams', [CorporateApiController::class, 'listTeams']);
        Route::post('/users', [CorporateApiController::class, 'createUser']);
        Route::get('/users', [CorporateApiController::class, 'listUsers']);
        Route::post('/translate', [CorporateApiController::class, 'translate']);
        Route::get('/budgets', [CorporateApiController::class, 'getBudgets']);
        Route::post('/budgets', [CorporateApiController::class, 'setBudget']);
        Route::get('/reports', [CorporateApiController::class, 'getReports']);
        Route::post('/sso', [CorporateApiController::class, 'configureSso']);
    });
});
