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
        // حساب الإحصائيات
        $totalUsers = User::count();
        $totalSubscriptions = Subscription::where('status', 'active')->count();
        $totalPages = Page::where('status', 'published')->count();
        $totalCompanies = Company::where('status', 'active')->count();
        
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
