<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('realtime.sessions.{publicId}', function ($user, string $publicId) {
    // يمكنك وضع صلاحيات: مثلاً السماح فقط لصاحب الجلسة أو أعضاء فريقه
    return ['id' => $user->id ?? null];
});
