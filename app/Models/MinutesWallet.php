<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinutesWallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance_seconds',
    ];

    protected $casts = [
        'balance_seconds' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addMinutes(int $minutes): void
    {
        $seconds = max(0, $minutes) * 60;
        $this->balance_seconds += $seconds;
        $this->save();
    }

    public function hasSeconds(int $seconds): bool
    {
        return $this->balance_seconds >= max(0, $seconds);
    }

    public function debitSeconds(int $seconds): bool
    {
        $seconds = max(0, $seconds);
        if ($seconds === 0) return true;

        if ($this->balance_seconds < $seconds) {
            return false;
        }

        $this->balance_seconds -= $seconds;
        $this->save();
        return true;
    }

    public function getBalanceMinutesAttribute(): int
    {
        return (int) floor($this->balance_seconds / 60);
    }
}
