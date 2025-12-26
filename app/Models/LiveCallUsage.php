<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveCallUsage extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'seconds_processed',
        'cost_snapshot',
    ];

    public function session()
    {
        return $this->belongsTo(LiveCallSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
