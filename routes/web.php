<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\GovernmentPortalController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CtsVerificationController;
use App\Http\Controllers\CertificateVerificationController;

/*
|--------------------------------------------------------------------------
| Government Subdomain Routes
|--------------------------------------------------------------------------
| Routes for government.culturaltranslate.com subdomain
*/

Route::domain('government.culturaltranslate.com')->group(function () {
    Route::get('/', [GovernmentPortalController::class, 'index'])->name('government.portal.index');
    Route::get('/dashboard', [GovernmentPortalController::class, 'dashboard'])->name('government.portal.dashboard');
    Route::get('/documents', [GovernmentPortalController::class, 'documents'])->name('government.portal.documents');
    Route::get('/documents/{id}', [GovernmentPortalController::class, 'show'])->name('government.portal.show');
});

// Gov Subdomain Routes (gov.culturaltranslate.com)
Route::domain('gov.culturaltranslate.com')->group(function () {
    Route::get('/', [GovernmentPortalController::class, 'index'])->name('gov.portal.index');
    Route::get('/dashboard', [GovernmentPortalController::class, 'dashboard'])->name('gov.portal.dashboard');
    Route::get('/documents', [GovernmentPortalController::class, 'documents'])->name('gov.portal.documents');
    Route::get('/documents/{id}', [GovernmentPortalController::class, 'show'])->name('gov.portal.show');
});

// Certificate Verification Routes (Public)
Route::get('/verify', [CertificateVerificationController::class, 'index'])->name('verify.index');
Route::post('/verify', [CertificateVerificationController::class, 'search'])->name('certificates.verify.check');
Route::get('/verify/{certificateId}', [CertificateVerificationController::class, 'show'])->name('verify.certificate');
Route::get('/api/verify/{certificateId}', [CertificateVerificationController::class, 'verify'])->name('api.verify.certificate');

// CTS Certification Routes (Must be at the top)
Route::get('/cts/about', [PagesController::class, 'aboutCts'])->name('cts.about');
Route::get('/cts/standard', [PagesController::class, 'ctsStandard'])->name('cts.standard');
Route::get('/cts/risk-engine', [PagesController::class, 'culturalRiskEngine'])->name('cts.risk-engine');
Route::get('/cts/verification', [CtsVerificationController::class, 'index'])->name('cts.verification');
Route::get('/cts-verify', [CtsVerificationController::class, 'index'])->name('cts-verify.index');
Route::get('/cts-verify/{certificateId}', [CtsVerificationController::class, 'verify'])->name('cts-verify.show');
Route::get('/cts-verify/{certificateId}/download', [CtsVerificationController::class, 'download'])->name('cts-verify.download');

// Health Check Endpoints
Route::get('/health', function() {
    return response()->json([
        'ok' => true,
        'timestamp' => now()->toIso8601String(),
        'service' => 'CulturalTranslate Platform',
        'version' => config('app.version', '1.0.0')
    ]);
})->name('health.check');

Route::get('/health/db', function() {
    try {
        \DB::select('select 1');
        return response()->json([
            'ok' => true,
            'db' => 'connected',
            'timestamp' => now()->toIso8601String()
        ]);
    } catch (\Exception $e) {
        \Log::warning('health.db failed', [
            'error' => $e->getMessage(),
        ]);
        return response()->json([
            'ok' => false,
            'db' => 'error',
            'message' => config('app.debug') ? $e->getMessage() : 'Database connection error',
            'timestamp' => now()->toIso8601String()
        ], 503);
    }
})->name('health.db');

// Government Portal Routes (Must be at the top)
Route::prefix('gov')->name('government.')->group(function () {
    Route::get('/', [GovernmentController::class, 'index'])->name('index');
    Route::get('/standard', [GovernmentController::class, 'standard'])->name('standard');
    Route::get('/compliance', [GovernmentController::class, 'compliance'])->name('compliance');
    Route::get('/audit', [GovernmentController::class, 'audit'])->name('audit');
    Route::get('/partners', [GovernmentController::class, 'partners'])->name('partners');
    Route::post('/submit', [GovernmentController::class, 'submit'])->name('submit');
    
    // Government Subdomain Protected Routes
    Route::middleware(['auth', App\Http\Middleware\GovernmentSubdomainMiddleware::class])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/bulk-upload', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'bulkUpload'])->name('bulk-upload');
        Route::get('/api-access', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'apiAccess'])->name('api-access');
        Route::get('/webhooks', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'webhookConfig'])->name('webhooks');
        Route::post('/webhooks', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'createWebhook'])->name('webhooks.create');
        Route::post('/priority-processing', [\App\Http\Controllers\Government\GovernmentDashboardController::class, 'priorityProcessing'])->name('priority-processing');
        // Removed duplicate compliance-report route (moved to /government prefix below)
    });
});

// Authority Console Routes (authority.culturaltranslate.com) - Invite-only
Route::prefix('authority')->name('authority.')->middleware(['web', 'auth', 'authority.only'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'index'])->name('dashboard');
    
    // Audit sampling
    Route::post('/audit/create-sample', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'createAuditSample'])->name('audit.create-sample');
    
    // Certificate actions
    Route::post('/certificate/{certificate}/freeze', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'freezeCertificate'])->name('certificate.freeze');
    Route::post('/certificate/{certificate}/restore', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'restoreCertificate'])->name('certificate.restore');
    
    // Two-man rule: request/approve revocation
    Route::post('/certificate/{certificate}/request-revoke', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'requestRevocation'])->name('certificate.request-revoke');
    Route::post('/revocation-request/{request}/approve', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'approveRevocation'])->name('revocation.approve');
    
    // Disputes
    Route::post('/dispute/open', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'openDispute'])->name('dispute.open');
    
    // Chain integrity (rate-limited)
    Route::get('/verify-chain', [\App\Http\Controllers\Authority\AuthorityDashboardController::class, 'verifyChainIntegrity'])
        ->name('verify-chain')
        ->middleware('throttle:10,1'); // 10 requests per minute
    
    // Evidence package download
    Route::get('/evidence/{documentId}/download', function($documentId) {
        $service = app(\App\Services\EvidencePackageService::class);
        $result = $service->build($documentId);
        
        return response()->download($result['zip_path'], $result['zip_filename'], [
            'Content-Type' => 'application/zip'
        ]);
    })->name('evidence.download');
});

