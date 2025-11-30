<?php
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
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add SetLocale middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        $middleware->alias([
            'ai-owner' => \App\Http\Middleware\EnsureAiDeveloperOwner::class,
            'ai.dev.auth' => \App\Http\Middleware\AIDeveloperAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
