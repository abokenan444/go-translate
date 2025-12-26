<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SandboxWebhookLog extends Model
{
    protected $fillable = [
        'sandbox_instance_id',
        'webhook_endpoint_id',
        'event_type',
        'payload',
        'delivered_at',
        'delivery_status',
        'response_code',
        'response_body',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'delivered_at' => 'datetime',
    ];

    public function sandboxInstance(): BelongsTo
    {
        return $this->belongsTo(SandboxInstance::class);
    }

    public function webhookEndpoint(): BelongsTo
    {
        return $this->belongsTo(SandboxWebhookEndpoint::class, 'webhook_endpoint_id');
    }

    public function scopeSimulated($query)
    {
        return $query->where('delivery_status', 'simulated');
    }

    public function scopeDelivered($query)
    {
        return $query->where('delivery_status', 'delivered');
    }

    public function scopeFailed($query)
    {
        return $query->where('delivery_status', 'failed');
    }

    public function wasSuccessful(): bool
    {
        return in_array($this->delivery_status, ['simulated', 'delivered']);
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->delivery_status) {
            'simulated' => 'info',
            'delivered' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }
}