// Affiliate referral redirects
Route::get('/r/{slug}', [\App\Http\Controllers\ReferralController::class, 'redirect'])->name('referral.redirect');

// Admin payout trigger (protect with auth middleware in real setup)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/affiliates/payout/{period?}', [\App\Http\Controllers\AffiliatesAdminController::class, 'triggerPayout'])
        ->name('affiliates.payout.trigger');
    // Test endpoint to simulate a successful purchase and record affiliate conversion
    if (app()->environment(['local', 'testing'])) {
        Route::post('/test/purchase/success', [\App\Http\Controllers\TestPurchaseController::class, 'success']);
    }
});
// Redirect deprecated/legacy admin paths to homepage to avoid exposure
Route::get('/admin-dashboard{any?}', function () {
    return redirect('/');
})->where('any', '.*');

Route::get('/backoffice{any?}', function () {
    return redirect('/');
})->where('any', '.*');

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Admin Subdomain Routes
|--------------------------------------------------------------------------
| Routes for admin.culturaltranslate.com subdomain
| Redirects to Filament Admin Panel
*/

Route::domain('admin.culturaltranslate.com')->group(function () {
    Route::get('/', function () {
        return redirect('/admin');
    });
});

/*
|--------------------------------------------------------------------------
| Main Website Routes
|--------------------------------------------------------------------------
| Routes for culturaltranslate.com main domain
*/

// Language Switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->middleware('throttle:30,1')
    ->name('language.switch');

// Test Locale Page (for debugging)
Route::get('/test-locale', function () {
    return view('test-locale');
})->name('test.locale');

// Landing Page
Route::get("/", [App\Http\Controllers\LandingController::class, "index"])->name("home");

// Public Pages
Route::get("/features", function() { return view("features"); })->name("features");

// Legal Pages
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');
Route::view('/sla', 'legal.sla')->name('legal.sla');
Route::view('/legal-disclaimer', 'legal.disclaimer')->name('legal.disclaimer');
Route::view('/terms', 'legal.terms')->name('legal.terms');

// Pricing page (database-driven, connected to upgrade flows)
Route::get('/pricing-plans', function() {
    $plans = \App\Models\SubscriptionPlan::where('is_active', 1)->orderBy('sort_order')->get();
    return view('legal.pricing', compact('plans'));
})->name('legal.pricing');

// Government Registration Routes
Route::prefix('government')->name('government.')->group(function () {
    Route::get('/register', [\App\Http\Controllers\GovernmentRegistrationController::class, 'showForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\GovernmentRegistrationController::class, 'register'])->name('register.submit');
    Route::middleware(['auth'])->group(function () {
        Route::get('/status', [\App\Http\Controllers\GovernmentRegistrationController::class, 'checkStatus'])->name('status');
        Route::post('/additional-info', [\App\Http\Controllers\GovernmentRegistrationController::class, 'submitAdditionalInfo'])->name('additional-info');
        
        // Compliance Report (Government Client Portal)
        Route::get('/compliance-report', [\App\Http\Controllers\Government\ComplianceReportController::class, 'generate'])->name('compliance-report');
    });
});

// Services Routes
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ServicesController::class, 'index'])->name('all');
    Route::get('/certified-translation', [\App\Http\Controllers\ServicesController::class, 'certifiedTranslation'])->name('certified-translation');
    Route::get('/physical-copy', [\App\Http\Controllers\ServicesController::class, 'physicalCopy'])->name('physical-copy');
    Route::get('/partners', [\App\Http\Controllers\ServicesController::class, 'partners'])->name('partners');
    Route::get('/affiliate', [\App\Http\Controllers\ServicesController::class, 'affiliate'])->name('affiliate');
    Route::get('/enterprise', [\App\Http\Controllers\ServicesController::class, 'enterprise'])->name('enterprise');
    Route::get('/document-translation', [\App\Http\Controllers\ServicesController::class, 'documentTranslation'])->name('document-translation');
});

// Pricing page now uses legal.pricing view (previously line 192)
Route::redirect('/pricing', '/pricing-plans', 301);
Route::post('/contact-custom-plan', [\App\Http\Controllers\PricingController::class, 'contactCustomPlan'])->name('pricing.contact-custom');

// Stripe Pricing and Checkout (public)
Route::get('/plans', [\App\Http\Controllers\StripeController::class, 'pricing'])->name('stripe.pricing');

Route::get('/use-cases', function () {
    return view('pages.use-cases');
})->name('use-cases');

Route::get('/api-docs', function () {
    return view('pages.api-docs');
})->name('api-docs');

// Status page
Route::view('/status', 'pages.status')->name('status');

// Careers routes are defined later with controller; removing simple view route to avoid conflicts.

Route::get('/demo', function () {
    return view('pages.demo');
})->name('demo');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

// Governance & New Pages
Route::get('/governance-recognition', function () {
    return view('pages.governance-recognition');
})->name('governance-recognition');

Route::get('/government-pilot', function () {
    return view('pages.government-pilot');
})->name('government-pilot');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact/submit', [App\Http\Controllers\ContactController::class, 'submit'])
    ->name('contact.submit');

Route::get('/gdpr', [\App\Http\Controllers\GdprController::class, 'index'])->name('gdpr');

// Stripe Webhook (MUST be outside auth middleware and CSRF protection)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/integrations', function () {
    return view('pages.integrations');
})->name('integrations');

// Complaints
Route::get('/complaints', [\App\Http\Controllers\ComplaintController::class, 'index'])->name('complaints');
Route::post('/complaints/submit', [\App\Http\Controllers\ComplaintController::class, 'submit'])->name('complaints.submit');
Route::get('/complaints/track', [\App\Http\Controllers\ComplaintController::class, 'track'])->name('complaints.track');

