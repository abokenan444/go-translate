<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PromptEngine bindings
        $this->app->bind(
            \App\Services\PromptEngine\Repositories\PromptRepositoryInterface::class,
            function ($app) {
                $jsonPath = base_path('app/Services/PromptEngine/Data/prompts.json');
                return new \App\Services\PromptEngine\Repositories\JsonPromptRepository($jsonPath);
            }
        );
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only force HTTPS if explicitly enabled in environment
        if (config('app.force_https', false)) {
            URL::forceScheme('https');
        }
        
        // Trust proxies (for reverse proxy setups)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
            $this->app['url']->forceScheme('https');
        }

        // Register Observers
        \App\Models\Document::observe(\App\Observers\DocumentObserver::class);
    }
}
