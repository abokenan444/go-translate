<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        return view('government.index');
    }

    public function submitDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
        ]);

        // TODO: Process government document

        return redirect()->route('government.success')
            ->with('success', 'Document submitted successfully');
    }
}
