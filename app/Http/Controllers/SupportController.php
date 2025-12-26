<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    /**
     * Show support page
     */
    public function index()
    {
        return view('support');
    }
    
    /**
     * Submit support request
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cert_number' => 'nullable|string|max:50',
            'issue_type' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);
        
        // Log support request
        Log::channel('support')->info('Support Request Received', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cert_number' => $validated['cert_number'] ?? 'N/A',
            'issue_type' => $validated['issue_type'],
            'message' => $validated['message'],
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
        ]);
        
        // Send email notification (optional)
        try {
            Mail::raw(
                "Support Request\n\n" .
                "Name: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Certificate: {$validated['cert_number']}\n" .
                "Issue: {$validated['issue_type']}\n\n" .
                "Message:\n{$validated['message']}",
                function ($message) use ($validated) {
                    $message->to(config('mail.support_email', 'support@culturaltranslate.com'))
                        ->subject('Certified Translation Support Request')
                        ->replyTo($validated['email']);
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send support email', ['error' => $e->getMessage()]);
        }
        
        return back()->with('success', 'Your support request has been submitted. We will contact you soon.');
    }
}
