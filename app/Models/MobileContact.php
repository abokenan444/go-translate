<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileContact extends Model
{
    protected $fillable = [
        'user_id',
        'contact_user_id',
        'name',
        'phone',
        'email',
        'avatar_url',
        'is_favorite',
        'last_called_at',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'last_called_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contactUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_user_id');
    }

    public function isOnline(): bool
    {
        if (!$this->contactUser) {
            return false;
        }
        // Consider online if active in last 5 minutes
        return $this->contactUser->last_active_at 
            && $this->contactUser->last_active_at->gt(now()->subMinutes(5));
    }
}
