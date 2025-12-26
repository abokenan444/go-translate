<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MobileInvite extends Model
{
    protected $fillable = [
        'inviter_id',
        'invite_code',
        'invited_email',
        'invited_phone',
        'invited_name',
        'registered_user_id',
        'status',
        'reward_minutes',
    ];

    protected $casts = [
        'reward_minutes' => 'decimal:2',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function registeredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('invite_code', $code)->exists());

        return $code;
    }

    public function markAsRegistered(User $user, float $rewardMinutes = 10.0): void
    {
        $this->update([
            'registered_user_id' => $user->id,
            'status' => 'registered',
            'reward_minutes' => $rewardMinutes,
        ]);
    }

    public function markAsRewarded(): void
    {
        $this->update(['status' => 'rewarded']);
    }
}
