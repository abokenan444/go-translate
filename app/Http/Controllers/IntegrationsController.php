<?php

namespace App\Http\Controllers;

use App\Models\PlatformIntegration;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    public function index()
    {
        // Load all platform integrations from database
        $integrations = PlatformIntegration::orderBy('category')
            ->orderBy('name')
            ->get();
        
        return view('pages.integrations', compact('integrations'));
    }
}
