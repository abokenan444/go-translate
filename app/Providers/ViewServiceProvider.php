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
        // مشاركة عناصر القائمة فقط مع جميع الـ Views
        // الإحصائيات يتم تمريرها من Controllers المختصة
        View::composer('*', function ($view) {
            // جلب عناصر قائمة الهيدر
            $headerMenuItems = MenuItem::active()
                ->inLocation('header')
                ->topLevel()
                ->ordered()
                ->get();

            // جلب عناصر قائمة الفوتر
            $footerMenuItems = MenuItem::active()
                ->inLocation('footer')
                ->topLevel()
                ->ordered()
                ->get();

            $view->with([
                'headerMenuItems' => $headerMenuItems,
                'footerMenuItems' => $footerMenuItems,
            ]);
        });
    }
}
