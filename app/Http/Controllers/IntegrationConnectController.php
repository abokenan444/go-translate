<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserIntegration;
use App\Models\Integration;
use App\Models\PlatformIntegration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntegrationConnectController extends Controller
{
    /**
     * Connect to an integration
     */
    public function connect(Request $request, $platform)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to connect integrations'
            ], 401);
        }

        // Get integration from database
        $integration = PlatformIntegration::where('slug', $platform)->first();
        
        if (!$integration) {
            return response()->json([
                'success' => false,
                'message' => 'Integration not found'
            ], 404);
        }

        // Check if already connected
        $existingConnection = UserIntegration::where('user_id', $user->id)
            ->where('integration_id', $integration->id)
            ->first();

        if ($existingConnection && $existingConnection->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Already connected to ' . $integration->name
            ]);
        }

        // Handle OAuth integrations
        if ($this->requiresOAuth($platform)) {
            $redirectUrl = $this->getOAuthUrl($platform, $user);
            
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl,
                'message' => 'Redirecting to ' . $integration->name . ' authorization...'
            ]);
        }

        // For non-OAuth integrations, create connection immediately
        $connection = UserIntegration::updateOrCreate(
            [
                'user_id' => $user->id,
                'integration_id' => $integration->id
            ],
            [
                'is_active' => true,
                'settings' => json_encode([]),
                'connected_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Successfully connected to ' . $integration->name
        ]);
    }

    /**
     * Check if integration requires OAuth
     */
    private function requiresOAuth($platform)
    {
        $oauthPlatforms = [
            'shopify', 'slack', 'teams', 'zoom', 'github', 'gitlab',
            'facebook', 'twitter', 'linkedin', 'tiktok',
            'whatsapp', 'telegram', 'discord',
            'contentful', 'strapi'
        ];

        return in_array($platform, $oauthPlatforms);
    }

    /**
     * Get OAuth URL for platform
     */
    private function getOAuthUrl($platform, $user)
    {
        $state = Str::random(40);
        $redirectUri = url("/integrations/oauth/callback/{$platform}");

        // Store state in session for verification
        session(["oauth_state_{$platform}" => $state]);
        session(["oauth_user_id" => $user->id]);

        switch ($platform) {
            case 'shopify':
                $shopDomain = request('shop_domain', 'myshop.myshopify.com');
                return "https://{$shopDomain}/admin/oauth/authorize?" . http_build_query([
                    'client_id' => config('services.shopify.client_id'),
                    'scope' => 'read_products,write_products,read_content,write_content',
                    'redirect_uri' => $redirectUri,
                    'state' => $state
                ]);

            case 'slack':
                return 'https://slack.com/oauth/v2/authorize?' . http_build_query([
                    'client_id' => config('services.slack.client_id'),
                    'scope' => 'chat:write,channels:read,channels:history',
                    'redirect_uri' => $redirectUri,
                    'state' => $state
                ]);

            case 'teams':
                return 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query([
                    'client_id' => config('services.microsoft.client_id'),
                    'response_type' => 'code',
                    'redirect_uri' => $redirectUri,
                    'scope' => 'https://graph.microsoft.com/Chat.ReadWrite',
                    'state' => $state
                ]);

            case 'zoom':
                return 'https://zoom.us/oauth/authorize?' . http_build_query([
                    'client_id' => config('services.zoom.client_id'),
                    'response_type' => 'code',
                    'redirect_uri' => $redirectUri,
                    'state' => $state
                ]);

            case 'github':
                return 'https://github.com/login/oauth/authorize?' . http_build_query([
                    'client_id' => config('services.github.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'repo,read:org',
                    'state' => $state
                ]);

            case 'gitlab':
                return 'https://gitlab.com/oauth/authorize?' . http_build_query([
                    'client_id' => config('services.gitlab.client_id'),
                    'redirect_uri' => $redirectUri,
                    'response_type' => 'code',
                    'scope' => 'api,read_repository',
                    'state' => $state
                ]);

            case 'facebook':
                return 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
                    'client_id' => config('services.facebook.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'pages_manage_posts,pages_read_engagement,instagram_basic,instagram_content_publish',
                    'state' => $state
                ]);

            case 'twitter':
                return 'https://twitter.com/i/oauth2/authorize?' . http_build_query([
                    'client_id' => config('services.twitter.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'tweet.read tweet.write users.read',
                    'state' => $state,
                    'code_challenge' => 'challenge',
                    'code_challenge_method' => 'plain'
                ]);

            case 'linkedin':
                return 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
                    'client_id' => config('services.linkedin.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'w_member_social r_liteprofile',
                    'state' => $state,
                    'response_type' => 'code'
                ]);

            case 'tiktok':
                return 'https://www.tiktok.com/auth/authorize/?' . http_build_query([
                    'client_key' => config('services.tiktok.client_key'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'user.info.basic,video.list,video.upload',
                    'state' => $state,
                    'response_type' => 'code'
                ]);

            case 'whatsapp':
                return 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
                    'client_id' => config('services.whatsapp.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'whatsapp_business_management,whatsapp_business_messaging',
                    'state' => $state
                ]);

            case 'telegram':
                $botUsername = config('services.telegram.bot_username');
                return "https://telegram.me/{$botUsername}?start=" . base64_encode(json_encode([
                    'user_id' => $user->id,
                    'state' => $state
                ]));

            case 'discord':
                return 'https://discord.com/api/oauth2/authorize?' . http_build_query([
                    'client_id' => config('services.discord.client_id'),
                    'redirect_uri' => $redirectUri,
                    'response_type' => 'code',
                    'scope' => 'bot messages.read messages.write',
                    'state' => $state
                ]);

            case 'contentful':
                return 'https://be.contentful.com/oauth/authorize?' . http_build_query([
                    'client_id' => config('services.contentful.client_id'),
                    'redirect_uri' => $redirectUri,
                    'scope' => 'content_management_manage',
                    'state' => $state,
                    'response_type' => 'code'
                ]);

            case 'strapi':
                // Strapi uses custom OAuth, redirect to setup page
                return route('integrations.strapi.setup');

            default:
                return route('integrations');
        }
    }

    /**
     * Disconnect from an integration
     */
    public function disconnect(Request $request, $platform)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login'
            ], 401);
        }

        $integration = PlatformIntegration::where('slug', $platform)->first();
        
        if (!$integration) {
            return response()->json([
                'success' => false,
                'message' => 'Integration not found'
            ], 404);
        }

        $connection = UserIntegration::where('user_id', $user->id)
            ->where('integration_id', $integration->id)
            ->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to this integration'
            ]);
        }

        $connection->update([
            'is_active' => false,
            'disconnected_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully disconnected from ' . $integration->name
        ]);
    }

    /**
     * Get user's connected integrations
     */
    public function getUserIntegrations(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login'
            ], 401);
        }

        $integrations = UserIntegration::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('integration')
            ->get();

        return response()->json([
            'success' => true,
            'integrations' => $integrations
        ]);
    }
}