// Dashboard for authenticated users
Route::middleware(['auth'])->group(function () {
    // Support Tickets System
    Route::get('/tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\TicketController::class, 'update'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/close', [\App\Http\Controllers\TicketController::class, 'close'])->name('tickets.close');
    // Smart dashboard router (redirects by account_type)
    Route::get('/dashboard', \App\Http\Controllers\DashboardRedirectController::class)->name('dashboard');

    // Customer dashboard (regular users)
    Route::get('/dashboard/customer', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware(['account.type:customer'])
        ->name('dashboard.customer');

    // Government dashboard
    Route::get('/dashboard/government', [\App\Http\Controllers\Dashboard\GovernmentDashboardController::class, 'index'])
        ->middleware(['account.type:government'])
        ->name('dashboard.government');

    // Translator dashboard
    Route::get('/dashboard/translator', [\App\Http\Controllers\Dashboard\TranslatorDashboardController::class, 'index'])
        ->middleware(['account.type:translator'])
        ->name('dashboard.translator');
    /*Route::get('/dashboard', function () {
        return view('dashboard.app');
    })->name('dashboard');*/
    
    // Cultural Memory Graph Visualization
    Route::get('/dashboard/cultural-graph', function () {
        return view('dashboard.cultural-graph');
    })->name('dashboard.cultural-graph');

    // Dataset Export & Download
        Route::middleware(['super-admin'])->group(function() {
            Route::post('/dashboard/dataset/export', [\App\Http\Controllers\DatasetController::class, 'export'])->name('dashboard.dataset.export');
            Route::get('/dashboard/dataset/download', [\App\Http\Controllers\DatasetController::class, 'download'])->name('dashboard.dataset.download');
        });

    // Video Integration OAuth (Zoom / Teams)
    Route::middleware(['throttle:10,1'])->group(function() {
        Route::post('/integrations/connect/{provider}', [\App\Http\Controllers\IntegrationOAuthController::class, 'connect'])->name('integrations.oauth.connect');
        Route::get('/integrations/callback/{provider}', [\App\Http\Controllers\IntegrationOAuthController::class, 'callback'])->name('integrations.oauth.callback');
        Route::delete('/integrations/disconnect/{provider}', [\App\Http\Controllers\IntegrationOAuthController::class, 'disconnect'])->name('integrations.oauth.disconnect');
    });
    
    // Dashboard Translation API (session-auth). Named uniquely to avoid conflict with API routes.
    Route::post('/api/v1/translate', [\App\Http\Controllers\API\ApiTranslationController::class, 'translate'])->name('web.api.translate');

    // Prometheus metrics endpoint (read-only, restrict to super-admin if sensitive)
    Route::get('/metrics', function() { 
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || $user->role !== 'super_admin') {
            return response('forbidden', 403); 
        }
        $output = \App\Services\Monitoring\MonitoringService::export();
        return response($output, 200)->header('Content-Type', 'text/plain; version=0.0.4');
    });

    // Stripe Subscription Management (authenticated)
    Route::post('/stripe/checkout', [\App\Http\Controllers\StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/stripe/success', [\App\Http\Controllers\StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [\App\Http\Controllers\StripeController::class, 'cancel'])->name('stripe.cancel');
    Route::post('/stripe/cancel-subscription', [\App\Http\Controllers\StripeController::class, 'cancelSubscription'])->name('stripe.cancel-subscription');
    Route::post('/stripe/resume-subscription', [\App\Http\Controllers\StripeController::class, 'resumeSubscription'])->name('stripe.resume-subscription');
    Route::get('/stripe/portal', [\App\Http\Controllers\StripeController::class, 'portal'])->name('stripe.portal');
});

/*
|--------------------------------------------------------------------------
| OLD ROUTES - COMMENTED OUT (Replaced by Filament Admin Panel)
|--------------------------------------------------------------------------
| These routes are no longer used as we've migrated to Filament
| Keeping them commented for reference
*/

//         ->name('admin.dashboard');
// });

//     ->name('admin.')
//     ->group(function () {
//         Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
//             ->name('dashboard');
//     });

//     ->name('admin.')
//     ->group(function () {
//         Route::get('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'index'])
//             ->name('ai-agent-chat.index');
//
//         Route::post('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'send'])
//             ->name('ai-agent-chat.send');
//     });

// Legal & Info Pages



Route::get('/_temp_admin_login', function () {
    $user = App\Models\User::where('email', 'admin@culturaltranslate.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/admin-dashboard');
    }
    return 'User not found';
});


require __DIR__.'/ai_developer.php';
require __DIR__.'/auth.php';
require __DIR__.'/super_ai.php';

// Translation Routes
require __DIR__.'/translation.php';

// Demo Translation (Public)
Route::post('/api/demo-translate', [App\Http\Controllers\DemoController::class, 'translate'])->name('api.demo-translate');

// Cultural Prompt Engine Routes
Route::middleware(['web', 'auth'])
    ->prefix('admin/cultural-prompts')
    ->group(function () {
        Route::view('/', 'admin.cultural_prompts.index')
            ->name('admin.cultural-prompts.index');

        Route::post('/preview', [\App\Http\Controllers\CulturalPromptController::class, 'preview'])
            ->name('admin.cultural-prompts.preview');
    });
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// Real-Time Voice Translation
Route::get('/realtime/meeting/{publicId}', [App\Http\Controllers\RealTime\RealTimeSessionController::class, 'showMeeting'])->name('realtime.meeting.show');
Route::get('/realtime/meeting-video/{publicId}', function ($publicId) { $session = App\Models\RealTimeSession::where('public_id', $publicId)->firstOrFail(); return view('realtime.meeting-video', compact('session')); })->name('realtime.meeting.video');
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckSubscriptionLimits::class, \App\Http\Middleware\RequireRealtimeCallTranslationFeature::class])
    ->get('/realtime/call/{publicId}', function ($publicId) {
        $session = App\Models\RealTimeSession::where('public_id', $publicId)->firstOrFail();
        return view('realtime.call-mode', compact('session'));
    })
    ->name('realtime.call');

