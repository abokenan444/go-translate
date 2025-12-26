<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class GuestUsageTracking extends Model
{
    protected $table = 'guest_usage_tracking';

    protected $fillable = [
        'ip_address',
        'fingerprint',
        'cookie_id',
        'user_agent',
        'daily_count',
        'usage_date',
        'translation_history',
        'first_used_at',
        'last_used_at',
        'blocked',
        'blocked_until',
    ];

    protected $casts = [
        'translation_history' => 'array',
        'first_used_at' => 'datetime',
        'last_used_at' => 'datetime',
        'blocked' => 'boolean',
        'blocked_until' => 'datetime',
        'usage_date' => 'date',
    ];

    const MAX_DAILY_TRANSLATIONS = 2; // الحد الأقصى يومياً للضيوف
    const BLOCK_DURATION_DAYS = 7; // مدة الحظر بعد التجاوز

    /**
     * التحقق من إمكانية الترجمة
     */
    public static function canTranslate(
        string $ipAddress,
        ?string $fingerprint = null,
        ?string $cookieId = null
    ): array {
        $today = now()->toDateString();
        
        // البحث عن السجل
        $tracking = static::where('usage_date', $today)
            ->where(function ($query) use ($ipAddress, $fingerprint, $cookieId) {
                $query->where('ip_address', $ipAddress);
                
                if ($fingerprint) {
                    $query->orWhere('fingerprint', $fingerprint);
                }
                
                if ($cookieId) {
                    $query->orWhere('cookie_id', $cookieId);
                }
            })
            ->first();
        
        // التحقق من الحظر
        if ($tracking && $tracking->blocked) {
            if ($tracking->blocked_until && now()->lt($tracking->blocked_until)) {
                return [
                    'allowed' => false,
                    'reason' => 'blocked',
                    'blocked_until' => $tracking->blocked_until,
                    'remaining' => 0,
                ];
            } else {
                // إلغاء الحظر
                $tracking->update(['blocked' => false, 'blocked_until' => null]);
            }
        }
        
        // التحقق من الحد اليومي
        if ($tracking && $tracking->daily_count >= self::MAX_DAILY_TRANSLATIONS) {
            return [
                'allowed' => false,
                'reason' => 'daily_limit_reached',
                'limit' => self::MAX_DAILY_TRANSLATIONS,
                'used' => $tracking->daily_count,
                'remaining' => 0,
            ];
        }
        
        $used = $tracking ? $tracking->daily_count : 0;
        $remaining = self::MAX_DAILY_TRANSLATIONS - $used;
        
        return [
            'allowed' => true,
            'used' => $used,
            'remaining' => $remaining,
            'limit' => self::MAX_DAILY_TRANSLATIONS,
        ];
    }

    /**
     * تسجيل استخدام الترجمة
     */
    public static function recordUsage(
        string $ipAddress,
        ?string $fingerprint = null,
        ?string $cookieId = null,
        ?string $userAgent = null,
        ?array $translationData = null
    ): void {
        $today = now()->toDateString();
        
        $tracking = static::where('usage_date', $today)
            ->where(function ($query) use ($ipAddress, $fingerprint, $cookieId) {
                $query->where('ip_address', $ipAddress);
                
                if ($fingerprint) {
                    $query->orWhere('fingerprint', $fingerprint);
                }
                
                if ($cookieId) {
                    $query->orWhere('cookie_id', $cookieId);
                }
            })
            ->first();
        
        if ($tracking) {
            // تحديث السجل الموجود
            $history = $tracking->translation_history ?? [];
            if ($translationData) {
                $history[] = array_merge($translationData, ['timestamp' => now()]);
            }
            
            $tracking->update([
                'daily_count' => $tracking->daily_count + 1,
                'last_used_at' => now(),
                'translation_history' => $history,
                'fingerprint' => $fingerprint ?? $tracking->fingerprint,
                'cookie_id' => $cookieId ?? $tracking->cookie_id,
            ]);
        } else {
            // إنشاء سجل جديد
            static::create([
                'ip_address' => $ipAddress,
                'fingerprint' => $fingerprint,
                'cookie_id' => $cookieId,
                'user_agent' => $userAgent,
                'daily_count' => 1,
                'usage_date' => $today,
                'translation_history' => $translationData ? [$translationData] : [],
                'first_used_at' => now(),
                'last_used_at' => now(),
            ]);
        }
    }

    /**
     * حظر المستخدم لمدة معينة
     */
    public static function blockUser(
        string $ipAddress,
        ?string $fingerprint = null,
        ?string $cookieId = null,
        int $days = self::BLOCK_DURATION_DAYS
    ): void {
        $today = now()->toDateString();
        
        static::where('usage_date', $today)
            ->where(function ($query) use ($ipAddress, $fingerprint, $cookieId) {
                $query->where('ip_address', $ipAddress);
                
                if ($fingerprint) {
                    $query->orWhere('fingerprint', $fingerprint);
                }
                
                if ($cookieId) {
                    $query->orWhere('cookie_id', $cookieId);
                }
            })
            ->update([
                'blocked' => true,
                'blocked_until' => now()->addDays($days),
            ]);
    }

    /**
     * توليد Cookie ID فريد
     */
    public static function generateCookieId(): string
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * تنظيف السجلات القديمة (أكثر من 30 يوم)
     */
    public static function cleanup(int $days = 30): int
    {
        return static::where('usage_date', '<', now()->subDays($days))
            ->delete();
    }
}
