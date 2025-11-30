<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIDeveloperAuth
{
    public function handle(Request $request, Closure $next)
    {
        // السماح بصفحات تسجيل الدخول
        if ($request->routeIs('ai-developer.login') || $request->routeIs('ai-developer.authenticate')) {
            return $next($request);
        }
        
        // التحقق من المصادقة - خيارات متعددة:
        // 1. Session خاصة بـ AI Developer
        // 2. Filament Auth (للمستخدمين المسجلين في Filament)
        // 3. Laravel Auth العادي
        
        $isAiDevAuthenticated = $request->session()->has('ai_developer_authenticated');
        $isFilamentAuthenticated = Auth::guard('web')->check(); // Filament يستخدم web guard
        
        if (!$isAiDevAuthenticated && !$isFilamentAuthenticated) {
            return redirect()->route('ai-developer.login');
        }
        
        return $next($request);
    }
}
