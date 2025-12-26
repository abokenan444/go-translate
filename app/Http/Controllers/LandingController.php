<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Page;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalUsers = 151; // Static - was User::count()
        $totalSubscriptions = 89; // Static - was Subscription::where('status', 'active')->count()
        $totalPages = 30; // Static - was Page::where('is_published', true)->count()
        $totalTranslations = 150000; // Static
        $totalCompanies = 4; // Static
        
        return view('landing', compact('totalUsers', 'totalSubscriptions', 'totalPages', 'totalTranslations', 'totalCompanies'));
    }
}
