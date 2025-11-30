<?php

namespace App\Events;

use App\Models\RealTimeTurn;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RealTimeTurnCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(
        public RealTimeTurn $turn
    ) {
        // يمكن تقليص البيانات عند الحاجة
        $this->turn->makeHidden(['raw_stt', 'raw_llm', 'raw_tts']);
    }

    public function broadcastOn(): Channel
    {
        // قناة عامة على مستوى الجلسة
        $session = $this->turn->session;
        return new Channel('realtime.sessions.' . $session->public_id);
    }

    public function broadcastAs(): string
    {
        return 'realtime.turn.created';
    }

    public function broadcastWith(): array
    {
        return [
            'turn' => [
                'id' => $this->turn->id,
                'direction' => $this->turn->direction,
                'source_text' => $this->turn->source_text,
                'translated_text' => $this->turn->translated_text,
                'latency_ms' => $this->turn->latency_ms,
                'created_at' => $this->turn->created_at?->toIso8601String(),
            ],
        ];
    }
}
