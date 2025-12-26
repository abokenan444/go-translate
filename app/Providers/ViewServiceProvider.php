<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MenuItem;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // مشاركة عناصر قائمة فارغة لتجنب الأخطاء
        View::composer('*', function ($view) {
            $view->with([
                'headerMenuItems' => collect(),
                'footerMenuItems' => collect(),
            ]);
        });
    }
}
