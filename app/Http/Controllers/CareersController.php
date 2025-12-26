<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CareersController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::open()
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $departments = JobPosting::open()
            ->distinct()
            ->pluck('department')
            ->filter()
            ->values();
        
        $locations = JobPosting::open()
            ->distinct()
            ->pluck('location')
            ->values();
        
        return view('careers.index', compact('jobs', 'departments', 'locations'));
    }

    public function show(JobPosting $job)
    {
        if (!$job->isOpen()) {
            abort(404);
        }
        
        $job->incrementViews();
        
        $relatedJobs = JobPosting::open()
            ->where('id', '!=', $job->id)
            ->where(function ($query) use ($job) {
                $query->where('department', $job->department)
                      ->orWhere('location', $job->location);
            })
            ->limit(3)
            ->get();
        
        return view('careers.show', compact('job', 'relatedJobs'));
    }

    public function apply(Request $request, JobPosting $job)
    {
        if (!$job->isOpen()) {
            return back()->with('error', 'This position is no longer accepting applications.');
        }
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'linkedin_url' => 'nullable|url|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'cover_letter' => 'nullable|string|max:5000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'additional_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'current_position' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric|min:0',
            'notice_period' => 'nullable|string|max:100',
            'additional_info' => 'nullable|string|max:2000',
        ]);
        
        // Store resume
        $resumePath = $request->file('resume')->store('job-applications/resumes', 'private');
        $resumeOriginalName = $request->file('resume')->getClientOriginalName();
        
        // Store additional documents
        $additionalDocs = [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $file) {
                $path = $file->store('job-applications/documents', 'private');
                $additionalDocs[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }
        
        // Create application
        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'resume_original_name' => $resumeOriginalName,
            'additional_documents' => $additionalDocs ?: null,
            'years_of_experience' => $validated['years_of_experience'] ?? null,
            'current_position' => $validated['current_position'] ?? null,
            'current_company' => $validated['current_company'] ?? null,
            'expected_salary' => $validated['expected_salary'] ?? null,
            'notice_period' => $validated['notice_period'] ?? null,
            'additional_info' => $validated['additional_info'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'pending',
        ]);
        
        // Increment applications count
        $job->incrementApplications();
        
        // TODO: Send notification email to admins
        // TODO: Send confirmation email to applicant
        
        return redirect()->route('careers.index')
            ->with('success', 'Your application has been submitted successfully! We will review it and contact you soon.');
    }

    public function downloadResume(JobApplication $application)
    {
        if (!Storage::exists($application->resume_path)) {
            abort(404);
        }
        
        return Storage::download(
            $application->resume_path,
            $application->resume_original_name
        );
    }
}
