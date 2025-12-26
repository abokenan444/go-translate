<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GovInvite extends Model
{
    protected $table = 'gov_invites';

    protected $fillable = [
        'gov_entity_id',
        'invited_by',
        'token',
        'email',
        'invited_name',
        'role',
        'expires_at',
        'used_at',
        'used_by',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'expires_at' => 'datetime',
        'used_at' => 'datetime'
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(GovEntity::class, 'gov_entity_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public static function generateToken(): string
    {
        return 'gov_invite_' . Str::random(48);
    }

    public function isValid(): bool
    {
        return $this->used_at === null 
            && $this->expires_at > now();
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function markAsUsed(int $userId): void
    {
        $this->update([
            'used_at' => now(),
            'used_by' => $userId
        ]);
    }
}
