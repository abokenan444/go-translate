<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:ios,android,web',
            'token' => 'required|string',
            'device_id' => 'nullable|string',
        ]);

        DeviceToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id' => $request->user()->id,
                'platform' => $request->platform,
                'device_id' => $request->device_id ? hash('sha256', $request->device_id) : null,
                'last_seen_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function unregister(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        
        DeviceToken::where('token', $request->token)
            ->where('user_id', $request->user()->id)
            ->delete();
            
        return response()->json(['ok' => true]);
    }
}
