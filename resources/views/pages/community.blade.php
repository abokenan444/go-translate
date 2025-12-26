@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Community') }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ __('Join our global community of translators and developers') }}
            </p>
        </div>

        <!-- Community Platforms -->
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <!-- Discord -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <svg class="mx-auto h-16 w-16 text-indigo-600 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"/>
                </svg>
                <h3 class="text-xl font-bold mb-2">{{ __('Discord Server') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('Chat with developers and users') }}</p>
                <a href="#" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    {{ __('Join Discord') }}
                </a>
            </div>

            <!-- GitHub -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <svg class="mx-auto h-16 w-16 text-gray-900 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
                <h3 class="text-xl font-bold mb-2">{{ __('GitHub Discussions') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('Contribute to our open source projects') }}</p>
                <a href="#" class="inline-block bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                    {{ __('View on GitHub') }}
                </a>
            </div>

            <!-- Forum -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <svg class="mx-auto h-16 w-16 text-indigo-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <h3 class="text-xl font-bold mb-2">{{ __('Community Forum') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('Ask questions and share knowledge') }}</p>
                <a href="#" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    {{ __('Visit Forum') }}
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-8">{{ __('Community Stats') }}</h2>
            <div class="grid md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">10K+</div>
                    <div class="text-gray-600">{{ __('Active Users') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">50+</div>
                    <div class="text-gray-600">{{ __('Countries') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">100M+</div>
                    <div class="text-gray-600">{{ __('Characters Translated') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">15+</div>
                    <div class="text-gray-600">{{ __('Languages Supported') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
