<?php

namespace App\Http\Controllers;

use App\Services\SuperAIAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SuperAIAgentController extends Controller
{
    protected SuperAIAgentService $aiAgent;

    public function __construct(SuperAIAgentService $aiAgent)
    {
        $this->aiAgent = $aiAgent;
    }

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showDirectLogin()
    {
        // التحقق من أن المستخدم غير مسجل دخول
        if (session()->has('super_ai_authenticated')) {
            return redirect()->route('super-ai.dashboard');
        }

        return view('super-ai.login');
    }

    /**
     * تسجيل الدخول المباشر (محمي بكلمة مرور)
     */
    public function directLogin(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        // Rate limiting - 5 محاولات كل 15 دقيقة
        $key = 'super-ai-login:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('SuperAI Login Rate Limit Exceeded', [
                'ip' => $request->ip(),
                'remaining_seconds' => $seconds,
            ]);

            return back()->withErrors([
                'password' => "محاولات كثيرة جداً. حاول مرة أخرى بعد {$seconds} ثانية.",
            ])->withInput();
        }

        RateLimiter::hit($key, 900); // 15 دقيقة

        $masterPassword = config('ai_developer.emergency.password') ?? env('AI_EMERGENCY_PASSWORD');
        
        if (!$masterPassword) {
            Log::error('SuperAI Emergency Password Not Set');
            abort(500, 'كلمة المرور الطارئة غير مضبوطة في ملف التكوين');
        }

        // التحقق من كلمة المرور
        if (!Hash::check($request->password, $masterPassword)) {
            Log::warning('SuperAI Failed Login Attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors([
                'password' => 'كلمة مرور غير صحيحة',
            ])->withInput();
        }

        // مسح Rate Limiter بعد نجاح تسجيل الدخول
        RateLimiter::clear($key);

        // تسجيل الدخول
        session()->put('super_ai_authenticated', true);
        session()->put('super_ai_login_time', now());
        session()->put('super_ai_ip', $request->ip());

        Log::info('SuperAI Emergency Login Success', [
            'ip' => $request->ip(),
        ]);

        return redirect()->route('super-ai.dashboard');
    }

    /**
     * تسجيل الخروج
     */
    public function logout()
    {
        session()->forget('super_ai_authenticated');
        session()->forget('super_ai_login_time');
        session()->forget('super_ai_ip');

        return redirect()->route('super-ai.login');
    }

    /**
     * لوحة التحكم الرئيسية
     */
    public function dashboard()
    {
        $this->ensureAuthenticated();

        // فحص صحة النظام
        $health = $this->aiAgent->systemHealthCheck();

        return view('super-ai.dashboard', compact('health'));
    }

    /**
     * معالجة طلب ذكي
     */
    public function processRequest(Request $request)
    {
        $this->ensureAuthenticated();

        $request->validate([
            'prompt' => 'required|string|min:3|max:5000',
        ]);

        $user = Auth::user();
        $result = $this->aiAgent->processIntelligentRequest($request->prompt, $user);

        return response()->json($result);
    }

    /**
     * تحليل السجلات
     */
    public function analyzeLogs(Request $request)
    {
        $this->ensureAuthenticated();

        $hours = $request->input('hours', 24);
        $result = $this->aiAgent->analyzeLogs($hours);

        return response()->json($result);
    }

    /**
     * فحص صحة النظام
     */
    public function healthCheck()
    {
        $this->ensureAuthenticated();

        $result = $this->aiAgent->systemHealthCheck();

        return response()->json($result);
    }

    /**
     * اقتراح تحسينات
     */
    public function suggestImprovements()
    {
        $this->ensureAuthenticated();

        $result = $this->aiAgent->suggestImprovements();

        return response()->json($result);
    }

    /**
     * تشغيل أمر محدد
     */
    public function executeCommand(Request $request)
    {
        $this->ensureAuthenticated();

        $request->validate([
            'command' => 'required|string',
        ]);

        $allowedCommands = config('ai_developer.allowed_commands', []);
        
        if (!in_array($request->command, $allowedCommands)) {
            return response()->json([
                'success' => false,
                'error' => 'هذا الأمر غير مسموح به',
            ], 403);
        }

        $result = \Illuminate\Support\Facades\Process::run($request->command);

        return response()->json([
            'success' => $result->successful(),
            'output' => $result->output(),
            'error' => $result->errorOutput(),
        ]);
    }

    /**
     * تنظيف الكاش
     */
    public function clearCache()
    {
        $this->ensureAuthenticated();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return response()->json([
            'success' => true,
            'message' => 'تم تنظيف جميع أنواع الكاش بنجاح',
        ]);
    }

    /**
     * إعادة تشغيل النظام
     */
    public function restart()
    {
        $this->ensureAuthenticated();

        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        \Illuminate\Support\Facades\Artisan::call('view:cache');

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة بناء الكاش بنجاح',
        ]);
    }

    /**
     * التحقق من المصادقة
     */
    protected function ensureAuthenticated()
    {
        if (!session()->has('super_ai_authenticated')) {
            abort(401, 'غير مصرح');
        }

        // التحقق من انتهاء الجلسة (4 ساعات)
        $loginTime = session()->get('super_ai_login_time');
        if ($loginTime && now()->diffInHours($loginTime) > 4) {
            session()->forget('super_ai_authenticated');
            abort(401, 'انتهت صلاحية الجلسة');
        }

        // التحقق من IP
        $sessionIp = session()->get('super_ai_ip');
        if ($sessionIp && $sessionIp !== request()->ip()) {
            Log::warning('SuperAI IP Mismatch', [
                'session_ip' => $sessionIp,
                'request_ip' => request()->ip(),
            ]);
            session()->forget('super_ai_authenticated');
            abort(401, 'تغيير في عنوان IP');
        }
    }
}
