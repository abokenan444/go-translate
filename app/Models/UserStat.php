<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStat extends Model
{
    protected $fillable = [
        'user_id',
        'total_requests',
        'total_tokens',
        'monthly_requests',
        'monthly_tokens',
        'current_month',
        'last_request_at',
    ];

    protected $casts = [
        'current_month'   => 'date',
        'last_request_at' => 'datetime',
        'total_requests' => 'integer',
        'total_tokens' => 'integer',
        'monthly_requests' => 'integer',
        'monthly_tokens' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reset monthly stats if new month
     */
    public function resetMonthlyIfNeeded(): void
    {
        $currentMonth = now()->format('Y-m-01');
        
        if ($this->current_month?->format('Y-m-01') !== $currentMonth) {
            $this->update([
                'monthly_requests' => 0,
                'monthly_tokens' => 0,
                'current_month' => $currentMonth,
            ]);
        }
    }

    /**
     * Increment usage stats
     */
    public function incrementUsage(int $tokens): void
    {
        $this->resetMonthlyIfNeeded();
        
        $this->increment('total_requests');
        $this->increment('total_tokens', $tokens);
        $this->increment('monthly_requests');
        $this->increment('monthly_tokens', $tokens);
        $this->update(['last_request_at' => now()]);
    }
}
