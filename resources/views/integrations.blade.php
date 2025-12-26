@extends('layouts.app')
@section('title', 'Integrations')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">🔌 Integrations</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Connect Cultural Translate with your favorite tools and platforms
            </p>
        </div>

        <!-- Integration Categories -->
        <div class="max-w-6xl mx-auto space-y-12">
            
            <!-- CMS & Website Builders -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📱 CMS & Website Builders</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">WordPress</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Translate your WordPress content automatically</p>
                        <a href="{{ route('integrations.wordpress') }}" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            Configure
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Shopify</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Multilingual e-commerce made easy</p>
                        <a href="{{ route('integrations.shopify') }}" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            Configure
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition opacity-60">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Webflow</h3>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Coming Soon</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Design meets translation</p>
                        <button disabled class="block w-full bg-gray-300 text-gray-500 text-center py-2 rounded-lg cursor-not-allowed">
                            Coming Q2 2025
                        </button>
                    </div>
                </div>
            </div>

            <!-- Productivity Tools -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">⚡ Productivity Tools</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Slack</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Translate messages in real-time</p>
                        <a href="{{ route('integrations.slack') }}" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            Connect
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition opacity-60">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Microsoft Teams</h3>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Beta</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Enterprise team communication</p>
                        <a href="mailto:support@culturaltranslate.com?subject=Teams Beta Access" class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition">
                            Request Beta
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition opacity-60">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Notion</h3>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Coming Soon</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Translate your workspace</p>
                        <button disabled class="block w-full bg-gray-300 text-gray-500 text-center py-2 rounded-lg cursor-not-allowed">
                            Coming Q3 2025
                        </button>
                    </div>
                </div>
            </div>

            <!-- Developer Tools -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">👨‍💻 Developer Tools</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">GitHub</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Translate README and docs automatically</p>
                        <a href="{{ route('integrations.github') }}" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            Connect
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Zapier</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Automate translation workflows</p>
                        <a href="https://zapier.com/apps/cultural-translate/integrations" target="_blank" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View on Zapier
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">REST API</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Build custom integrations</p>
                        <a href="{{ route('docs.api') }}" class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            API Docs
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- CTA -->
        <div class="max-w-4xl mx-auto mt-16 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Need a Custom Integration?</h2>
            <p class="text-lg mb-8 opacity-90">Our team can build custom integrations for enterprise clients</p>
            <a href="mailto:enterprise@culturaltranslate.com" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Contact Enterprise Team
            </a>
        </div>
    </div>
</div>
@endsection
