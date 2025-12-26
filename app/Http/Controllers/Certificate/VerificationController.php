<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function showForm()
    {
        return view('certificates.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:16'
        ]);

        $certificate = Certificate::where('verification_code', strtoupper($request->code))
            ->first();

        if (!$certificate) {
            return back()->with('error', 'Certificate not found');
        }

        return view('certificates.result', compact('certificate'));
    }

    public function download($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        // TODO: Generate PDF certificate
        
        return response()->download(storage_path('certificates/' . $certificate->certificate_number . '.pdf'));
    }
}
