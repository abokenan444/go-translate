<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LiveCallSession extends Model
{
    protected $fillable = [
        'room_id',
        'caller_user_id',
        'callee_user_id',
        'mode',
        'status',
        'caller_send_lang',
        'caller_receive_lang',
        'callee_send_lang',
        'callee_receive_lang',
        'started_at',
        'ended_at',
        'billed_seconds',
        'billing_mode',
        'price_per_minute_snapshot'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (!$model->room_id) {
                $model->room_id = (string) Str::uuid();
            }
        });
    }

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_user_id');
    }

    public function callee()
    {
        return $this->belongsTo(User::class, 'callee_user_id');
    }

    public function usages()
    {
        return $this->hasMany(LiveCallUsage::class, 'session_id');
    }
}
