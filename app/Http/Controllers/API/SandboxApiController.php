<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SandboxInstance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SandboxApiController extends Controller
{
    /**
     * Create sandbox instance
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'company' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $request->input('email')],
            ['name' => $request->input('company'), 'password' => bcrypt(Str::random(16))]
        );

        // Create sandbox instance
        $sandbox = SandboxInstance::create([
            'user_id' => $user->id,
            'company_name' => $request->input('company'),
            'api_key' => 'ct_' . Str::random(32),
            'api_secret' => Str::random(64),
            'status' => 'active',
            'rate_limit' => 100,
            'expires_at' => now()->addMonths(3),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'api_key' => $sandbox->api_key,
                'api_secret' => $sandbox->api_secret,
                'rate_limit' => $sandbox->rate_limit,
                'expires_at' => $sandbox->expires_at->toIso8601String(),
            ],
            'message' => 'Sandbox instance created successfully. Keep your API secret safe!',
        ], 201);
    }

    /**
     * Get sandbox statistics
     */
    public function stats(Request $request)
    {
        $sandbox = $request->user()->sandboxInstance;

        if (!$sandbox) {
            return response()->json([
                'success' => false,
                'message' => 'No sandbox instance found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'requests_count' => $sandbox->requests_count,
                'rate_limit' => $sandbox->rate_limit,
                'last_request_at' => $sandbox->last_request_at?->toIso8601String(),
                'status' => $sandbox->status,
                'expires_at' => $sandbox->expires_at?->toIso8601String(),
            ],
        ]);
    }
}