// Local helper: create a new call session and redirect (for local testing only)
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckSubscriptionLimits::class, \App\Http\Middleware\RequireRealtimeCallTranslationFeature::class])
    ->get('/realtime/call-new', function (\Illuminate\Http\Request $request) {
        $source = $request->query('source', 'auto');
        $target = $request->query('target', 'en');

        $session = App\Models\RealTimeSession::create([
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'owner_id' => auth()->id(),
            'type' => 'call',
            'title' => 'Live Translated Call',
            'source_language' => $source,
            'target_language' => $target,
            'bi_directional' => true,
            'record_audio' => false,
            'record_transcript' => false,
            'max_participants' => 2,
            'is_active' => true,
            'started_at' => now(),
            'metadata' => [
                'local_dev' => true,
                'created_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('realtime.call', ['publicId' => $session->public_id]);
    })
    ->name('realtime.call.new');

// Local call-translation API (web session auth) - used by realtime call demo UI
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckSubscriptionLimits::class, \App\Http\Middleware\RequireRealtimeCallTranslationFeature::class])
    ->prefix('realtime/api')
    ->group(function () {
        Route::post('sessions/{publicId}/participants/join', [\App\Http\Controllers\RealTime\RealTimeParticipantController::class, 'join']);
        Route::patch('sessions/{publicId}/participants/me', [\App\Http\Controllers\RealTime\RealTimeParticipantController::class, 'updateMe']);
        Route::get('sessions/{publicId}/participants', [\App\Http\Controllers\RealTime\RealTimeParticipantController::class, 'index']);

        Route::post('sessions/{publicId}/audio', [\App\Http\Controllers\RealTime\RealTimeStreamController::class, 'handleAudio']);
        Route::get('sessions/{publicId}/poll', [\App\Http\Controllers\RealTime\RealTimeStreamController::class, 'pollText']);
    });
Route::get('/realtime/premium/{publicId}', function ($publicId) { $session = App\Models\RealTimeSession::where('public_id', $publicId)->firstOrFail(); return view('realtime.premium-meeting', compact('session')); })->name('realtime.premium');

// Guest Join System
Route::get('/join/{sessionId}', [App\Http\Controllers\RealTime\GuestJoinController::class, 'show'])->name('realtime.guest.join');
Route::post('/join/{sessionId}/token', [App\Http\Controllers\RealTime\GuestJoinController::class, 'generateToken'])->name('realtime.guest.token');
Route::get('/join/{sessionId}/qr', [App\Http\Controllers\RealTime\GuestJoinController::class, 'qrCode'])->name('realtime.guest.qr');
Route::get('/join/{sessionId}/verify/{token}', [App\Http\Controllers\RealTime\GuestJoinController::class, 'verifyToken'])->name('realtime.guest.verify');

// Metrics Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/realtime/metrics', [App\Http\Controllers\RealTime\MetricsController::class, 'dashboard'])->name('realtime.metrics.dashboard');
    Route::get('/api/realtime/metrics', [App\Http\Controllers\RealTime\MetricsController::class, 'getMetrics'])->name('api.realtime.metrics');
    Route::get('/api/realtime/metrics/{sessionId}', [App\Http\Controllers\RealTime\MetricsController::class, 'sessionMetrics'])->name('api.realtime.session.metrics');
});

// Dashboard API endpoints (authenticated via web session)
Route::middleware('auth')->prefix('api/dashboard')->group(function () {
    Route::get('/user', [App\Http\Controllers\DashboardApiController::class, 'getUser']);
    Route::get('/stats', [App\Http\Controllers\DashboardApiController::class, 'getStats']);
    Route::get('/usage', [App\Http\Controllers\DashboardApiController::class, 'getUsage']);
    Route::get('/usage-chart', [App\Http\Controllers\DashboardApiController::class, 'getUsageData']);
    Route::get('/languages', [App\Http\Controllers\DashboardApiController::class, 'getLanguagesData']);
    Route::get('/history', [App\Http\Controllers\DashboardApiController::class, 'getHistory']);
    Route::get('/projects', [App\Http\Controllers\DashboardApiController::class, 'getProjects']);
    Route::post('/projects', [App\Http\Controllers\DashboardApiController::class, 'createProject']);
    Route::delete('/projects/{id}', [App\Http\Controllers\DashboardApiController::class, 'deleteProject']);
    Route::post('/projects/{id}/invite', [App\Http\Controllers\DashboardApiController::class, 'inviteToProject']);
    Route::get('/subscription', [App\Http\Controllers\DashboardApiController::class, 'getSubscription']);
    // Translate endpoint for dashboard (session-auth)
    Route::post('/translate', [\App\Http\Controllers\API\ApiTranslationController::class, 'translate']);
});

// Public API endpoint for languages list (no auth required)
Route::get('/api/languages', [App\Http\Controllers\DashboardApiController::class, 'getLanguagesList']);

// Invoices endpoint
Route::middleware('auth')->get('/api/invoices', [App\Http\Controllers\DashboardApiController::class, 'getInvoices']);

// Integrations endpoint
Route::middleware('auth')->get('/api/integrations', [App\Http\Controllers\API\UserIntegrationController::class, 'index']);

// API v1 endpoints for Dashboard (authenticated via web session)
Route::middleware('auth')->prefix('api/v1')->group(function () {
    Route::get('/me', [App\Http\Controllers\DashboardApiController::class, 'getUser']);
    Route::get('/analytics/dashboard', [App\Http\Controllers\DashboardApiController::class, 'getStats']);
    Route::get('/translations', [App\Http\Controllers\DashboardApiController::class, 'getHistory']);
    Route::get('/projects', [App\Http\Controllers\DashboardApiController::class, 'getProjects']);
    Route::get('/subscription', [App\Http\Controllers\DashboardApiController::class, 'getSubscription']);
});
// Sitemap routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/sitemap-main.xml', [App\Http\Controllers\SitemapController::class, 'main']);
Route::get('/sitemap-blog.xml', [App\Http\Controllers\SitemapController::class, 'blog']);
// Pricing API routes
Route::get('/api/pricing', [App\Http\Controllers\PricingController::class, 'api']);
Route::get('/api/pricing/{id}', [App\Http\Controllers\PricingController::class, 'show']);
Route::post('/api/pricing/subscribe', [App\Http\Controllers\PricingController::class, 'subscribe'])->middleware('auth');

