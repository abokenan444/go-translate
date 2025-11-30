<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Complaint;
use App\Models\Page as PageModel;
use App\Models\ActivityLog;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Analytics';
    
    protected static ?string $navigationLabel = 'التقارير';
    
    // grouped under Analytics
    
    protected static ?int $navigationSort = 2;
    
    protected static string $view = 'filament.admin.pages.reports';

    public function getViewData(): array
    {
        return [
            // تقرير المستخدمين
            'totalUsers' => User::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'verifiedUsers' => User::whereNotNull('email_verified_at')->count(),
            'newUsersToday' => User::whereDate('created_at', today())->count(),
            'newUsersThisWeek' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'newUsersThisMonth' => User::whereMonth('created_at', now()->month)->count(),
            
            // تقرير الاشتراكات
            'totalSubscriptions' => UserSubscription::count(),
            'activeSubscriptions' => UserSubscription::where('status', 'active')->count(),
            'expiredSubscriptions' => UserSubscription::where('status', 'expired')->count(),
            'cancelledSubscriptions' => UserSubscription::where('status', 'cancelled')->count(),
            
            // تقرير الشكاوى
            'totalComplaints' => Complaint::count(),
            'pendingComplaints' => Complaint::where('status', 'pending')->count(),
            'resolvedComplaints' => Complaint::where('status', 'resolved')->count(),
            'complaintsToday' => Complaint::whereDate('created_at', today())->count(),
            
            // تقرير المحتوى
            'totalPages' => PageModel::count(),
            'publishedPages' => PageModel::where('status', 'published')->count(),
            'draftPages' => PageModel::where('status', 'draft')->count(),
            'pagesInHeader' => PageModel::where('show_in_header', true)->count(),
            'pagesInFooter' => PageModel::where('show_in_footer', true)->count(),
            
            // تقرير النشاطات
            'totalActivities' => ActivityLog::count(),
            'activitiesToday' => ActivityLog::whereDate('created_at', today())->count(),
            'activitiesThisWeek' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            
            // تقرير الأدوار
            'usersByRole' => User::selectRaw('role, count(*) as count')->groupBy('role')->get(),
        ];
    }
}
