<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Playground - Cultural Translate</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
    <style>
        .response-box {
            font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
            font-size: 13px;
        }
        .dark {
            color-scheme: dark;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div x-data="playground()" class="min-h-screen">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">API Playground</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Test Cultural Translate API endpoints</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="toggleDarkMode()" 
                                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </button>
                        <a href="/" class="text-sm text-primary hover:text-secondary font-medium">← Back to Home</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Sidebar - Endpoints -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Available Endpoints</h2>
                        <div class="space-y-2">
                            <button @click="selectEndpoint('languages')" 
                                    :class="selectedEndpoint === 'languages' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="w-full text-left px-4 py-3 rounded-lg transition-colors">
                                <span class="text-xs font-mono bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">GET</span>
                                <span class="ml-2 font-medium">Languages</span>
                            </button>
                            <button @click="selectEndpoint('translate')" 
                                    :class="selectedEndpoint === 'translate' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="w-full text-left px-4 py-3 rounded-lg transition-colors">
                                <span class="text-xs font-mono bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">POST</span>
                                <span class="ml-2 font-medium">Translate</span>
                            </button>
                            <button @click="selectEndpoint('usage')" 
                                    :class="selectedEndpoint === 'usage' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="w-full text-left px-4 py-3 rounded-lg transition-colors">
                                <span class="text-xs font-mono bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">GET</span>
                                <span class="ml-2 font-medium">Usage Stats</span>
                            </button>
                            <button @click="selectEndpoint('cache-stats')" 
                                    :class="selectedEndpoint === 'cache-stats' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="w-full text-left px-4 py-3 rounded-lg transition-colors">
                                <span class="text-xs font-mono bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">GET</span>
                                <span class="ml-2 font-medium">Cache Stats</span>
                            </button>
                            <button @click="selectEndpoint('rate-limit')" 
                                    :class="selectedEndpoint === 'rate-limit' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="w-full text-left px-4 py-3 rounded-lg transition-colors">
                                <span class="text-xs font-mono bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">GET</span>
                                <span class="ml-2 font-medium">Rate Limit Test</span>
                            </button>
                        </div>

                        <!-- API Key Input -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">API Key</label>
                            <input type="text" 
                                   x-model="apiKey"
                                   placeholder="Enter your API key"
                                   class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Get your API key from the <a href="/dashboard" class="text-primary hover:underline">dashboard</a></p>
                        </div>
                    </div>
                </div>

                <!-- Main Content - Request/Response -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Request Panel -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Request</h2>
                            <button @click="sendRequest()" 
                                    :disabled="loading"
                                    :class="loading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-primary-600'"
                                    class="px-6 py-2 bg-primary text-white rounded-lg font-medium transition-colors">
                                <span x-show="!loading">Send Request</span>
                                <span x-show="loading">Sending...</span>
                            </button>
                        </div>

                        <!-- Endpoint URL -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endpoint</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-sm">
                                    <span x-text="currentMethod" class="font-mono font-bold"></span>
                                </span>
                                <input type="text" 
                                       x-model="currentUrl" 
                                       readonly
                                       class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-r-lg text-gray-900 dark:text-white font-mono text-sm">
                            </div>
                        </div>

                        <!-- Request Body (for POST endpoints) -->
                        <div x-show="currentMethod === 'POST'" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Request Body (JSON)</label>
                            <textarea x-model="requestBody" 
                                      rows="8"
                                      class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder='{"text": "Hello World", "target_language": "ar"}'></textarea>
                        </div>

                        <!-- Headers Preview -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Headers</h3>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 font-mono text-xs text-gray-600 dark:text-gray-400">
                                <div>Accept: application/json</div>
                                <div>Content-Type: application/json</div>
                                <div x-show="apiKey">Authorization: Bearer <span x-text="apiKey.substring(0, 8) + '...'"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Panel -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Response</h2>
                            <div class="flex items-center space-x-3">
                                <span x-show="responseTime > 0" 
                                      class="text-sm text-gray-500 dark:text-gray-400">
                                    <span x-text="responseTime"></span>ms
                                </span>
                                <span x-show="statusCode > 0" 
                                      :class="statusCode >= 200 && statusCode < 300 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'"
                                      class="px-3 py-1 rounded-full text-sm font-medium">
                                    <span x-text="statusCode"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Response Body -->
                        <div x-show="response" class="response-box">
                            <pre class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 overflow-x-auto text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700"><code x-text="response"></code></pre>
                        </div>

                        <!-- Empty State -->
                        <div x-show="!response" class="text-center py-12 text-gray-400 dark:text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm">No response yet. Send a request to see the result.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function playground() {
            return {
                darkMode: localStorage.getItem('darkMode') === 'true',
                selectedEndpoint: 'languages',
                apiKey: 'd232f267-0044-4bec-976b-502745745ffe', // Demo API key
                currentUrl: '/api/sandbox/v1/languages',
                currentMethod: 'GET',
                requestBody: '',
                response: '',
                statusCode: 0,
                responseTime: 0,
                loading: false,

                endpoints: {
                    'languages': {
                        method: 'GET',
                        url: '/api/sandbox/v1/languages',
                        body: '',
                        requiresAuth: false
                    },
                    'translate': {
                        method: 'POST',
                        url: '/api/sandbox/v1/translate',
                        body: JSON.stringify({
                            text: "Hello World",
                            target_language: "ar",
                            tone: "formal"
                        }, null, 2),
                        requiresAuth: true
                    },
                    'usage': {
                        method: 'GET',
                        url: '/api/sandbox/v1/usage',
                        body: '',
                        requiresAuth: true
                    },
                    'cache-stats': {
                        method: 'GET',
                        url: '/api/sandbox/v1/cache/stats',
                        body: '',
                        requiresAuth: true
                    },
                    'rate-limit': {
                        method: 'GET',
                        url: '/api/sandbox/v1/rate-limit/test',
                        body: '',
                        requiresAuth: true
                    }
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                },

                selectEndpoint(endpoint) {
                    this.selectedEndpoint = endpoint;
                    const config = this.endpoints[endpoint];
                    this.currentMethod = config.method;
                    this.currentUrl = config.url;
                    this.requestBody = config.body;
                },

                async sendRequest() {
                    this.loading = true;
                    this.response = '';
                    this.statusCode = 0;
                    this.responseTime = 0;

                    const startTime = performance.now();
                    const config = this.endpoints[this.selectedEndpoint];

                    try {
                        const headers = {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        };

                        if (config.requiresAuth && this.apiKey) {
                            headers['Authorization'] = `Bearer ${this.apiKey}`;
                        }

                        const options = {
                            method: this.currentMethod,
                            headers: headers
                        };

                        if (this.currentMethod === 'POST' && this.requestBody) {
                            options.body = this.requestBody;
                        }

                        const response = await fetch(this.currentUrl, options);
                        const endTime = performance.now();
                        
                        this.statusCode = response.status;
                        this.responseTime = Math.round(endTime - startTime);

                        const data = await response.json();
                        this.response = JSON.stringify(data, null, 2);
                    } catch (error) {
                        this.response = JSON.stringify({
                            error: error.message
                        }, null, 2);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
