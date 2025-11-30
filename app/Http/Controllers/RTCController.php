<?php

namespace App\Http\Controllers;

use App\Services\RTC\RealTimeCommunicationEngine;
use Illuminate\Http\Request;

class RTCController extends Controller
{
    public function __construct(protected RealTimeCommunicationEngine $rtc) {}

    public function createSession(Request $request)
    {
        $meta = $request->validate(['metadata' => 'array']);
        $start = microtime(true);
        $session = $this->rtc->createSession(\Illuminate\Support\Facades\Auth::id(), $meta['metadata'] ?? []);
        \App\Services\Monitoring\MonitoringService::increment('sessions_total');
        $elapsedMs = (microtime(true) - $start) * 1000;
        \App\Services\Monitoring\MonitoringService::observeLatency('session_create_latency', $elapsedMs);
        app(\App\Services\Scaling\AutoScaleService::class)->recordSessionCreated();
        return response()->json(['success' => true, 'data' => $session]);
    }

    public function endSession(Request $request)
    {
        $data = $request->validate(['room_id' => 'required|string']);
        $ok = $this->rtc->endSession($data['room_id']);
        if ($ok) { app(\App\Services\Scaling\AutoScaleService::class)->recordSessionEnded(); }
        return response()->json(['success' => $ok]);
    }
}
