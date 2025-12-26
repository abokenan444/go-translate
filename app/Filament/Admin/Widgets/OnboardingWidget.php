<?php

namespace App\Filament\Admin\Widgets;

use App\Models\SandboxApiKey;
use App\Models\SandboxInstance;
use App\Models\SandboxWebhookEndpoint;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class OnboardingWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.onboarding-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -10; // Show at the top

    public function getViewData(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return ['steps' => [], 'progress' => 0];
        }

        $hasInstance = SandboxInstance::where('user_id', $user->id)->exists();

        $hasApiKey = SandboxApiKey::whereHas('sandboxInstance', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->exists();

        $hasUsedApi = SandboxApiKey::whereHas('sandboxInstance', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereNotNull('last_used_at')->exists();

        $hasWebhook = SandboxWebhookEndpoint::whereHas('sandboxInstance', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->exists();

        $steps = [
            [
                'label' => 'Create your first Sandbox Instance',
                'completed' => $hasInstance,
                'description' => 'Set up a sandbox environment to test translations.',
                'action' => '#',
                'action_label' => 'Create Instance',
                'icon' => 'heroicon-o-server',
            ],
            [
                'label' => 'Generate an API Key',
                'completed' => $hasApiKey,
                'description' => 'Create an API key to authenticate your requests.',
                'action' => '#',
                'action_label' => 'Generate Key',
                'icon' => 'heroicon-o-key',
            ],
            [
                'label' => 'Make your first API Request',
                'completed' => $hasUsedApi,
                'description' => 'Use your API key to translate some text.',
                'action' => '/api-playground', 
                'action_label' => 'Go to Playground',
                'icon' => 'heroicon-o-code-bracket',
            ],
            [
                'label' => 'Configure Webhooks',
                'completed' => $hasWebhook,
                'description' => 'Receive real-time updates for translation events.',
                'action' => '#',
                'action_label' => 'Add Webhook',
                'icon' => 'heroicon-o-bolt',
            ]
        ];

        $completedCount = collect($steps)->where('completed', true)->count();
        $progress = count($steps) > 0 ? round(($completedCount / count($steps)) * 100) : 0;

        return [
            'steps' => $steps,
            'progress' => $progress,
        ];
    }
    
    public static function canView(): bool
    {
        // Hide if completed? Or maybe keep it for reference?
        // Let's keep it for now.
        return true;
    }
}
