<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class IntegrationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'provider', 'account_id', 'access_token', 'refresh_token', 'expires_at', 'scope', 'meta'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'meta' => 'array',
        'access_token' => 'encrypted',
        'refresh_token' => 'encrypted'
    ];

    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function scopeProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
