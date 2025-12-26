<?php
umask(0002);
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load API routes with 'api' middleware group (NOT 'web')
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/ai_developer.php'));
            
            // Emergency SuperAI Agent routes
            Route::middleware('web')
                ->group(base_path('routes/super_ai.php'));
            
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api-realtime.php'));

            // Mobile API Routes
            Route::middleware('api')
                ->prefix('api/mobile')
                ->group(base_path('routes/api_mobile.php'));

            // CTS (Cultural Translate Standard) Routes
            Route::middleware('web')
                ->group(base_path('routes/cts.php'));

            // Government Portal Routes (path-based country detection)
            Route::middleware(['web', \App\Http\Middleware\GovernmentPortalMiddleware::class])
                ->group(base_path('routes/government.php'));

            // Partner web dashboard routes
            Route::middleware('web')
                ->group(base_path('routes/partner_web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add SetLocale middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Add GET protection globally to web group
        $middleware->web(append: [
            \App\Http\Middleware\GetRequestProtection::class,
        ]);
        
        // Add request metrics tracking to API group
        $middleware->api(append: [
            \App\Http\Middleware\TrackRequestMetrics::class,
        ]);
        
        $middleware->alias([
            'ai-owner' => \App\Http\Middleware\EnsureAiDeveloperOwner::class,
            'ai.dev.auth' => \App\Http\Middleware\AIDeveloperAuth::class,
            'tiered.ratelimit' => \App\Http\Middleware\TieredRateLimit::class,
            'recaptcha' => \App\Http\Middleware\VerifyRecaptcha::class,
            'advanced.ratelimit' => \App\Http\Middleware\AdvancedRateLimiter::class,
            'honeypot' => \App\Http\Middleware\HoneypotProtection::class,
            'dedupe' => \App\Http\Middleware\MessageDeduplication::class,
            'ensure.certified.partner' => \App\Http\Middleware\EnsureCertifiedPartner::class,
            'ensure.affiliate' => \App\Http\Middleware\EnsureAffiliate::class,
            'account.type' => \App\Http\Middleware\CheckAccountType::class,
            'government_api' => \App\Http\Middleware\GovernmentApiRateLimiter::class,
            'require.subscription.plan' => \App\Http\Middleware\RequireSubscriptionPlan::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
