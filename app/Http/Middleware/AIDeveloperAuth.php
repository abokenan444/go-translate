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
        if ($request->routeIs('ai-developer.login') || $request->routeIs('ai-developer.login.post')) {
            return $next($request);
        }
        
        // التحقق من المصادقة - خيارات متعددة:
        // 1. Session خاصة بـ AI Developer
        // 2. Filament Auth (للمستخدمين المسجلين في Filament)
        // 3. Laravel Auth العادي
        
        if (!config('ai_developer.enabled')) {
            abort(403, 'AI Developer is disabled.');
        }

        // 1) Session الخاصة بـ AI Developer (password-based)
        $isAiDevAuthenticated = $request->session()->has('ai_developer_authenticated');
        if ($isAiDevAuthenticated) {
            return $next($request);
        }

        // 2) Allow ONLY the configured owner via normal auth
        $user = Auth::guard('web')->user();
        $ownerEmail = config('ai_developer.owner_email');
        if ($user && $ownerEmail && $user->email === $ownerEmail) {
            return $next($request);
        }

        return redirect()->route('ai-developer.login');
        
    }
}
