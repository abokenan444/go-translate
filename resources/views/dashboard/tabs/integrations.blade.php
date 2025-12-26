<div x-show="currentTab === 'integrations'" x-transition>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Connected Integrations') }}</h2>
                <p class="text-gray-600 mt-1">{{ __('Manage your connected platforms and services') }}</p>
            </div>
            <a href="/integrations" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>{{ __('Add New Integration') }}
            </a>
        </div>

        <!-- Connected Integrations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="integrationsManager()">
            
            <!-- Slack Integration -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-slack text-2xl text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Slack</h3>
                            <p class="text-sm text-gray-500">Real-time translation in channels</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full" x-show="integrations.slack">
                        {{ __('Connected') }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full" x-show="!integrations.slack">
                        {{ __('Not Connected') }}
                    </span>
                </div>
                
                <div class="mt-4 space-y-2" x-show="integrations.slack">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>{{ __('Connected on') }}: <span x-text="integrations.slack?.connected_at"></span></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        <span>{{ __('Status') }}: <span x-text="integrations.slack?.status"></span></span>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <button @click="disconnectIntegration('slack')" x-show="integrations.slack" class="flex-1 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-unlink mr-2"></i>{{ __('Disconnect') }}
                    </button>
                    <a href="/integrations" x-show="!integrations.slack" class="flex-1 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-center">
                        <i class="fas fa-link mr-2"></i>{{ __('Connect') }}
                    </a>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- Microsoft Teams Integration -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-microsoft text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Microsoft Teams</h3>
                            <p class="text-sm text-gray-500">Translation in Teams chats</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full" x-show="integrations.teams">
                        {{ __('Connected') }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full" x-show="!integrations.teams">
                        {{ __('Not Connected') }}
                    </span>
                </div>
                
                <div class="mt-4 space-y-2" x-show="integrations.teams">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>{{ __('Connected on') }}: <span x-text="integrations.teams?.connected_at"></span></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        <span>{{ __('Status') }}: <span x-text="integrations.teams?.status"></span></span>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <button @click="disconnectIntegration('teams')" x-show="integrations.teams" class="flex-1 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-unlink mr-2"></i>{{ __('Disconnect') }}
                    </button>
                    <a href="/integrations" x-show="!integrations.teams" class="flex-1 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-center">
                        <i class="fas fa-link mr-2"></i>{{ __('Connect') }}
                    </a>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- Zoom Integration -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-video text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Zoom</h3>
                            <p class="text-sm text-gray-500">Live meeting translation</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full" x-show="integrations.zoom">
                        {{ __('Connected') }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full" x-show="!integrations.zoom">
                        {{ __('Not Connected') }}
                    </span>
                </div>
                
                <div class="mt-4 space-y-2" x-show="integrations.zoom">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>{{ __('Connected on') }}: <span x-text="integrations.zoom?.connected_at"></span></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        <span>{{ __('Status') }}: <span x-text="integrations.zoom?.status"></span></span>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <button @click="disconnectIntegration('zoom')" x-show="integrations.zoom" class="flex-1 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-unlink mr-2"></i>{{ __('Disconnect') }}
                    </button>
                    <a href="/integrations" x-show="!integrations.zoom" class="flex-1 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-center">
                        <i class="fas fa-link mr-2"></i>{{ __('Connect') }}
                    </a>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- GitLab Integration -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-gitlab text-2xl text-orange-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">GitLab</h3>
                            <p class="text-sm text-gray-500">CI/CD translation automation</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full" x-show="integrations.gitlab">
                        {{ __('Connected') }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full" x-show="!integrations.gitlab">
                        {{ __('Not Connected') }}
                    </span>
                </div>
                
                <div class="mt-4 space-y-2" x-show="integrations.gitlab">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>{{ __('Connected on') }}: <span x-text="integrations.gitlab?.connected_at"></span></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        <span>{{ __('Status') }}: <span x-text="integrations.gitlab?.status"></span></span>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <button @click="disconnectIntegration('gitlab')" x-show="integrations.gitlab" class="flex-1 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-unlink mr-2"></i>{{ __('Disconnect') }}
                    </button>
                    <a href="/integrations" x-show="!integrations.gitlab" class="flex-1 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-center">
                        <i class="fas fa-link mr-2"></i>{{ __('Connect') }}
                    </a>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Integration Stats -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">{{ __('Integration Statistics') }}</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="text-3xl font-bold" x-data="integrationsManager()" x-text="Object.keys(integrations).filter(k => integrations[k]).length">0</div>
                    <div class="text-indigo-100">{{ __('Active Integrations') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">4</div>
                    <div class="text-indigo-100">{{ __('Available Platforms') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">∞</div>
                    <div class="text-indigo-100">{{ __('Translation Requests') }}</div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function integrationsManager() {
    return {
        integrations: {
            slack: null,
            teams: null,
            zoom: null,
            gitlab: null
        },
        
        init() {
            this.loadIntegrations();
        },
        
        async loadIntegrations() {
            try {
                const response = await fetch('/api/integrations', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    console.error('Failed to load integrations: HTTP', response.status);
                    return;
                }
                
                const data = await response.json();
                
                // Map integrations by platform
                if (data.integrations) {
                    data.integrations.forEach(integration => {
                        this.integrations[integration.platform] = {
                            connected_at: new Date(integration.connected_at).toLocaleDateString(),
                            status: integration.status
                        };
                    });
                }
            } catch (error) {
                console.error('Failed to load integrations:', error);
            }
        },
        
        async disconnectIntegration(platform) {
            if (!confirm(`Are you sure you want to disconnect ${platform}?`)) {
                return;
            }
            
            try {
                const response = await fetch(`/integrations/disconnect/${platform}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.integrations[platform] = null;
                    alert(`${platform} disconnected successfully!`);
                } else {
                    alert('Failed to disconnect: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Disconnect error:', error);
                alert('Failed to disconnect. Please try again.');
            }
        }
    }
}
</script>
