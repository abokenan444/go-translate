<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'plan_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_price_id',
        'plan_name',
        'status',
        'tokens_limit',
        'tokens_used',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'auto_renewal_enabled',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'auto_renewal_enabled' => 'boolean',
        'tokens_limit' => 'integer',
        'tokens_used' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function onTrial(): bool
    {
        return $this->status === 'trialing' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasTokensAvailable(): bool
    {
        if ($this->tokens_limit === -1) {
            return true; // Unlimited
        }
        return $this->tokens_used < $this->tokens_limit;
    }

    public function incrementTokenUsage(int $tokens): void
    {
        $this->increment('tokens_used', $tokens);
    }

    public function resetTokenUsage(): void
    {
        $this->update(['tokens_used' => 0]);
    }
}