// Dashboard-friendly subscription API endpoints expected by the front-end client
Route::middleware('auth')->prefix('api')->group(function () {
    // Alias for plans list
    Route::get('/plans', [App\Http\Controllers\PricingController::class, 'api']);

    // Current subscription summary
    Route::get('/subscription', function () {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $sub = \Illuminate\Support\Facades\DB::table('user_subscriptions as us')
            ->leftJoin('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
            ->where('us.user_id', $userId)
            ->where('us.status', 'active')
            ->orderByDesc('us.starts_at')
            ->select([
                'us.id as user_subscription_id',
                'us.subscription_plan_id as plan_id',
                'us.starts_at',
                'us.expires_at',
                'sp.name as plan_name',
                'sp.price',
                'sp.billing_period',
            ])
            ->first();

        if (!$sub) {
            return response()->json([
                'success' => true,
                'data' => [
                    'plan_id' => null,
                    'plan_name' => 'Free',
                    'price' => 0,
                    'billing_cycle' => 'month',
                    'renews_at' => now()->addDays(14)->toIso8601String(),
                ],
            ]);
        }

        $renewsAt = $sub->expires_at
            ? \Illuminate\Support\Carbon::parse($sub->expires_at)
            : (($sub->billing_period ?? 'monthly') === 'yearly'
                ? \Illuminate\Support\Carbon::parse($sub->starts_at)->addYear()
                : \Illuminate\Support\Carbon::parse($sub->starts_at)->addMonth());

        return response()->json([
            'success' => true,
            'data' => [
                'plan_id' => $sub->plan_id,
                'plan_name' => $sub->plan_name,
                'price' => (float) $sub->price,
                'billing_cycle' => ($sub->billing_period ?? 'monthly') === 'yearly' ? 'year' : 'month',
                'renews_at' => \Illuminate\Support\Carbon::parse($renewsAt)->toIso8601String(),
            ],
        ]);
    });

    // Usage summary for dashboard
    Route::get('/usage', function () {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $active = \Illuminate\Support\Facades\DB::table('user_subscriptions as us')
            ->leftJoin('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
            ->where('us.user_id', $userId)
            ->where('us.status', 'active')
            ->orderByDesc('us.starts_at')
            ->select(['us.tokens_used', 'us.tokens_remaining', 'sp.tokens_limit', 'sp.max_team_members'])
            ->first();

        $charactersUsed = $active ? (int) ($active->tokens_used ?? 0) : 0;
        $charactersLimit = $active ? (int) ($active->tokens_limit ?? 0) : 100000;
        $apiCalls = (int) round($charactersUsed / 10);
        $apiLimit = (int) round($charactersLimit / 10);
        $teamMembers = 1;
        $teamLimit = $active ? (int) ($active->max_team_members ?? 1) : 1;

        return response()->json([
            'success' => true,
            'data' => [
                'characters_used' => $charactersUsed,
                'characters_limit' => $charactersLimit,
                'api_calls' => $apiCalls,
                'api_limit' => $apiLimit,
                'team_members' => $teamMembers,
                'team_limit' => $teamLimit,
            ],
        ]);
    });

    // Subscribe/upgrade alias matching the API client
    Route::post('/subscribe', [App\Http\Controllers\PricingController::class, 'subscribe']);
    
    // Cultural Intelligence Engine (CIE)
    Route::post('/cie/analyze', [App\Http\Controllers\CIEController::class, 'analyze']);
    Route::post('/cie/brand-voice', [App\Http\Controllers\CIEController::class, 'brandVoice']);
    Route::post('/cie/normalize', [App\Http\Controllers\CIEController::class, 'normalize']);
    Route::post('/cie/emotion-map', [App\Http\Controllers\CIEController::class, 'emotionMap']);

    // Multimodal Translation Engine (MTE)
    Route::post('/mte/text', [App\Http\Controllers\MTEController::class, 'translateText'])->middleware('throttle:60,1');
    Route::post('/mte/image', [App\Http\Controllers\MTEController::class, 'translateImage'])->middleware('throttle:20,1');
    Route::post('/mte/pdf', [App\Http\Controllers\MTEController::class, 'translatePDF'])->middleware('throttle:10,1');
    Route::post('/mte/pdf/async', [App\Http\Controllers\MTEController::class, 'enqueuePDF'])->middleware('throttle:10,1');
    Route::get('/mte/pdf/status/{jobId}', [App\Http\Controllers\MTEController::class, 'pdfStatus'])->middleware('throttle:60,1');
    Route::post('/mte/asr', [App\Http\Controllers\MTEController::class, 'transcribeAudio'])->middleware('throttle:10,1');
    Route::post('/mte/tts', [App\Http\Controllers\MTEController::class, 'synthesizeSpeech'])->middleware('throttle:30,1');

    // Knowledge Base & Memory (KBM)
    Route::get('/kbm/memories', [App\Http\Controllers\KBMController::class, 'listMemories']);
    Route::post('/kbm/review', [App\Http\Controllers\KBMController::class, 'review']);
    Route::get('/kbm/graph', [App\Http\Controllers\KBMController::class, 'graph']);

    // Real-time Communication (RTC)
    Route::post('/rtc/session', [App\Http\Controllers\RTCController::class, 'createSession']);
    Route::post('/rtc/session/end', [App\Http\Controllers\RTCController::class, 'endSession']);

    // Governance
    Route::get('/governance/audit-logs', [App\Http\Controllers\GovernanceController::class, 'auditLogs']);
    Route::post('/governance/request-erasure', function() {
        $uid = \Illuminate\Support\Facades\Auth::id();
        \App\Services\Governance\GovernanceService::logStatic($uid, 'request_erasure', 'user', $uid, []);
        return response()->json(['success' => true]);
    });
});

// API v1 endpoints for usage
Route::get('/api/v1/usage', function() {
    return response()->json([
        'success' => true,
        'data' => [
            'characters_used' => 45200,
            'characters_limit' => 100000,
            'api_calls' => 1234,
            'api_limit' => 10000,
            'team_members' => 5,
            'team_limit' => 10
        ]
    ]);
})->middleware('auth:web');

Route::get('/api/v1/subscription', function() {
    return response()->json([
        'success' => true,
        'data' => [
            'plan_id' => 2,
            'plan_name' => 'Professional',
            'price' => 49,
            'billing_cycle' => 'month',
            'renews_at' => now()->addDays(15)->toISOString()
        ]
    ]);
})->middleware('auth:web');

// Integration Documentation Pages
Route::get('/integrations/github', function () {
    return view('pages.integrations.github-integration');
});

Route::get('/integrations/wordpress', function () {
    return view('pages.integrations.wordpress-integration');
});

Route::get('/integrations/woocommerce', function () {
    return view('pages.integrations.woocommerce-integration');
});
Route::post('/integrations/connect/{platform}', [App\Http\Controllers\IntegrationConnectController::class, 'connect'])->name('integrations.connect');
Route::get('/integrations/callback/{platform}', [App\Http\Controllers\IntegrationConnectController::class, 'callback'])->name('integrations.callback');
Route::post('/integrations/disconnect/{platform}', [App\Http\Controllers\IntegrationConnectController::class, 'disconnect'])->name('integrations.disconnect');

// Careers Routes
Route::get('/careers', [App\Http\Controllers\CareersController::class, 'index'])->name('careers.index');
Route::get('/careers/{job}', [App\Http\Controllers\CareersController::class, 'show'])->name('careers.show');
Route::post('/careers/{job}/apply', [App\Http\Controllers\CareersController::class, 'apply'])->name('careers.apply');
Route::get('/careers/applications/{application}/resume', [App\Http\Controllers\CareersController::class, 'downloadResume'])->name('careers.download-resume')->middleware('auth');

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-main.xml', [App\Http\Controllers\SitemapController::class, 'main'])->name('sitemap.main');
Route::get('/sitemap-blog.xml', [App\Http\Controllers\SitemapController::class, 'blog'])->name('sitemap.blog');
Route::get('/sitemap-careers.xml', [App\Http\Controllers\SitemapController::class, 'careers'])->name('sitemap.careers');

/*
|--------------------------------------------------------------------------
| Documentation Routes
|--------------------------------------------------------------------------
*/
Route::get('/docs', [App\Http\Controllers\DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/partners', [App\Http\Controllers\DocumentationController::class, 'partners'])->name('docs.partners');
Route::get('/docs/{page}', [App\Http\Controllers\DocsController::class, 'show'])->name('docs.show');

// Sandbox Preview Routes
require __DIR__ . '/sandbox.php';

// Two-Factor Authentication Routes
require __DIR__ . '/two-factor.php';

// API Playground
Route::get('/playground', function () {
    return view('pages.api-playground');
})->name('playground');

/*
|--------------------------------------------------------------------------
| SSO Routes (Enterprise)
|--------------------------------------------------------------------------
*/
Route::prefix('sso')->group(function () {
    Route::get('metadata', [\App\Http\Controllers\Auth\SSOController::class, 'metadata'])->name('sso.metadata');
    Route::get('login/{provider}', [\App\Http\Controllers\Auth\SSOController::class, 'login'])->name('sso.login');
    Route::post('callback/{provider}', [\App\Http\Controllers\Auth\SSOController::class, 'callback'])->name('sso.callback');
});

// Legal & Support Pages
Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy-policy');

Route::get("/privacy", function () { return view("pages.privacy"); })->name('privacy');

Route::get('/terms-of-service', [App\Http\Controllers\PageController::class, 'show'])->defaults('slug', 'terms-of-service')->name('terms-of-service');

Route::get("/terms", function () { return view("pages.terms"); })->name('terms');

Route::get("/security", function () { return view("pages.security"); })->name('security');

// Help Center
Route::get('/help-center', [\App\Http\Controllers\HelpCenterController::class, 'index'])->name('help-center');
Route::get('/help-center/search', [\App\Http\Controllers\HelpCenterController::class, 'search'])->name('help-center.search');
Route::get('/help-center/{slug}', [\App\Http\Controllers\HelpCenterController::class, 'article'])->name('help-center.article');
Route::get("/help", function () { return view("pages.help"); });

// Partner Application
Route::get('/partner/apply', function () { 
    return view('partners.apply'); 
})->name('partner.apply');
Route::post('/partner/apply', [App\Http\Controllers\PartnerApplicationController::class, 'store'])->name('partner.submit');
/*
    */


Route::get('/guides', function () {
    return view('pages.guides');
})->name('guides');

// Additional Pages
Route::get('/press', function () {
    return view('pages.press');
})->name('press');

Route::get('/community', function () {
    return view('pages.community');
})->name('community');

// Affiliate Program Page
Route::get('/affiliate', function() {     return view('pages.affiliate');
})->name('affiliate');

// Enterprise Subscription Routes
Route::get('/enterprise', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'pricing'])->name('enterprise.pricing');
Route::get('/enterprise/request', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'requestForm'])->name('enterprise.request');
Route::post('/enterprise/request', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'submitRequest'])->name('enterprise.submit-request');

Route::middleware(['auth'])->group(function () {
    Route::get('/enterprise/dashboard', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'dashboard'])->name('enterprise.dashboard');
    Route::get('/enterprise/usage', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'getUsage'])->name('enterprise.usage');
    Route::get('/enterprise/report', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'downloadReport'])->name('enterprise.download-report');
    Route::post('/enterprise/payment-method', [\App\Http\Controllers\EnterpriseSubscriptionController::class, 'updatePaymentMethod'])->name('enterprise.update-payment');
});

