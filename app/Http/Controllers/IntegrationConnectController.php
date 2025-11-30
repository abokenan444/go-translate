<?php

namespace App\Http\Controllers;

use App\Models\UserIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntegrationConnectController extends Controller
{
    public function connect(Request $request, $platform)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Please login to connect integrations',
                'redirect' => '/login'
            ], 401);
        }
        
        $user = auth()->user();
        
        // Supported platforms
        $platforms = ['slack', 'teams', 'zoom', 'gitlab'];
        
        if (!in_array($platform, $platforms)) {
            return response()->json(['error' => 'Unsupported platform'], 400);
        }
        
        // Generate OAuth URL based on platform
        $oauthUrl = $this->getOAuthUrl($platform);
        
        return response()->json([
            'success' => true,
            'oauth_url' => $oauthUrl,
            'platform' => $platform
        ]);
    }
    
    public function callback(Request $request, $platform)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect('/dashboard')->with('error', 'Integration failed: No authorization code received');
        }
        
        // Exchange code for access token
        $accessToken = $this->exchangeCodeForToken($platform, $code);
        
        if (!$accessToken) {
            return redirect('/dashboard')->with('error', 'Integration failed: Could not obtain access token');
        }
        
        // Store integration
        $user = auth()->user();
        $user->userIntegrations()->updateOrCreate(
            ['platform' => $platform],
            [
                'access_token' => encrypt($accessToken),
                'status' => 'active',
                'connected_at' => now()
            ]
        );
        
        return redirect('/dashboard')->with('success', ucfirst($platform) . ' connected successfully!');
    }
    
    public function disconnect(Request $request, $platform)
    {
        $user = auth()->user();
        
        $integration = $user->userIntegrations()->where('platform', $platform)->first();
        
        if ($integration) {
            $integration->delete();
            return response()->json(['success' => true, 'message' => 'Integration disconnected']);
        }
        
        return response()->json(['error' => 'Integration not found'], 404);
    }
    
    private function getOAuthUrl($platform)
    {
        $redirectUri = url("/integrations/callback/{$platform}");
        $state = Str::random(40);
        session(["oauth_state_{$platform}" => $state]);
        
        $urls = [
            'slack' => "https://slack.com/oauth/v2/authorize?client_id=" . env('SLACK_CLIENT_ID') . "&scope=chat:write,channels:read&redirect_uri={$redirectUri}&state={$state}",
            'teams' => "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=" . env('TEAMS_CLIENT_ID') . "&response_type=code&redirect_uri={$redirectUri}&scope=Chat.ReadWrite&state={$state}",
            'zoom' => "https://zoom.us/oauth/authorize?response_type=code&client_id=" . env('ZOOM_CLIENT_ID') . "&redirect_uri={$redirectUri}&state={$state}",
            'gitlab' => "https://gitlab.com/oauth/authorize?client_id=" . env('GITLAB_CLIENT_ID') . "&redirect_uri={$redirectUri}&response_type=code&scope=api&state={$state}"
        ];
        
        return $urls[$platform] ?? '#';
    }
    
    private function exchangeCodeForToken($platform, $code)
    {
        $redirectUri = url("/integrations/callback/{$platform}");
        
        try {
            switch ($platform) {
                case 'slack':
                    return $this->exchangeSlackToken($code, $redirectUri);
                case 'teams':
                    return $this->exchangeTeamsToken($code, $redirectUri);
                case 'zoom':
                    return $this->exchangeZoomToken($code, $redirectUri);
                case 'gitlab':
                    return $this->exchangeGitLabToken($code, $redirectUri);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            \Log::error("OAuth token exchange failed for {$platform}: " . $e->getMessage());
            return null;
        }
    }
    
    private function exchangeSlackToken($code, $redirectUri)
    {
        $response = \Http::post('https://slack.com/api/oauth.v2.access', [
            'client_id' => env('SLACK_CLIENT_ID'),
            'client_secret' => env('SLACK_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => $redirectUri
        ]);
        
        $data = $response->json();
        return $data['access_token'] ?? null;
    }
    
    private function exchangeTeamsToken($code, $redirectUri)
    {
        $response = \Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'client_id' => env('TEAMS_CLIENT_ID'),
            'client_secret' => env('TEAMS_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]);
        
        $data = $response->json();
        return $data['access_token'] ?? null;
    }
    
    private function exchangeZoomToken($code, $redirectUri)
    {
        $credentials = base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET'));
        
        $response = \Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]);
        
        $data = $response->json();
        return $data['access_token'] ?? null;
    }
    
    private function exchangeGitLabToken($code, $redirectUri)
    {
        $response = \Http::post('https://gitlab.com/oauth/token', [
            'client_id' => env('GITLAB_CLIENT_ID'),
            'client_secret' => env('GITLAB_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]);
        
        $data = $response->json();
        return $data['access_token'] ?? null;
    }
}
