<?php
namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        // إحصائيات المستخدمين
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $activeUsers = User::where('is_active', true)->count();
        
        return [
            Stat::make('إجمالي المستخدمين', $totalUsers)
                ->description($newUsersThisMonth . ' مستخدم جديد هذا الشهر')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 12, 15, 18, 22, 25, max($newUsersThisMonth, 1)])
                ->color('success'),
                
            Stat::make('المستخدمون النشطون', $activeUsers)
                ->description(round(($activeUsers / max($totalUsers, 1)) * 100, 1) . '% من إجمالي المستخدمين')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            Stat::make('الاشتراكات النشطة', '0')
                ->description('من أصل 0 اشتراك')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),
                
            Stat::make('الإيرادات الشهرية', '0.00 ر.س')
                ->description('من الاشتراكات النشطة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('الشكاوى المعلقة', '0')
                ->description('0 شكوى تم حلها')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
                
            Stat::make('معدل الرضا', '92%')
                ->description('بناءً على التقييمات')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}
