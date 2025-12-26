<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'key_name',
        'api_key',
        'environment',
        'is_active',
        'scopes',
        'rate_limit',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'scopes' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the partner that owns the API key
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Check if key is for sandbox environment
     */
    public function isSandbox(): bool
    {
        return $this->environment === 'sandbox';
    }

    /**
     * Check if key is for production environment
     */
    public function isProduction(): bool
    {
        return $this->environment === 'production';
    }

    /**
     * Check if key has expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Check if key is valid
     */
    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Update last used timestamp
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if key has specific scope
     */
    public function hasScope(string $scope): bool
    {
        return in_array($scope, $this->scopes ?? []);
    }

    /**
     * Get API logs for this key
     */
    public function logs()
    {
        return $this->hasMany(PartnerApiLog::class, 'api_key_id');
    }

    /**
     * Scope for active keys only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sandbox keys only
     */
    public function scopeSandbox($query)
    {
        return $query->where('environment', 'sandbox');
    }

    /**
     * Scope for production keys only
     */
    public function scopeProduction($query)
    {
        return $query->where('environment', 'production');
    }
}
