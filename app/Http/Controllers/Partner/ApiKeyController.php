<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiKeyController extends Controller
{
    /**
     * Create a new API key for the partner.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $user = Auth::user();

        // Check if user is a partner
        if (!$user->hasRole('partner')) {
            return response()->json([
                'success' => false,
                'message' => 'Only partners can create API keys.',
            ], 403);
        }

        // Generate API key
        $apiKey = 'ct_' . Str::random(40);
        $hashedKey = Hash::make($apiKey);

        // Save to database (placeholder - create PartnerApiKey model)
        // $key = PartnerApiKey::create([
        //     'user_id' => $user->id,
        //     'name' => $request->name,
        //     'key' => $hashedKey,
        //     'permissions' => $request->permissions ?? [],
        //     'last_used_at' => null,
        //     'expires_at' => now()->addYear(),
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'API key created successfully!',
            'api_key' => $apiKey, // Only show once
            'key_id' => 1, // Replace with: $key->id
            'name' => $request->name,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Revoke an API key.
     */
    public function revoke(Request $request)
    {
        $request->validate([
            'key_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Find and revoke key (placeholder - implement with PartnerApiKey model)
        // $key = PartnerApiKey::where('id', $request->key_id)
        //     ->where('user_id', $user->id)
        //     ->firstOrFail();
        // 
        // $key->update([
        //     'revoked_at' => now(),
        //     'is_active' => false,
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'API key revoked successfully!',
        ]);
    }

    /**
     * List all API keys for the partner.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all keys (placeholder - implement with PartnerApiKey model)
        // $keys = PartnerApiKey::where('user_id', $user->id)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        $keys = collect([]); // Placeholder

        return view('partner.api-keys.index', compact('keys'));
    }

    /**
     * Regenerate an API key.
     */
    public function regenerate(Request $request)
    {
        $request->validate([
            'key_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Find key (placeholder - implement with PartnerApiKey model)
        // $key = PartnerApiKey::where('id', $request->key_id)
        //     ->where('user_id', $user->id)
        //     ->firstOrFail();
        // 
        // // Generate new API key
        // $newApiKey = 'ct_' . Str::random(40);
        // $hashedKey = Hash::make($newApiKey);
        // 
        // $key->update([
        //     'key' => $hashedKey,
        //     'revoked_at' => null,
        //     'is_active' => true,
        // ]);

        $newApiKey = 'ct_' . Str::random(40); // Placeholder

        return response()->json([
            'success' => true,
            'message' => 'API key regenerated successfully!',
            'api_key' => $newApiKey, // Only show once
        ]);
    }

    /**
     * Get API key usage statistics.
     */
    public function stats($keyId)
    {
        $user = Auth::user();

        // Get stats (placeholder - implement with PartnerApiKey model)
        // $key = PartnerApiKey::where('id', $keyId)
        //     ->where('user_id', $user->id)
        //     ->firstOrFail();
        // 
        // $stats = [
        //     'total_requests' => $key->total_requests ?? 0,
        //     'requests_today' => $key->requests_today ?? 0,
        //     'last_used_at' => $key->last_used_at,
        //     'created_at' => $key->created_at,
        // ];

        $stats = [
            'total_requests' => 0,
            'requests_today' => 0,
            'last_used_at' => null,
            'created_at' => now(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}
