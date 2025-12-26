<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $notifications = MobileNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'data' => $n->data,
                'read' => $n->read_at !== null,
                'created_at' => $n->created_at->toIso8601String(),
            ]);

        $unreadCount = MobileNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(MobileNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        MobileNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = MobileNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }

    /**
     * Delete a notification
     */
    public function destroy(MobileNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