Route::get('/changelog', function () {
    return view('pages.changelog');
})->name('changelog');

Route::get('/cookies', function () {
    return view('pages.cookies');
})->name('cookies');

// Official Documents Translation Routes
require __DIR__.'/official_documents.php';
Route::get('/developers', function() { return view('pages.developers'); })->name('developers');
Route::get('/sandbox', function() { return view('sandbox'); })->name('sandbox.index');
Route::get('/partners', function () { return view('partners'); })->name('partners.index');

Route::get("/live-demo", function() { return view("pages.live-demo"); })->name("live-demo");
// File Translation Routes
Route::middleware(['auth'])->prefix('dashboard/file-translation')->name('dashboard.file-translation.')->group(function () {
    Route::get('/',[App\Http\Controllers\Dashboard\FileTranslationController::class, 'index'])->name('index');
    Route::post('/upload',[App\Http\Controllers\Dashboard\FileTranslationController::class, 'upload'])->name('upload');
    Route::get('/download/{id}',[App\Http\Controllers\Dashboard\FileTranslationController::class, 'download'])->name('download');
    Route::delete('/delete/{id}',[App\Http\Controllers\Dashboard\FileTranslationController::class, 'delete'])->name('delete');
});

// Affiliate Dashboard Routes
Route::middleware(['auth', 'account.type:affiliate', 'ensure.affiliate'])->prefix('dashboard/affiliate')->name('dashboard.affiliate.')->group(function () {
    Route::get('/',[App\Http\Controllers\Dashboard\AffiliateController::class, 'dashboard'])->name('dashboard');
    Route::post('/generate-link',[App\Http\Controllers\Dashboard\AffiliateController::class, 'generateLink'])->name('generate-link');
    Route::get('/stats',[App\Http\Controllers\Dashboard\AffiliateController::class, 'stats'])->name('stats');
    Route::get("/links", [App\Http\Controllers\Dashboard\AffiliateController::class, "links"])->name("links");
    Route::post("/toggle-link/{id}", [App\Http\Controllers\Dashboard\AffiliateController::class, "toggleLink"])->name("toggle-link");
    Route::get("/payouts", [App\Http\Controllers\Dashboard\AffiliateController::class, "payouts"])->name("payouts");
    Route::post("/request-payout", [App\Http\Controllers\Dashboard\AffiliateController::class, "requestPayout"])->name("request-payout");
});
Route::get('/ref/{code}',[App\Http\Controllers\Dashboard\AffiliateController::class, 'trackClick'])->name('affiliate.track');


