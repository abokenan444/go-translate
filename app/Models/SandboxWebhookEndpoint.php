<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SandboxWebhookEndpoint extends Model
{
    protected $fillable = [
        'sandbox_instance_id',
        'url',
        'description',
        'secret',
        'events',
        'is_simulation_only',
        'is_active',
    ];

    protected $casts = [
        'events' => 'array',
        'is_simulation_only' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'secret',
    ];

    public function sandboxInstance(): BelongsTo
    {
        return $this->belongsTo(SandboxInstance::class);
    }

    public function webhookLogs(): HasMany
    {
        return $this->hasMany(SandboxWebhookLog::class, 'webhook_endpoint_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSimulationOnly($query)
    {
        return $query->where('is_simulation_only', true);
    }

    public function subscribesToEvent(string $event): bool
    {
        return in_array($event, $this->events ?? []) || in_array('*', $this->events ?? []);
    }

    public function generateSignature(array $payload): string
    {
        return hash_hmac('sha256', json_encode($payload), $this->secret);
    }
}
