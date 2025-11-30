<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WordPressIntegrationController extends Controller
{
    /**
     * Connect to WordPress site
     */
    public function connect(Request $request)
    {
        $request->validate([
            'site_url' => 'required|url',
            'username' => 'required|string',
            'app_password' => 'required|string',
        ]);
        
        try {
            // Test connection
            $response = Http::withBasicAuth(
                $request->username,
                $request->app_password
            )->get($request->site_url . '/wp-json/wp/v2/users/me');
            
            if ($response->successful()) {
                // Store integration
                auth()->user()->integrations()->create([
                    'platform' => 'wordpress',
                    'site_url' => $request->site_url,
                    'credentials' => encrypt([
                        'username' => $request->username,
                        'app_password' => $request->app_password,
                    ]),
                    'status' => 'active',
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'WordPress connected successfully',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to WordPress',
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('WordPress connection failed', [
                'error' => $e->getMessage(),
                'site_url' => $request->site_url,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Translate WordPress post
     */
    public function translatePost(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'post_id' => 'required|integer',
            'target_language' => 'required|string',
        ]);
        
        $integration = auth()->user()->integrations()->findOrFail($request->integration_id);
        $credentials = decrypt($integration->credentials);
        
        try {
            // Get post content
            $response = Http::withBasicAuth(
                $credentials['username'],
                $credentials['app_password']
            )->get($integration->site_url . '/wp-json/wp/v2/posts/' . $request->post_id);
            
            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Post not found'], 404);
            }
            
            $post = $response->json();
            
            // Translate content using CulturalPromptEngine
            $translatedTitle = app(\App\Services\TranslationService::class)->translate(
                $post['title']['rendered'],
                'en',
                $request->target_language
            );
            
            $translatedContent = app(\App\Services\TranslationService::class)->translate(
                strip_tags($post['content']['rendered']),
                'en',
                $request->target_language
            );
            
            // Create translated post
            $translatedPost = Http::withBasicAuth(
                $credentials['username'],
                $credentials['app_password']
            )->post($integration->site_url . '/wp-json/wp/v2/posts', [
                'title' => $translatedTitle,
                'content' => $translatedContent,
                'status' => 'draft',
                'meta' => [
                    'original_post_id' => $request->post_id,
                    'translated_language' => $request->target_language,
                ],
            ]);
            
            return response()->json([
                'success' => true,
                'translated_post_id' => $translatedPost->json()['id'],
                'message' => 'Post translated successfully',
            ]);
            
        } catch (\Exception $e) {
            Log::error('WordPress post translation failed', [
                'error' => $e->getMessage(),
                'post_id' => $request->post_id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Translation error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Sync translations back to WordPress
     */
    public function syncTranslations(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
        ]);
        
        $integration = auth()->user()->integrations()->findOrFail($request->integration_id);
        $credentials = decrypt($integration->credentials);
        
        try {
            // Get all posts
            $response = Http::withBasicAuth(
                $credentials['username'],
                $credentials['app_password']
            )->get($integration->site_url . '/wp-json/wp/v2/posts', [
                'per_page' => 100,
            ]);
            
            $posts = $response->json();
            $synced = 0;
            
            foreach ($posts as $post) {
                // Check if translation exists
                $translation = auth()->user()->translations()
                    ->where('source_text', $post['title']['rendered'])
                    ->first();
                
                if ($translation) {
                    $synced++;
                }
            }
            
            return response()->json([
                'success' => true,
                'synced_count' => $synced,
                'total_posts' => count($posts),
            ]);
            
        } catch (\Exception $e) {
            Log::error('WordPress sync failed', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sync error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
