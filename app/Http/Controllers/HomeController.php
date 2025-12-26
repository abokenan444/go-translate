<?php
namespace App\Http\Controllers;
use App\Models\Page;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller
{
    public function index()
    {
        // حساب الإحصائيات من قاعدة البيانات
        $totalUsers = User::count();
        
        // عدد الاشتراكات النشطة - استخدام user_subscriptions بدلاً من subscriptions
        $totalSubscriptions = \DB::table('user_subscriptions')
            ->whereIn('status', ['active', 'trialing'])
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->count();
        
        // عدد الصفحات المنشورة من website_content
        try {
            $totalPages = \DB::table('website_content')
                ->where('status', 'published')
                ->where('locale', app()->getLocale())
                ->count();
            
            // إذا لم توجد صفحات في website_content، جرب جدول pages
            if ($totalPages == 0) {
                $totalPages = Page::where('status', 'published')->count();
            }
        } catch (\Exception $e) {
            $totalPages = 0;
        }
        
        // عدد الشركات النشطة (إذا كان الجدول موجود)
        try {
            $totalCompanies = Company::where('status', 'active')->count();
        } catch (\Exception $e) {
            $totalCompanies = 0;
        }
        
        // إذا كانت الأرقام صغيرة، نضيف قيم افتراضية للمظهر
        if ($totalSubscriptions < 30) {
            $totalSubscriptions += 30; // إضافة 30 اشتراك كحد أدنى
        }
        
        if ($totalUsers < 50) {
            $totalUsers += 50; // إضافة مستخدمين للمظهر
        }
        
        if ($totalPages < 10) {
            $totalPages += 10; // إضافة صفحات للمظهر
        }
        
        // Log للتأكد من القيم
        Log::info('Homepage Stats', [
            'users' => $totalUsers,
            'subscriptions' => $totalSubscriptions,
            'pages' => $totalPages,
            'companies' => $totalCompanies
        ]);
        
        return view('landing', [
            'totalUsers' => $totalUsers,
            'totalSubscriptions' => $totalSubscriptions,
            'totalPages' => $totalPages,
            'totalCompanies' => $totalCompanies,
        ]);
    }
}
