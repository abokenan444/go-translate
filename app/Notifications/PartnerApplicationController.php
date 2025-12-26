<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PartnerApplicationController extends Controller
{
    public function index()
    {
        return view('partners');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'country' => 'required|string|max:2',
            'company_size' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'partnership_type' => 'required|string',
            'monthly_volume' => 'nullable|string',
            'message' => 'required|string'
        ]);

        // Store in database
        DB::table('partner_applications')->insert([
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
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully'
        ]);
    }
}
