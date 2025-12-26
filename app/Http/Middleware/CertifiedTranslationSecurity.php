<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CertifiedTranslationSecurity
{
    /**
     * Handle an incoming request for certified translation
     */
    public function handle(Request $request, Closure $next)
    {
        // Rate limiting check
        $this->checkRateLimit($request);
        
        // File upload security
        if ($request->hasFile('document')) {
            $this->validateFileUpload($request->file('document'));
        }
        
        // Add security headers
        $response = $next($request);
        
        return $this->addSecurityHeaders($response);
    }
    
    /**
     * Check rate limiting
     */
    private function checkRateLimit(Request $request)
    {
        $key = 'certified_upload_' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 10) {
            abort(429, 'Too many requests. Please try again later.');
        }
        
        cache()->put($key, $attempts + 1, now()->addMinutes(60));
    }
    
    /**
     * Validate file upload
     */
    private function validateFileUpload($file)
    {
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            abort(413, 'File size exceeds 10MB limit');
        }
        
        // Check MIME type
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            abort(415, 'Invalid file type. Only PDF, JPG, and PNG are allowed');
        }
        
        // Check file extension
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            abort(415, 'Invalid file extension');
        }
        
        // Additional security: Check for malicious content
        $this->scanForMaliciousContent($file);
    }
    
    /**
     * Scan for malicious content
     */
    private function scanForMaliciousContent($file)
    {
        // Check for executable code in filename
        $filename = $file->getClientOriginalName();
        $dangerousPatterns = ['<?php', '<?=', '<script', 'javascript:', 'eval('];
        
        foreach ($dangerousPatterns as $pattern) {
            if (stripos($filename, $pattern) !== false) {
                abort(400, 'Suspicious filename detected');
            }
        }
        
        // For PDF files, check for JavaScript
        if ($file->getMimeType() === 'application/pdf') {
            $content = file_get_contents($file->getRealPath());
            if (stripos($content, '/JavaScript') !== false || stripos($content, '/JS') !== false) {
                \Log::warning('PDF with JavaScript detected', ['filename' => $filename]);
            }
        }
    }
    
    /**
     * Add security headers
     */
    private function addSecurityHeaders($response)
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
