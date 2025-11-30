<?php

namespace App\Http\Controllers\RealTime;

use App\Http\Controllers\Controller;
use App\Models\RealTimeSession;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuestJoinController extends Controller
{
    /**
     * Show guest join page
     */
    public function show(string $sessionId, Request $request)
    {
        $session = RealTimeSession::where('public_id', $sessionId)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return view('realtime.guest-join', [
                'session' => null,
            ]);
        }

        // Generate QR code for easy mobile joining
        $joinUrl = route('realtime.guest.join', $sessionId);
        $qrCode = QrCode::size(200)
            ->margin(1)
            ->generate($joinUrl);

        return view('realtime.guest-join', [
            'session' => $session,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Generate guest access token
     */
    public function generateToken(string $sessionId, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $session = RealTimeSession::where('public_id', $sessionId)
            ->where('status', 'active')
            ->firstOrFail();

        // Generate unique guest token
        $guestToken = 'guest_' . time() . '_' . bin2hex(random_bytes(8));

        // Store guest info in session metadata
        $guestInfo = [
            'token' => $guestToken,
            'name' => $request->name,
            'email' => $request->email,
            'joined_at' => now()->toIso8601String(),
        ];

        // Add to session metadata
        $metadata = $session->metadata ?? [];
        $metadata['guests'] = $metadata['guests'] ?? [];
        $metadata['guests'][$guestToken] = $guestInfo;
        
        $session->metadata = $metadata;
        $session->save();

        return response()->json([
            'success' => true,
            'token' => $guestToken,
            'redirect_url' => route('realtime.meeting.show', $sessionId) . '?guest=' . $guestToken,
        ]);
    }

    /**
     * Get QR code for session
     */
    public function qrCode(string $sessionId)
    {
        $session = RealTimeSession::where('public_id', $sessionId)
            ->where('status', 'active')
            ->firstOrFail();

        $joinUrl = route('realtime.guest.join', $sessionId);
        
        return QrCode::size(300)
            ->margin(2)
            ->format('png')
            ->generate($joinUrl);
    }

    /**
     * Verify guest token
     */
    public function verifyToken(string $sessionId, string $token)
    {
        $session = RealTimeSession::where('public_id', $sessionId)
            ->where('status', 'active')
            ->firstOrFail();

        $metadata = $session->metadata ?? [];
        $guests = $metadata['guests'] ?? [];

        if (!isset($guests[$token])) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired guest token',
            ], 403);
        }

        return response()->json([
            'valid' => true,
            'guest' => $guests[$token],
        ]);
    }
}
