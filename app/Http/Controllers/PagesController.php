<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Translation;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function aboutUs()
    {
        // سحب الأرقام الحقيقية من قاعدة البيانات
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_translations' => Translation::count(),
            'supported_languages' => 150, // ثابت - عدد اللغات المدعومة
        ];
        
        return view('pages.about', compact('stats'));
    }
    
    public function ctsStandard()
    {
        return view('cts.standard');
    }
    
    public function culturalRiskEngine()
    {
        return view('cts.risk-engine');
    }
    
    public function certificateVerification()
    {
        return view('cts.verification');
    }
    
    public function aboutCts()
    {
        return view('cts.about');
    }
}
