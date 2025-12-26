@extends('layouts.app')

@section('title', 'Developers - Cultural Translate API')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">Cultural Translate API</h1>
            <p class="text-xl mb-8 text-indigo-100">
                Integrate culturally-aware translation into your applications with our powerful REST API
            </p>
            <div class="flex gap-4 justify-center">
                <a href="#sandbox" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                    Get API Key
                </a>
                <a href="#documentation" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                    View Documentation
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Our API?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Lightning Fast</h3>
                <p class="text-gray-600">Average response time under 200ms with global CDN distribution</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Secure & Reliable</h3>
                <p class="text-gray-600">99.9% uptime SLA with enterprise-grade security</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Cultural Awareness</h3>
                <p class="text-gray-600">AI-powered cultural adaptation for authentic translations</p>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start -->
<section id="documentation" class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Quick Start</h2>
        
        <div class="max-w-4xl mx-auto">
            <!-- Step 1 -->
            <div class="mb-8">
                <h3 class="text-2xl font-semibold mb-4">1. Get Your API Key</h3>
                <p class="text-gray-600 mb-4">Sign up for a free sandbox account to get your API credentials:</p>
                <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm">
                    curl -X POST https://api.culturaltranslate.com/v1/sandbox \<br>
                    &nbsp;&nbsp;-H "Content-Type: application/json" \<br>
                    &nbsp;&nbsp;-d '{"email": "your@email.com", "company": "Your Company"}'
                </div>
            </div>

            <!-- Step 2 -->
            <div class="mb-8">
                <h3 class="text-2xl font-semibold mb-4">2. Make Your First Request</h3>
                <p class="text-gray-600 mb-4">Translate text with cultural adaptation:</p>
                <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                    curl -X POST https://api.culturaltranslate.com/v1/translate \<br>
                    &nbsp;&nbsp;-H "Authorization: Bearer YOUR_API_KEY" \<br>
                    &nbsp;&nbsp;-H "Content-Type: application/json" \<br>
                    &nbsp;&nbsp;-d '{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"text": "Hello, how are you?",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"source_language": "en",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"target_language": "ar",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"context": "formal",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"apply_glossary": true<br>
                    &nbsp;&nbsp;}'
                </div>
            </div>

            <!-- Step 3 -->
            <div class="mb-8">
                <h3 class="text-2xl font-semibold mb-4">3. Handle the Response</h3>
                <p class="text-gray-600 mb-4">The API returns JSON with the translated text and metadata:</p>
                <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                    {<br>
                    &nbsp;&nbsp;"success": true,<br>
                    &nbsp;&nbsp;"data": {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"translated_text": "مرحباً، كيف حالك؟",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"source_language": "en",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"target_language": "ar",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"cultural_adaptations": ["formal_greeting"],<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"glossary_matches": 0,<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"translation_memory_used": false<br>
                    &nbsp;&nbsp;},<br>
                    &nbsp;&nbsp;"meta": {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"request_id": "req_abc123",<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;"processing_time_ms": 145<br>
                    &nbsp;&nbsp;}<br>
                    }
                </div>
            </div>
        </div>
    </div>
</section>

<!-- API Endpoints -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">API Endpoints</h2>
        
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Translate Endpoint -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm font-semibold">POST</span>
                    <span class="font-mono text-gray-700">/v1/translate</span>
                </div>
                <p class="text-gray-600 mb-4">Translate text with cultural adaptation and glossary application</p>
                <details class="text-sm">
                    <summary class="cursor-pointer text-indigo-600 font-semibold">View Parameters</summary>
                    <div class="mt-4 bg-gray-50 p-4 rounded">
                        <ul class="space-y-2">
                            <li><code class="bg-gray-200 px-2 py-1 rounded">text</code> (required) - Text to translate</li>
                            <li><code class="bg-gray-200 px-2 py-1 rounded">source_language</code> (required) - Source language code</li>
                            <li><code class="bg-gray-200 px-2 py-1 rounded">target_language</code> (required) - Target language code</li>
                            <li><code class="bg-gray-200 px-2 py-1 rounded">context</code> (optional) - Context type (formal, casual, marketing, legal)</li>
                            <li><code class="bg-gray-200 px-2 py-1 rounded">apply_glossary</code> (optional) - Apply user glossary (default: true)</li>
                            <li><code class="bg-gray-200 px-2 py-1 rounded">use_translation_memory</code> (optional) - Use translation memory (default: true)</li>
                        </ul>
                    </div>
                </details>
            </div>

            <!-- Batch Translate -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm font-semibold">POST</span>
                    <span class="font-mono text-gray-700">/v1/translate/batch</span>
                </div>
                <p class="text-gray-600">Translate multiple texts in a single request</p>
            </div>

            <!-- Glossary Management -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-mono text-sm font-semibold">GET</span>
                    <span class="font-mono text-gray-700">/v1/glossary</span>
                </div>
                <p class="text-gray-600">Retrieve your glossary terms</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm font-semibold">POST</span>
                    <span class="font-mono text-gray-700">/v1/glossary</span>
                </div>
                <p class="text-gray-600">Add new glossary terms</p>
            </div>

            <!-- Translation Memory -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-mono text-sm font-semibold">GET</span>
                    <span class="font-mono text-gray-700">/v1/translation-memory</span>
                </div>
                <p class="text-gray-600">Search translation memory for matches</p>
            </div>
        </div>
    </div>
</section>

<!-- SDKs & Libraries -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">SDKs & Libraries</h2>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">PHP</h3>
                <code class="text-sm bg-gray-100 px-3 py-1 rounded">composer require culturaltranslate/sdk</code>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">JavaScript</h3>
                <code class="text-sm bg-gray-100 px-3 py-1 rounded">npm install @culturaltranslate/sdk</code>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Python</h3>
                <code class="text-sm bg-gray-100 px-3 py-1 rounded">pip install culturaltranslate</code>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl mb-8 text-indigo-100">Create your free sandbox account and start integrating today</p>
        <a href="#sandbox" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition inline-block">
            Get Your API Key
        </a>
    </div>
</section>
@endsection
