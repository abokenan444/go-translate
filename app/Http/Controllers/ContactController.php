<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Notifications\NewContactMessageNotification;
use App\Notifications\ContactMessageReceivedNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        // Rate limiting: 3 requests per 10 minutes per IP
        $key = 'contact-form:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Contact form rate limit exceeded', [
                'ip' => $request->ip(),
                'available_in' => $seconds
            ]);
            
            return back()->withErrors([
                'rate_limit' => 'Too many messages. Please try again in ' . ceil($seconds / 60) . ' minutes.'
            ])->withInput();
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Sanitize inputs
        $validated['name'] = strip_tags($validated['name']);
        $validated['subject'] = strip_tags($validated['subject']);
        $validated['message'] = strip_tags($validated['message']);

        try {
            // Create contact message
            $contact = Contact::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Increment rate limiter
            RateLimiter::hit($key, 600); // 10 minutes

            // Log successful contact
            Log::info('New contact message received', [
                'id' => $contact->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'ip' => $request->ip()
            ]);

            // Send notification to all super admins
            $admins = User::where('role', 'super_admin')->get();
            Notification::send($admins, new NewContactMessageNotification($contact));

            // Send confirmation email to user
            $contact->notify(new ContactMessageReceivedNotification($contact));

            return redirect()->route('contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown'
            ]);

            return back()->withErrors([
                'submission' => 'An error occurred while sending your message. Please try again.'
            ])->withInput();
        }
    }
}
