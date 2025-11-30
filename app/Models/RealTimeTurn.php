<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealTimeTurn extends Model
{
    protected $table = 'realtime_turns';

    protected $fillable = [
        'session_id',
        'user_id',
        'external_id',
        'role',
        'direction',
        'source_text',
        'translated_text',
        'source_language',
        'target_language',
        'raw_stt',
        'raw_llm',
        'raw_tts',
        'audio_path_source',
        'audio_path_translated',
        'latency_ms',
    ];

    protected $casts = [
        'raw_stt' => 'array',
        'raw_llm' => 'array',
        'raw_tts' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(RealTimeSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
