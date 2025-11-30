<?php

namespace App\Services\RTC;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RealTimeCommunicationEngine
{
    public function createSession(int $userId, array $meta = []): array
    {
        $roomId = 'room_' . Str::random(10);
        $id = DB::table('rtc_sessions')->insertGetId([
            'room_id' => $roomId,
            'created_by' => $userId,
            'status' => 'active',
            'started_at' => now(),
            'metadata' => json_encode($meta),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return ['id' => $id, 'room_id' => $roomId];
    }

    public function endSession(string $roomId): bool
    {
        return DB::table('rtc_sessions')->where('room_id', $roomId)
            ->update(['status' => 'ended', 'ended_at' => now(), 'updated_at' => now()]) > 0;
    }
}
