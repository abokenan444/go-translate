<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user(),
                'stats' => [
                    'projects' => $request->user()->projects()->count(),
                    'translations' => $request->user()->translations()->count(),
                ],
            ],
        ]);
    }

    public function notifications(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    public function updateDeviceToken(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Device token updated successfully',
        ]);
    }
}
