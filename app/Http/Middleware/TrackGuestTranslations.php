<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GuestUsageTracking;
use Symfony\Component\HttpFoundation\Response;

class TrackGuestTranslations
{
    public function handle(Request $request, Closure $next): Response
    {
        // السماح للمستخدمين المسجلين
        if ($request->user()) {
            return $next($request);
        }

        // جمع معلومات التتبع
        $ipAddress = $request->ip();
        $fingerprint = $request->header('X-Fingerprint'); // من JavaScript
        $cookieId = $request->cookie('guest_translation_id');
        
        // إنشاء Cookie ID إذا لم يكن موجوداً
        if (!$cookieId) {
            $cookieId = GuestUsageTracking::generateCookieId();
        }

        // التحقق من إمكانية الترجمة
        $canTranslate = GuestUsageTracking::canTranslate($ipAddress, $fingerprint, $cookieId);

        if (!$canTranslate['allowed']) {
            $response = [
                'success' => false,
                'message' => $this->getBlockMessage($canTranslate),
                'data' => $canTranslate,
                'requires_registration' => true,
            ];

            return response()->json($response, 429);
        }

        // السماح بالطلب
        $response = $next($request);

        // تسجيل الاستخدام بعد نجاح الترجمة
        if ($response->status() === 200 && $request->isMethod('post')) {
            $translationData = [
                'source_lang' => $request->input('source_lang'),
                'target_lang' => $request->input('target_lang'),
                'text_length' => strlen($request->input('source_text', '')),
            ];

            GuestUsageTracking::recordUsage(
                $ipAddress,
                $fingerprint,
                $cookieId,
                $request->userAgent(),
                $translationData
            );
        }

        // إضافة Cookie للتتبع (صالح لمدة 30 يوم)
        return $response->withCookie(
            cookie('guest_translation_id', $cookieId, 60 * 24 * 30)
        );
    }

    private function getBlockMessage(array $canTranslate): string
    {
        if ($canTranslate['reason'] === 'blocked') {
            $days = now()->diffInDays($canTranslate['blocked_until']);
            return "تم حظر الترجمة المجانية لمدة {$days} أيام. يرجى التسجيل للحصول على وصول غير محدود.";
        }

        if ($canTranslate['reason'] === 'daily_limit_reached') {
            return "لقد استخدمت الحد الأقصى من الترجمات المجانية اليوم ({$canTranslate['limit']} ترجمات). سجل الآن للحصول على ترجمات غير محدودة!";
        }

        return "غير مسموح بالترجمة في الوقت الحالي. يرجى التسجيل للمتابعة.";
    }
}
