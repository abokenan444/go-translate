<?php

namespace App\Http\Controllers;

use App\Models\CulturalProfile;
use App\Models\IndustryTemplate;
use App\Models\Translation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load all languages
        $languages = CulturalProfile::orderBy('region')->orderBy('name')->get();
        
        // Load all industries
        $industries = IndustryTemplate::orderBy('name')->get();
        
        // Get user's subscription limits
        $subscription = $user->subscription ?? null;
        $characterLimit = $subscription ? $subscription->character_limit : 100000; // Default 100k
        $projectLimit = $subscription ? $subscription->project_limit : 3;
        $teamLimit = $subscription ? $subscription->team_limit : 10;
        
        // Calculate statistics
        $stats = $this->calculateStats($user, $characterLimit);
        
        // Get usage data for chart (last 4 weeks)
        $usageData = $this->getWeeklyUsage($user);
        
        // Get language distribution
        $languageDistribution = $this->getLanguageDistribution($user);
        
        // Get recent translations
        $recentTranslations = Translation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.app', compact('user', 
            'languages',
            'industries',
            'stats',
            'usageData',
            'languageDistribution',
            'recentTranslations',
            'characterLimit',
            'projectLimit',
            'teamLimit'
        ));
    }
    
    /**
     * Calculate dashboard statistics
     */
    private function calculateStats($user, $characterLimit)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();
        
        // Total translations
        $totalTranslations = Translation::where('user_id', $user->id)->count();
        
        // Translations this month
        $translationsThisMonth = Translation::where('user_id', $user->id)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        
        // Translations last month
        $translationsLastMonth = Translation::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();
        
        // Calculate growth percentage
        $translationGrowth = 0;
        if ($translationsLastMonth > 0) {
            $translationGrowth = (($translationsThisMonth - $translationsLastMonth) / $translationsLastMonth) * 100;
        } elseif ($translationsThisMonth > 0) {
            $translationGrowth = 100;
        }
        
        // Total characters used
        $totalCharacters = Translation::where('user_id', $user->id)
            ->sum(DB::raw('LENGTH(source_text)'));
        
        // Characters this month
        $charactersThisMonth = Translation::where('user_id', $user->id)
            ->where('created_at', '>=', $startOfMonth)
            ->sum(DB::raw('LENGTH(source_text)'));
        
        // Active projects
        $activeProjects = 0;
        if (class_exists('App\\Models\\Project')) {
            $activeProjects = Project::where('user_id', $user->id)
                ->where('status', 'active')
                ->count();
        }
        
        // Team members (if applicable)
        $teamMembers = 1; // Default: just the user
        if ($user->company_id) {
            $teamMembers = DB::table('users')
                ->where('company_id', $user->company_id)
                ->count();
        }
        
        return [
            'total_translations' => $totalTranslations,
            'translations_this_month' => $translationsThisMonth,
            'translation_growth' => round($translationGrowth, 1),
            'total_characters' => $totalCharacters,
            'characters_this_month' => $charactersThisMonth,
            'character_limit' => $characterLimit,
            'active_projects' => $activeProjects,
            'team_members' => $teamMembers,
        ];
    }
    
    /**
     * Get weekly usage data for chart
     */
    private function getWeeklyUsage($user)
    {
        $weeks = [];
        $data = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $characters = Translation::where('user_id', $user->id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum(DB::raw('LENGTH(source_text)'));
            
            $weeks[] = 'Week ' . (4 - $i);
            $data[] = $characters;
        }
        
        return [
            'labels' => $weeks,
            'data' => $data,
        ];
    }
    
    /**
     * Get language distribution for pie chart
     */
    private function getLanguageDistribution($user)
    {
        $distribution = Translation::where('user_id', $user->id)
            ->select('target_language', DB::raw('count(*) as count'))
            ->groupBy('target_language')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [
            '#3B82F6', // Blue
            '#8B5CF6', // Purple
            '#10B981', // Green
            '#F59E0B', // Orange
            '#6B7280', // Gray
        ];
        
        foreach ($distribution as $index => $item) {
            $labels[] = strtoupper($item->target_language);
            $data[] = $item->count;
        }
        
        // If less than 5 languages, fill with "Others"
        if (count($labels) < 5 && count($labels) > 0) {
            $labels[] = 'Others';
            $data[] = 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels)),
        ];
    }
}
