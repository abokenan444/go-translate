<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestUploadController extends Controller
{
    public function showForm()
    {
        return view('test-upload');
    }

    public function handleUpload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:30720', // 30MB in KB
            ]);

            $file = $request->file('file');
            
            // Get file information
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
            $mimeType = $file->getMimeType();
            
            // Store the file
            $path = $file->store('test-uploads', 'public');
            
            Log::info('File uploaded successfully', [
                'name' => $fileName,
                'size' => $fileSizeMB . ' MB',
                'type' => $mimeType,
                'path' => $path,
            ]);

            return back()->with('success', "✅ تم رفع الملف بنجاح! الاسم: {$fileName} | الحجم: {$fileSizeMB} ميجابايت");
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors(['file' => 'حجم الملف أكبر من الحد المسموح (30 ميجابايت)']);
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors(['file' => 'فشل رفع الملف: ' . $e->getMessage()]);
        }
    }
}
