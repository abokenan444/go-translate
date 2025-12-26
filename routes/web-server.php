<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Test Locale Page (for debugging)
Route::get('/test-locale', function () {
    return view('test-locale');
})->name('test.locale');

// Landing Page
Route::get("/", [\App\Http\Controllers\HomeController::class, "index"])->name("home");

// Public Pages
Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/pricing', [\App\Http\Controllers\PricingController::class, 'index'])->name('pricing');
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

// Careers page
Route::view('/careers', 'pages.careers')->name('careers');

Route::get('/demo', function () {
    return view('pages.demo');
})->name('demo');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact/submit', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

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
    Route::get('/dashboard', function () {
        return view('dashboard.app');
    })->name('dashboard');
    
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
Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// Real-Time Voice Translation
Route::get('/realtime/meeting/{publicId}', [App\Http\Controllers\RealTime\RealTimeSessionController::class, 'showMeeting'])->name('realtime.meeting.show');
Route::get('/realtime/meeting-video/{publicId}', function ($publicId) { $session = App\Models\RealTimeSession::where('public_id', $publicId)->firstOrFail(); return view('realtime.meeting-video', compact('session')); })->name('realtime.meeting.video');
Route::get('/realtime/call/{publicId}', function ($publicId) { $session = App\Models\RealTimeSession::where('public_id', $publicId)->firstOrFail(); return view('realtime.call-mode', compact('session')); })->name('realtime.call');
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
    Route::get('/subscription', [App\Http\Controllers\DashboardApiController::class, 'getSubscription']);
    // Translate endpoint for dashboard (session-auth)
    Route::post('/translate', [\App\Http\Controllers\API\ApiTranslationController::class, 'translate']);
});

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
    Route::post('/mte/text', [App\Http\Controllers\MTEController::class, 'translateText']);
    Route::post('/mte/image', [App\Http\Controllers\MTEController::class, 'translateImage']);
    Route::post('/mte/pdf', [App\Http\Controllers\MTEController::class, 'translatePDF']);
    Route::post('/mte/pdf/async', [App\Http\Controllers\MTEController::class, 'enqueuePDF']);
    Route::get('/mte/pdf/status/{jobId}', [App\Http\Controllers\MTEController::class, 'pdfStatus']);
    Route::post('/mte/asr', [App\Http\Controllers\MTEController::class, 'transcribeAudio']);
    Route::post('/mte/tts', [App\Http\Controllers\MTEController::class, 'synthesizeSpeech']);

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

Route::get('/{slug}', [App\Http\Controllers\WebsiteContentController::class, 'show'])->where('slug', 'terms-of-service|privacy-policy|gdpr|security|help-center|guides');

// Sandbox Preview Routes
require __DIR__ . '/sandbox.php';