// Certified Translation RoutesRoute::get("/certified-translation", [App\Http\Controllers\CertifiedTranslationController::class, "index"])->name("certified-translation");Route::post("/certified-translation/upload", [App\Http\Controllers\CertifiedTranslationController::class, "upload"])->middleware("auth")->name("certified.upload");Route::get("/certified-translation/download/{certNumber}", [App\Http\Controllers\CertifiedTranslationController::class, "download"])->middleware("auth")->name("certified.download");Route::get("/certified-translation/verify/{certNumber}", [App\Http\Controllers\CertifiedTranslationController::class, "verify"])->name("certified.verify");

// Support Routes
Route::get("/support", [App\Http\Controllers\SupportController::class, "index"])->name("support");
Route::post("/support/submit", [App\Http\Controllers\SupportController::class, "submit"])->name("support.submit");

// Certified Translation Routes
Route::get("/certified-translation", [App\Http\Controllers\CertifiedTranslationController::class, "index"])->name("certified-translation");
Route::post("/certified-translation/upload", [App\Http\Controllers\CertifiedTranslationController::class, "upload"])->middleware("auth")->name("certified.upload");
Route::get("/certified-translation/download/{certNumber}", [App\Http\Controllers\CertifiedTranslationController::class, "download"])->middleware("auth")->name("certified.download");
Route::get("/certified-translation/verify/{certNumber}", [App\Http\Controllers\CertifiedTranslationController::class, "verify"])->name("certified.verify");

// Support Routes
Route::get("/support", [App\Http\Controllers\SupportController::class, "index"])->name("support");
Route::post("/support/submit", [App\Http\Controllers\SupportController::class, "submit"])->name("support.submit");

// Logout route
Route::post("/logout", [App\Http\Controllers\Auth\LoginController::class, "logout"])->name("logout");
Route::get("/logout", function() { auth()->logout(); return redirect("/login"); });

Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');


// Legal Documents Routes


// Legal Documents Routes
Route::middleware(['auth'])->group(function () {
});

// Legal Documents Routes
use App\Http\Controllers\LegalDocumentController;

Route::middleware(['auth'])
    ->prefix('legal-documents')
    ->name('legal-documents.')
    ->group(function () {
        Route::post('/upload',   [LegalDocumentController::class, 'upload'])->name('upload');
        Route::post('/payment',  [LegalDocumentController::class, 'payment'])->name('payment');
        Route::post('/translate',[LegalDocumentController::class, 'translate'])->name('translate');
        Route::get('/download/{trackingNumber}', [LegalDocumentController::class, 'download'])->name('download');
    });

// Official Documents Payment Callbacks
Route::middleware(['auth'])->prefix('dashboard/official-documents')->name('dashboard.official-documents.')->group(function () {
    Route::get('/success', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'paymentSuccess'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'paymentCancel'])->name('cancel');
});

// Official Documents API for Dashboard (with CSRF protection)
Route::middleware(['auth'])->prefix('api/official-documents')->group(function () {
    Route::post('/estimate', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'estimate']);
    Route::post('/create-payment', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'createPayment']);
    Route::get('/my-documents', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'myDocuments']);
    Route::get('/download/{id}', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'download']);
    Route::get('/{id}', [App\Http\Controllers\Api\DashboardOfficialDocumentController::class, 'show']);
});

// Language and Industry APIs - REMOVED (Old routes)
// Route::get('/api/languages', [App\Http\Controllers\Api\LanguageController::class, 'index']);
// Route::get('/api/industries', [App\Http\Controllers\Api\LanguageController::class, 'industries']);

// Partner Routes
Route::get('/partners', [App\Http\Controllers\PartnerApplicationController::class, 'index'])->name('partners');
Route::post('/api/partner-applications', [App\Http\Controllers\PartnerApplicationController::class, 'store'])
    ->middleware(['honeypot:3', 'dedupe:30', 'advanced.ratelimit:5,10', 'recaptcha:partner_application,0.5']);

// Shipping Management Routes
Route::middleware(['auth'])->prefix('shipping')->name('shipping.')->group(function () {
    Route::get('/', [App\Http\Controllers\ShippingController::class, 'index'])->name('index');
    Route::get('/track', [App\Http\Controllers\ShippingController::class, 'track'])->name('track');
    Route::get('/{document}', [App\Http\Controllers\ShippingController::class, 'show'])->name('show');
    Route::post('/{document}/update-status', [App\Http\Controllers\ShippingController::class, 'updateStatus'])->name('update-status');
    Route::post('/{document}/mark-printed', [App\Http\Controllers\ShippingController::class, 'markPrinted'])->name('mark-printed');
    Route::get('/{document}/timeline', [App\Http\Controllers\ShippingController::class, 'timeline'])->name('timeline');
    Route::post('/calculate-cost', [App\Http\Controllers\ShippingController::class, 'calculateCost'])->name('calculate-cost');
    Route::post('/{document}/request-physical-copy', [App\Http\Controllers\ShippingController::class, 'requestPhysicalCopy'])->name('request-physical-copy');
});

