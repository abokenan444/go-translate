<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GovernmentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->governmentProfile ?? null;
        
        // Get government statistics
        $stats = [
            'total_documents' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->count(),
            'pending_documents' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'completed_documents' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'certified_documents' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->where('is_certified', true)
                ->count(),
            'total_pages_translated' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->sum('page_count') ?? 0,
            'this_month_documents' => DB::table('official_documents')
                ->where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        // Get recent official documents
        $recentDocuments = DB::table('official_documents')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get bulk translation stats
        $bulkStats = [
            'total_batches' => DB::table('translation_batches')
                ->where('user_id', $user->id)
                ->count(),
            'processing' => DB::table('translation_batches')
                ->where('user_id', $user->id)
                ->where('status', 'processing')
                ->count(),
        ];
        
        return view('dashboard.government.index', compact('user', 'profile', 'stats', 'recentDocuments', 'bulkStats'));
    }
}
