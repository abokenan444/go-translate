<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubIntegrationController extends Controller
{
    /**
     * Connect to GitHub
     */
    public function connect(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);
        
        try {
            // Test connection
            $response = Http::withToken($request->access_token)
                ->get('https://api.github.com/user');
            
            if ($response->successful()) {
                $user = $response->json();
                
                // Store integration
                auth()->user()->integrations()->create([
                    'platform' => 'github',
                    'site_url' => 'https://github.com/' . $user['login'],
                    'credentials' => encrypt([
                        'access_token' => $request->access_token,
                        'username' => $user['login'],
                    ]),
                    'status' => 'active',
                    'metadata' => [
                        'user_id' => $user['id'],
                        'avatar_url' => $user['avatar_url'],
                    ],
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'GitHub connected successfully',
                    'user' => $user,
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to GitHub',
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('GitHub connection failed', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Translate README file
     */
    public function translateReadme(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'repo' => 'required|string',
            'target_language' => 'required|string',
        ]);
        
        $integration = auth()->user()->integrations()->findOrFail($request->integration_id);
        $credentials = decrypt($integration->credentials);
        
        try {
            // Get README content
            $response = Http::withToken($credentials['access_token'])
                ->get("https://api.github.com/repos/{$credentials['username']}/{$request->repo}/readme");
            
            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'README not found'], 404);
            }
            
            $readme = $response->json();
            $content = base64_decode($readme['content']);
            
            // Translate content
            $translatedContent = app(\App\Services\TranslationService::class)->translate(
                $content,
                'en',
                $request->target_language
            );
            
            // Create translated README
            $fileName = 'README.' . strtoupper($request->target_language) . '.md';
            
            $createFile = Http::withToken($credentials['access_token'])
                ->put("https://api.github.com/repos/{$credentials['username']}/{$request->repo}/contents/{$fileName}", [
                    'message' => "Add {$request->target_language} translation of README",
                    'content' => base64_encode($translatedContent),
                ]);
            
            return response()->json([
                'success' => true,
                'file_name' => $fileName,
                'message' => 'README translated successfully',
            ]);
            
        } catch (\Exception $e) {
            Log::error('GitHub README translation failed', [
                'error' => $e->getMessage(),
                'repo' => $request->repo,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Translation error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * List repositories
     */
    public function listRepositories(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
        ]);
        
        $integration = auth()->user()->integrations()->findOrFail($request->integration_id);
        $credentials = decrypt($integration->credentials);
        
        try {
            $response = Http::withToken($credentials['access_token'])
                ->get('https://api.github.com/user/repos', [
                    'per_page' => 100,
                    'sort' => 'updated',
                ]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'repositories' => $response->json(),
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch repositories',
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('GitHub repositories fetch failed', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