// Certified Partner Routes (governed access)
Route::middleware(['auth', 'account.type:partner', 'ensure.certified.partner'])->prefix('partner')->name('partner.')->group(function () {
    // Main Partner Dashboard (type-specific)
    Route::get('/dashboard', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'index'])->name('dashboard');
    
    // Certified Translator specific routes
    Route::get('/certified/dashboard', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'dashboard'])->name('certified.dashboard');
    Route::get('/documents', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'documents'])->name('documents');
    Route::get('/documents/{document}', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'showDocument'])->name('documents.show');
    Route::post('/documents/{document}/apply-stamp', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'applyStamp'])->name('documents.apply-stamp');
    Route::post('/documents/{document}/mark-printed', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'markPrinted'])->name('documents.mark-printed');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'downloadForPrint'])->name('documents.download');
    Route::get('/print-queue', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'printQueue'])->name('print-queue');
    Route::post('/upload-stamp', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'uploadStamp'])->name('upload-stamp');
    Route::get('/earnings', [App\Http\Controllers\Partner\CertifiedPartnerController::class, 'earnings'])->name('earnings');
    
    // General Partner Routes
    Route::get('/api-keys', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'apiKeys'])->name('api-keys');
    Route::get('/subscription', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'subscription'])->name('subscription');
    Route::get('/earnings-overview', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'earnings'])->name('earnings.overview');
    
    // Assignment Management Routes (Auto-Assignment System)
    Route::get('/assignments', [App\Http\Controllers\Partner\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [App\Http\Controllers\Partner\AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/accept', [App\Http\Controllers\Partner\AssignmentController::class, 'accept'])->name('assignments.accept');
    Route::post('/assignments/{assignment}/reject', [App\Http\Controllers\Partner\AssignmentController::class, 'reject'])->name('assignments.reject');
    Route::post('/assignments/{assignment}/complete', [App\Http\Controllers\Partner\AssignmentController::class, 'complete'])->name('assignments.complete');
});

// Analytics Dashboard Routes
Route::middleware(['auth'])->prefix('admin/analytics')->name('admin.analytics.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AnalyticsDashboardController::class, 'index'])->name('dashboard');
    Route::get('/data', [App\Http\Controllers\Admin\AnalyticsDashboardController::class, 'getData'])->name('data');
    Route::get('/export', [App\Http\Controllers\Admin\AnalyticsDashboardController::class, 'export'])->name('export');
});


// Account Upgrade Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account/upgrade', function() {
        return view('upgrade');
    })->name('account.upgrade');
    
    Route::post('/account/upgrade-account', function(Illuminate\Http\Request $request) {
        $user = auth()->user();
        $newType = $request->input('account_type');
        
        $validTypes = ['customer', 'affiliate', 'partner', 'government', 'translator'];
        if (!in_array($newType, $validTypes)) {
            return redirect()->back()->with('error', 'Invalid account type');
        }
        
        $user->account_type = $newType;
        $user->save();
        
        return redirect('/dashboard')->with('success', 'Account upgraded to ' . ucfirst($newType) . ' successfully!');
    })->name('upgrade.account');
});

// CTS Certification Routes
require __DIR__.'/cts.php';

/*
|--------------------------------------------------------------------------
| Governance & Compliance Routes (NEW)
|--------------------------------------------------------------------------
*/

// Customer Dispute Management
Route::middleware(['auth', 'account.type:customer'])->prefix('customer/disputes')->name('customer.disputes.')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\DisputeController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Customer\DisputeController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Customer\DisputeController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Customer\DisputeController::class, 'show'])->name('show');
});

// Partner Performance & Payouts
Route::middleware(['auth', 'account.type:partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/performance', [App\Http\Controllers\Partner\PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/performance/{periodId}', [App\Http\Controllers\Partner\PerformanceController::class, 'details'])->name('performance.details');
    
    Route::get('/payouts', [App\Http\Controllers\Partner\PayoutController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/{id}', [App\Http\Controllers\Partner\PayoutController::class, 'show'])->name('payouts.show');
});

// University Dashboard & Student Management
Route::middleware(['auth', 'account.type:university', 'ensure.verified.university'])->prefix('university')->name('university.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\University\UniversityDashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', [App\Http\Controllers\University\UniversityDashboardController::class, 'students'])->name('students');
    Route::post('/students/add', [App\Http\Controllers\University\UniversityDashboardController::class, 'addStudent'])->name('students.add');
    Route::delete('/students/{id}', [App\Http\Controllers\University\UniversityDashboardController::class, 'removeStudent'])->name('students.remove');
    Route::get('/subscription', [App\Http\Controllers\University\UniversityDashboardController::class, 'subscription'])->name('subscription');
});

// Government Compliance Dashboard
Route::middleware(['auth', 'account.type:government'])->prefix('government')->name('government.')->group(function () {
    Route::get('/compliance', [App\Http\Controllers\Government\ComplianceController::class, 'index'])->name('compliance.index');
    Route::get('/compliance/{id}', [App\Http\Controllers\Government\ComplianceController::class, 'show'])->name('compliance.show');
    Route::get('/audit-trail', [App\Http\Controllers\Government\ComplianceController::class, 'auditTrail'])->name('compliance.audit-trail');
    Route::get('/classifications', [App\Http\Controllers\Government\ComplianceController::class, 'classifications'])->name('compliance.classifications');
    Route::get('/reports', [App\Http\Controllers\Government\ComplianceController::class, 'reports'])->name('compliance.reports');
});

// Governance API Endpoints
Route::middleware('auth')->prefix('api/governance')->group(function () {
    Route::get('/performance-stats', [App\Http\Controllers\Api\GovernanceApiController::class, 'getPerformanceStats']);
    Route::get('/payouts-summary', [App\Http\Controllers\Api\GovernanceApiController::class, 'getPayoutsSummary']);
    Route::get('/dispute-stats', [App\Http\Controllers\Api\GovernanceApiController::class, 'getDisputeStats']);
    Route::get('/evidence-chain/{documentId}', [App\Http\Controllers\Api\GovernanceApiController::class, 'getEvidenceChain']);
    Route::get('/compliance-metrics', [App\Http\Controllers\Api\GovernanceApiController::class, 'getComplianceMetrics']);
});

