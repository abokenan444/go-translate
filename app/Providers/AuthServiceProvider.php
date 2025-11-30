<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Feature;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\\Models\\Model' => 'App\\Policies\\ModelPolicy',
        Feature::class => \App\Policies\FeaturePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('super-admin', function (User $user) {
            return $user->role === 'super_admin';
        });
    }
}
