<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    public function getUser(Request $request)
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
                'plan' => $user->subscription_plan ?? 'Free Plan',
                'created_at' => $user->created_at->format('Y-m-d'),
            ]
        ]);
    }

    public function getStats(Request $request)
    {
        $user = Auth::user();
        
        // Get user's translation statistics
        $stats = [
            'translations' => 1234, // Replace with actual count
            'translationsGrowth' => 12,
            'charactersUsed' => 45200,
            'charactersLimit' => 100000,
            'projects' => 8,
            'activeProjects' => 3,
            'teamMembers' => 5,
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function getUsageData(Request $request)
    {
        // Mock usage data for chart
        $usageData = [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'data' => [5000, 12000, 8000, 20000]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $usageData
        ]);
    }

    public function getLanguagesData(Request $request)
    {
        // Mock languages distribution
        $languagesData = [
            'labels' => ['Arabic', 'English', 'Spanish', 'French', 'German'],
            'data' => [35, 25, 20, 12, 8]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $languagesData
        ]);
    }

    public function getHistory(Request $request)
    {
        // Mock translation history
        $history = [
            [
                'id' => 1,
                'source_lang' => 'en',
                'target_lang' => 'ar',
                'source_text' => 'Hello World',
                'translated_text' => 'مرحبا بالعالم',
                'created_at' => now()->subDays(1)->format('Y-m-d H:i'),
            ],
            [
                'id' => 2,
                'source_lang' => 'ar',
                'target_lang' => 'en',
                'source_text' => 'شكراً لك',
                'translated_text' => 'Thank you',
                'created_at' => now()->subDays(2)->format('Y-m-d H:i'),
            ],
        ];
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function getProjects(Request $request)
    {
        // Mock projects
        $projects = [
            [
                'id' => 1,
                'name' => 'Website Translation',
                'description' => 'Main website content',
                'status' => 'active',
                'created_at' => now()->subDays(10)->format('Y-m-d'),
            ],
            [
                'id' => 2,
                'name' => 'Marketing Campaign',
                'description' => 'Q4 marketing materials',
                'status' => 'active',
                'created_at' => now()->subDays(5)->format('Y-m-d'),
            ],
        ];
        
        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function getSubscription(Request $request)
    {
        $user = Auth::user();
        
        $subscription = [
            'plan' => $user->subscription_plan ?? 'Free',
            'status' => 'active',
            'characters_used' => 45200,
            'characters_limit' => 100000,
            'renewal_date' => now()->addDays(15)->format('Y-m-d'),
            'amount' => 0,
        ];
        
        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }
}
