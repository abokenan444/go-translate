<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficialDocument;

class GovernmentPortalController extends Controller
{
    public function index()
    {
        return view('government.index');
    }
    
    public function dashboard()
    {
        $recentDocuments = OfficialDocument::with('user')
            ->latest()
            ->take(10)
            ->get();
            
        $stats = [
            'total_documents' => OfficialDocument::count(),
            'pending' => OfficialDocument::where('status', 'pending')->count(),
            'in_progress' => OfficialDocument::where('status', 'in_progress')->count(),
            'completed' => OfficialDocument::where('status', 'completed')->count(),
        ];
        
        // Government profile data (can be customized based on auth)
        $governmentProfile = [
            'name' => 'Government Portal',
            'role' => 'Administrator',
            'department' => 'Official Documents',
            'access_level' => 'Full Access'
        ];
        
        return view('government.dashboard', compact('stats', 'recentDocuments', 'governmentProfile'));
    }
    
    public function documents()
    {
        $documents = OfficialDocument::with('user')
            ->latest()
            ->paginate(20);
            
        return view('government.documents', compact('documents'));
    }
    
    public function show($id)
    {
        $document = OfficialDocument::with('user')->findOrFail($id);
        
        return view('government.show', compact('document'));
    }
}
