<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class PartnerApplicationController extends Controller
{
    public function index()
    {
        return view('partners');
    }

    public function store(Request $request)
    {
        // Rate limiting: 3 requests per 10 minutes per IP
        $key = 'partner-application:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Partner application rate limit exceeded', [
                'ip' => $request->ip(),
                'available_in' => $seconds
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many applications. Please try again in ' . ceil($seconds / 60) . ' minutes.'
            ], 429);
        }

        // Validate input
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'required|string|max:2',
            'company_size' => 'required|string|in:1-10,11-50,51-200,201-500,500+',
            'contact_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'partnership_type' => 'required|string|in:reseller,technology,agency,affiliate',
            'monthly_volume' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000'
        ]);

        // Sanitize inputs
        $validated['company_name'] = strip_tags($validated['company_name']);
        $validated['contact_name'] = strip_tags($validated['contact_name']);
        $validated['job_title'] = strip_tags($validated['job_title']);
        $validated['message'] = strip_tags($validated['message']);

        try {
            // Store in database
            $applicationId = DB::table('partner_applications')->insertGetId([
                'company_name' => $validated['company_name'],
                'website' => $validated['website'] ?? null,
                'country' => $validated['country'],
                'company_size' => $validated['company_size'],
                'contact_name' => $validated['contact_name'],
                'job_title' => $validated['job_title'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'partnership_type' => $validated['partnership_type'],
                'monthly_volume' => $validated['monthly_volume'] ?? null,
                'message' => $validated['message'],
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'recaptcha_score' => $request->input('recaptcha_score', 0),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Increment rate limiter
            RateLimiter::hit($key, 600); // 10 minutes

            // Log successful application
            Log::info('New partner application received', [
                'id' => $applicationId,
                'company' => $validated['company_name'],
                'email' => $validated['email'],
                'type' => $validated['partnership_type'],
                'ip' => $request->ip(),
                'recaptcha_score' => $request->input('recaptcha_score', 0)
            ]);

            // TODO: Send email notification to admin
            // TODO: Send confirmation email to applicant

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'application_id' => $applicationId
            ]);

        } catch (\Exception $e) {
            Log::error('Partner application failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again.'
            ], 500);
        }
    }
}
