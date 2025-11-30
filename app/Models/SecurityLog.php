<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attack_type',
        'ip_address',
        'url',
        'input_field',
        'suspicious_value',
        'user_agent',
        'referer',
        'request_method',
        'payload',
        'severity',
        'blocked',
        'notified_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'blocked' => 'boolean',
        'notified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByType($query, $type)
    {
        return $query->where('attack_type', $type);
    }

    public function scopeHighSeverity($query)
    {
        return $query->where('severity', 'high');
    }

    public function scopeBlocked($query)
    {
        return $query->where('blocked', true);
    }
}
