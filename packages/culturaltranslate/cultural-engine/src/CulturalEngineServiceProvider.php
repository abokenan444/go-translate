<?php

namespace CulturalTranslate\CulturalEngine;

use Illuminate\Support\ServiceProvider;
use CulturalTranslate\CulturalEngine\Services\CulturalPromptBuilder;
use CulturalTranslate\CulturalEngine\Services\CulturalPostProcessor;

class CulturalEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cultural_engine.php',
            'cultural_engine'
        );

        $this->app->singleton(CulturalPromptBuilder::class, function ($app) {
            return new CulturalPromptBuilder(config('cultural_engine'));
        });

        $this->app->singleton(CulturalPostProcessor::class, function ($app) {
            return new CulturalPostProcessor(config('cultural_engine'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cultural_engine.php' => config_path('cultural_engine.php'),
        ], 'cultural-engine-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders/CulturalEngineSeeder.php'
                => database_path('seeders/CulturalEngineSeeder.php'),
        ], 'cultural-engine-seeders');

        $this->publishes([
            __DIR__ . '/../stubs/filament' => app_path('Filament/Admin/Resources'),
        ], 'cultural-engine-filament');
    }
}
