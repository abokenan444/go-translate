<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageRecord extends Model
{
    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'service_type',
        'source_lang',
        'target_lang',
        'character_count',
        'word_count',
        'from_cache',
        'unit_price',
        'total_cost',
        'pricing_model',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'from_cache' => 'boolean',
        'unit_price' => 'decimal:6',
        'total_cost' => 'decimal:4',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Get usage statistics
     */
    public static function getStatistics($userId, string $period = 'month')
    {
        $query = static::where('user_id', $userId);

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        return [
            'total_translations' => $query->count(),
            'total_characters' => $query->sum('character_count'),
            'total_cost' => $query->sum('total_cost'),
            'cached_translations' => $query->where('from_cache', true)->count(),
            'cache_rate' => $query->count() > 0 
                ? round(($query->where('from_cache', true)->count() / $query->count()) * 100, 2) 
                : 0,
        ];
    }
}
