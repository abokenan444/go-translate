<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\LiveCallSession;

Broadcast::channel('realtime.sessions.{publicId}', function ($user, string $publicId) {
    // يمكنك وضع صلاحيات: مثلاً السماح فقط لصاحب الجلسة أو أعضاء فريقه
    return ['id' => $user->id ?? null];
});

Broadcast::channel('livecall.{roomId}', function ($user, $roomId) {
    $session = LiveCallSession::where('room_id', $roomId)->first();
    if (!$session) return false;

    return $user->id === $session->caller_user_id || $user->id === $session->callee_user_id;
});
