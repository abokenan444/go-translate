@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('What\'s New') }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ __('Latest updates and improvements to CulturalTranslate') }}
            </p>
        </div>

        <!-- Changelog Entries -->
        <div class="space-y-8">
            <!-- Version 2.5.0 -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">v2.5.0</h2>
                    <span class="text-gray-500">December 5, 2024</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-green-600 mb-2">✨ {{ __('New Features') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Real-time voice translation with AI') }}</li>
                            <li>{{ __('Enhanced Cultural AI Engine') }}</li>
                            <li>{{ __('New affiliate program') }}</li>
                            <li>{{ __('Advanced brand voice customization') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-600 mb-2">🔧 {{ __('Improvements') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Faster translation processing (50% improvement)') }}</li>
                            <li>{{ __('Better PDF handling for complex layouts') }}</li>
                            <li>{{ __('Improved dashboard UI/UX') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-purple-600 mb-2">🐛 {{ __('Bug Fixes') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Fixed API rate limiting issues') }}</li>
                            <li>{{ __('Resolved webhook delivery failures') }}</li>
                            <li>{{ __('Fixed character encoding in Arabic translations') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Version 2.4.5 -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">v2.4.5</h2>
                    <span class="text-gray-500">November 20, 2024</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-blue-600 mb-2">🔧 {{ __('Improvements') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Enhanced security headers') }}</li>
                            <li>{{ __('Optimized database queries') }}</li>
                            <li>{{ __('Updated API documentation') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-purple-600 mb-2">🐛 {{ __('Bug Fixes') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Fixed session timeout issues') }}</li>
                            <li>{{ __('Resolved CORS errors in API') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Version 2.4.0 -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">v2.4.0</h2>
                    <span class="text-gray-500">November 1, 2024</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-green-600 mb-2">✨ {{ __('New Features') }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>{{ __('Glossary management system') }}</li>
                            <li>{{ __('Team collaboration features') }}</li>
                            <li>{{ __('Custom webhooks') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscribe to Updates -->
        <div class="mt-12 bg-indigo-600 rounded-lg shadow-lg p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">{{ __('Stay Updated') }}</h2>
            <p class="mb-6">{{ __('Get notified about new features and updates') }}</p>
            <div class="max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="{{ __('Your email') }}" class="flex-1 px-4 py-2 rounded-lg text-gray-900">
                <button class="bg-white text-indigo-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    {{ __('Subscribe') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
