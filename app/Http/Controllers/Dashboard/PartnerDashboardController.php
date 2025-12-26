<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->partnerProfile ?? null;
        return view('dashboard.partner.index', compact('user', 'profile'));
    }
}
