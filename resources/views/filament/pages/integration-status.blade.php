<div>
    <x-filament::section>
        <x-slot name="heading">
            Integration Status
        </x-slot>

        <x-slot name="description">
            View the status of all external integrations
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($integrations as $key => $integration)
                <div class="border rounded-lg p-4 {{ $integration['configured'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">{{ $integration['icon'] }}</span>
                            <h3 class="font-semibold text-gray-900">{{ $integration['name'] }}</h3>
                        </div>
                        @if($integration['configured'])
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                ✓ Configured
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-200 rounded-full">
                                Not Configured
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600">
                        @if($integration['configured'])
                            Integration is active and ready to use
                        @else
                            Configure in .env to enable
                        @endif
                    </p>

                    @if($key === 'slack' && $integration['configured'])
                        <button 
                            wire:click="testSlack" 
                            class="mt-3 text-sm text-blue-600 hover:text-blue-800"
                        >
                            Test Connection →
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Configuration Guide
        </x-slot>

        <div class="prose prose-sm max-w-none">
            <h4>To configure integrations, add these to your .env file:</h4>
            
            <pre class="bg-gray-100 p-4 rounded text-xs overflow-x-auto"><code># Slack Integration
SLACK_CLIENT_ID=your_slack_client_id
SLACK_CLIENT_SECRET=your_slack_client_secret
SLACK_BOT_USER_OAUTH_TOKEN=xoxb-your-token

# Microsoft Teams
TEAMS_CLIENT_ID=your_teams_client_id
TEAMS_CLIENT_SECRET=your_teams_client_secret

# Zoom
ZOOM_CLIENT_ID=your_zoom_client_id
ZOOM_CLIENT_SECRET=your_zoom_client_secret

# GitLab
GITLAB_CLIENT_ID=your_gitlab_client_id
GITLAB_CLIENT_SECRET=your_gitlab_client_secret</code></pre>
        </div>
    </x-filament::section>
</div>
