<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GovernmentRequest;
use Illuminate\Support\Facades\Storage;

class GovernmentController extends Controller
{
    public function index()
    {
        return view('government.index');
    }
    
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'document_type' => 'required|string',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);
        
        // رفع الملف
        $path = $request->file('document')->store('government-documents', 'public');
        
        // حفظ في قاعدة البيانات
        $govRequest = GovernmentRequest::create([
            'department' => $validated['department'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'document_type' => $validated['document_type'],
            'source_language' => $validated['source_language'],
            'target_language' => $validated['target_language'],
            'document_path' => $path,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->back()->with('success', 'Your request has been submitted successfully. Reference ID: ' . $govRequest->id);
    }
}
