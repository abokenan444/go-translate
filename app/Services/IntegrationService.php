<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IntegrationService
{
    /**
     * Check if Slack integration is configured
     */
    public function isSlackConfigured(): bool
    {
        return !empty(config('services.slack.notifications.bot_user_oauth_token'));
    }

    /**
     * Check if Teams integration is configured
     */
    public function isTeamsConfigured(): bool
    {
        $clientId = config('services.teams.client_id');
        return $clientId && $clientId !== 'your_teams_client_id_here';
    }

    /**
     * Check if Zoom integration is configured
     */
    public function isZoomConfigured(): bool
    {
        $clientId = config('services.zoom.client_id');
        return $clientId && $clientId !== 'your_zoom_client_id_here';
    }

    /**
     * Check if GitLab integration is configured
     */
    public function isGitLabConfigured(): bool
    {
        return !empty(env('GITLAB_CLIENT_ID')) && 
               env('GITLAB_CLIENT_ID') !== 'your_gitlab_client_id_here';
    }

    /**
     * Test Slack connection
     */
    public function testSlack(): array
    {
        if (!$this->isSlackConfigured()) {
            return ['success' => false, 'message' => 'Slack not configured'];
        }

        try {
            $token = config('services.slack.notifications.bot_user_oauth_token');
            $response = Http::withToken($token)
                ->post('https://slack.com/api/auth.test');

            return [
                'success' => $response->json('ok', false),
                'message' => $response->json('error', 'Connection successful'),
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get all integration statuses
     */
    public function getIntegrationStatuses(): array
    {
        return [
            'slack' => [
                'name' => 'Slack',
                'configured' => $this->isSlackConfigured(),
                'icon' => '💬',
            ],
            'teams' => [
                'name' => 'Microsoft Teams',
                'configured' => $this->isTeamsConfigured(),
                'icon' => '👥',
            ],
            'zoom' => [
                'name' => 'Zoom',
                'configured' => $this->isZoomConfigured(),
                'icon' => '📹',
            ],
            'gitlab' => [
                'name' => 'GitLab',
                'configured' => $this->isGitLabConfigured(),
                'icon' => '🦊',
            ],
            'stripe' => [
                'name' => 'Stripe',
                'configured' => !empty(config('services.stripe.secret')),
                'icon' => '💳',
            ],
            'openai' => [
                'name' => 'OpenAI',
                'configured' => !empty(config('openai.api_key')),
                'icon' => '🤖',
            ],
        ];
    }
}
