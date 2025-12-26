<?php

namespace App\Http\Controllers;

use App\Models\SandboxInstance;
use App\Models\SandboxPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SandboxPreviewController extends Controller
{
    public function show(Request $request, ?string $subdomain = null, ?string $path = null)
    {
        // Try to get instance from middleware (subdomain-based) or route parameter
        $instance = $request->attributes->get('sandboxInstance');
        
        if (!$instance && $subdomain) {
            // Fallback: load instance by subdomain parameter (works without wildcard DNS)
            $instance = DB::table('sandbox_instances')
                ->where('subdomain', $subdomain)
                ->where('status', 'active')
                ->first();
            
            if (!$instance) {
                abort(404, 'Sandbox not found or inactive');
            }
        }

        if (!$instance) {
            abort(404, 'Sandbox instance not found');
        }

        // Determine the requested path
        $requestedPath = $path ? '/' . ltrim($path, '/') : '/';
        
        // Find the page
        $page = DB::table('sandbox_pages')
            ->where('sandbox_instance_id', $instance->id)
            ->where('path', $requestedPath)
            ->first();

        if (!$page) {
            // Fallback to homepage
            $page = DB::table('sandbox_pages')
                ->where('sandbox_instance_id', $instance->id)
                ->where('path', '/')
                ->first();
        }

        if (!$page) {
            abort(404, 'Sandbox page not found');
        }

        return view('sandbox.preview', [
            'instance' => $instance,
            'page' => $page,
        ]);
    }
}
