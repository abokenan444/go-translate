<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerWebhookLog extends Model
{
    protected $fillable = [
        'webhook_id',
        'event_type',
        'payload',
        'response',
        'status_code',
        'response_time',
        'success',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
        'success' => 'boolean',
    ];

    public function webhook()
    {
        return $this->belongsTo(PartnerWebhook::class, 'webhook_id');
    }
}
