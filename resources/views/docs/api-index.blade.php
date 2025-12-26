<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Cultural Translation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">API Documentation</h1>
                    <p class="text-blue-100">Cultural Translation Platform API v1</p>
                </div>
                <a href="{{ url('/') }}" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-semibold">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- API Overview -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to our API</h2>
            <p class="text-gray-700 mb-6">
                The Cultural Translation Platform API provides programmatic access to our translation services. 
                Our RESTful API allows you to integrate professional translation capabilities into your applications.
            </p>
            
            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Base URL:</strong> <code class="bg-white px-2 py-1 rounded">https://culturaltranslate.com/api/v1</code>
                </p>
            </div>
        </div>

        <!-- API Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Partner API -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-600">
                <div class="flex items-center mb-4">
                    <i class="fas fa-handshake text-4xl text-blue-600 mr-4"></i>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Partner API</h3>
                        <p class="text-sm text-gray-600">For registered partners</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6">
                    Access translation services, manage your account, track usage, and earn commissions.
                </p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Text & document translation</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Usage analytics & statistics</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Webhook notifications</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Commission tracking</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ url('/partner-api-swagger.yaml') }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700">
                        <i class="fas fa-book mr-2"></i>View OpenAPI Spec
                    </a>
                    <a href="{{ url('/docs/partners') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200">
                        <i class="fas fa-file-alt mr-2"></i>Partner Documentation
                    </a>
                </div>
            </div>

            <!-- Public API -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-600">
                <div class="flex items-center mb-4">
                    <i class="fas fa-globe text-4xl text-green-600 mr-4"></i>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Public API</h3>
                        <p class="text-sm text-gray-600">For general users</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6">
                    Basic translation services available to all registered users with API access.
                </p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Text translation</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Language detection</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Supported languages list</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-700">Usage statistics</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ url('/dashboard') }}" class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700">
                        <i class="fas fa-tachometer-alt mr-2"></i>User Dashboard
                    </a>
                    <a href="{{ url('/register') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Start Guide</h2>
            
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Get API Key</h3>
                        <p class="text-gray-700 mb-3">Register as a partner or user and generate your API key from the dashboard.</p>
                        <div class="bg-gray-100 p-3 rounded">
                            <p class="text-sm text-gray-600 mb-1">Partner Dashboard:</p>
                            <code class="text-sm">{{ url('/partner/api-keys') }}</code>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Authentication</h3>
                        <p class="text-gray-700 mb-3">Include your API key in the request header:</p>
                        <div class="bg-gray-900 text-gray-100 p-4 rounded font-mono text-sm overflow-x-auto">
                            <span class="text-blue-400">X-API-Key:</span> <span class="text-green-400">pk_your_api_key_here</span>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Make Your First Request</h3>
                        <p class="text-gray-700 mb-3">Example: Translate text</p>
                        <div class="bg-gray-900 text-gray-100 p-4 rounded font-mono text-sm overflow-x-auto">
<pre>curl -X POST https://culturaltranslate.com/api/v1/partner/translate \
  -H "X-API-Key: pk_your_api_key_here" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello World",
    "from": "en",
    "to": "ar"
  }'</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Endpoints -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Endpoints</h2>
            
            <div class="space-y-4">
                <!-- Translation -->
                <div class="border-l-4 border-blue-600 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Translation</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mr-3">POST</span>
                            <code class="text-sm">/api/v1/partner/translate</code>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mr-3">POST</span>
                            <code class="text-sm">/api/v1/partner/documents/translate</code>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-3">GET</span>
                            <code class="text-sm">/api/v1/partner/documents/{id}</code>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="border-l-4 border-purple-600 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Statistics & Analytics</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-3">GET</span>
                            <code class="text-sm">/api/v1/partner/stats</code>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-3">GET</span>
                            <code class="text-sm">/api/v1/partner/stats/detailed</code>
                        </div>
                    </div>
                </div>

                <!-- Webhooks -->
                <div class="border-l-4 border-orange-600 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Webhooks</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-3">GET</span>
                            <code class="text-sm">/api/v1/partner/webhooks</code>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mr-3">POST</span>
                            <code class="text-sm">/api/v1/partner/webhooks</code>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded mr-3">DELETE</span>
                            <code class="text-sm">/api/v1/partner/webhooks/{id}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 text-white">
            <h2 class="text-2xl font-bold mb-6">Additional Resources</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ url('/docs/partners') }}" class="block p-4 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition">
                    <i class="fas fa-book text-2xl mb-2"></i>
                    <h3 class="font-semibold mb-1">Partner Docs</h3>
                    <p class="text-sm text-blue-100">Complete partner guide</p>
                </a>
                
                <a href="{{ url('/partner-api-swagger.yaml') }}" target="_blank" class="block p-4 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition">
                    <i class="fas fa-file-code text-2xl mb-2"></i>
                    <h3 class="font-semibold mb-1">OpenAPI Spec</h3>
                    <p class="text-sm text-blue-100">Download YAML file</p>
                </a>
                
                <a href="mailto:api@culturaltranslate.com" class="block p-4 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition">
                    <i class="fas fa-envelope text-2xl mb-2"></i>
                    <h3 class="font-semibold mb-1">API Support</h3>
                    <p class="text-sm text-blue-100">Get technical help</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-semibold mb-4">Documentation</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ url('/api/v1') }}" class="hover:text-white">API Overview</a></li>
                        <li><a href="{{ url('/docs/partners') }}" class="hover:text-white">Partner Docs</a></li>
                        <li><a href="{{ url('/partner-api-swagger.yaml') }}" class="hover:text-white">OpenAPI Spec</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Partners</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ url('/partner/apply') }}" class="hover:text-white">Become a Partner</a></li>
                        <li><a href="{{ url('/partner/dashboard') }}" class="hover:text-white">Partner Dashboard</a></li>
                        <li><a href="{{ url('/partner/api-keys') }}" class="hover:text-white">API Keys</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-white">User Dashboard</a></li>
                        <li><a href="{{ url('/pricing-plans') }}" class="hover:text-white">Pricing</a></li>
                        <li><a href="{{ url('/support') }}" class="hover:text-white">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><i class="fas fa-envelope mr-2"></i>api@culturaltranslate.com</li>
                        <li><i class="fas fa-envelope mr-2"></i>partners@culturaltranslate.com</li>
                        <li><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
                <p>&copy; 2025 Cultural Translation Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
