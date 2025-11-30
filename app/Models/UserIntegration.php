<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIntegration extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_integrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'platform',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'status',
        'metadata',
        'connected_at',
        'last_used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'connected_at' => 'datetime',
        'last_used_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the user that owns the integration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the integration is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the token is expired.
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Mark the integration as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Deactivate the integration.
     */
    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Activate the integration.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Mark the integration as having an error.
     */
    public function markAsError(): void
    {
        $this->update(['status' => 'error']);
    }

    /**
     * Scope a query to only include active integrations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include integrations for a specific platform.
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Get the decrypted access token.
     */
    public function getDecryptedAccessToken(): ?string
    {
        if (!$this->access_token) {
            return null;
        }

        try {
            return decrypt($this->access_token);
        } catch (\Exception $e) {
            \Log::error("Failed to decrypt access token for integration {$this->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the decrypted refresh token.
     */
    public function getDecryptedRefreshToken(): ?string
    {
        if (!$this->refresh_token) {
            return null;
        }

        try {
            return decrypt($this->refresh_token);
        } catch (\Exception $e) {
            \Log::error("Failed to decrypt refresh token for integration {$this->id}: " . $e->getMessage());
            return null;
        }
    }
}
