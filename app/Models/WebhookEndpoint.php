<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEndpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id', 'url', 'events', 'secret', 'active',
    ];

    protected $casts = [
        'events' => 'array',
        'active' => 'boolean',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function logs()
    {
        return $this->hasMany(WebhookLog::class);
    }
}
