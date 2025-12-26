<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentationController extends Controller
{
    public function partners()
    {
        // Get all active plans from database
        $plans = DB::table('plans')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($plan) {
                // Decode JSON features
                $plan->features = json_decode($plan->features, true);
                return $plan;
            });
        
        return view('docs.partners', compact('plans'));
    }
    
    public function gettingStarted()
    {
        return view('docs.getting-started');
    }
    
    public function apiReference()
    {
        return view('docs.api-reference');
    }
}
