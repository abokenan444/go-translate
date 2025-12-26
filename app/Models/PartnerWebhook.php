<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerWebhook extends Model
{
    protected $fillable = [
        'partner_id',
        'url',
        'events',
        'secret',
        'is_active',
        'failure_count',
        'last_triggered_at',
        'last_success_at',
        'last_failure_at',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'last_success_at' => 'datetime',
        'last_failure_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function logs()
    {
        return $this->hasMany(PartnerWebhookLog::class, 'webhook_id');
    }

    public function hasEvent(string $event): bool
    {
        return in_array($event, $this->events ?? []);
    }
}
