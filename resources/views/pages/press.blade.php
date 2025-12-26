@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Press Kit & Media Resources') }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ __('Download our brand assets, logos, and media resources') }}
            </p>
        </div>

        <!-- Brand Assets -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Logo Package -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-4">{{ __('Logo Package') }}</h2>
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <img src="{{ asset('images/logo.png') }}" alt="CulturalTranslate Logo" class="mx-auto h-24 mb-4 object-contain">
                        <p class="text-gray-600 mb-2">{{ __('CulturalTranslate Logo') }}</p>
                        <p class="text-sm text-gray-500">{{ __('PNG format - High Resolution') }}</p>
                    </div>
                    <a href="{{ asset('images/logo.png') }}" download="culturaltranslate-logo.png" class="block w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition text-center">
                        <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ __('Download Logo (PNG)') }}
                    </a>
                    <a href="{{ asset('favicon.png') }}" download="culturaltranslate-icon.png" class="block w-full bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition text-center">
                        <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ __('Download Icon/Favicon') }}
                    </a>
                </div>
            </div>

            <!-- Brand Colors -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-4">{{ __('Brand Colors') }}</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-indigo-600 rounded-lg"></div>
                        <div>
                            <p class="font-semibold">{{ __('Primary') }}</p>
                            <p class="text-gray-600">#4F46E5</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-900 rounded-lg"></div>
                        <div>
                            <p class="font-semibold">{{ __('Dark') }}</p>
                            <p class="text-gray-600">#111827</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white border-2 border-gray-300 rounded-lg"></div>
                        <div>
                            <p class="font-semibold">{{ __('White') }}</p>
                            <p class="text-gray-600">#FFFFFF</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Contact -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-4">{{ __('Media Contact') }}</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">{{ __('Press Inquiries') }}</h3>
                    <p class="text-gray-600">press@culturaltranslate.com</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">{{ __('Partnership Inquiries') }}</h3>
                    <p class="text-gray-600">partnerships@culturaltranslate.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
