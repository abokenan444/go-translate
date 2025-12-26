@extends('layouts.app')
@section('title', __('Getting Started'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('Getting Started with CulturalTranslate') }}</h1>
            <p class="text-xl">{{ __('Start translating with cultural intelligence in minutes') }}</p>
        </div>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="space-y-8">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">1</span>
                    <h2 class="text-2xl font-bold">{{ __('Create Your Account') }}</h2>
                </div>
                <p class="text-gray-700 ml-14">{{ __('Sign up for a free account to get started. No credit card required.') }}</p>
                <div class="ml-14 mt-4">
                    <a href="{{ route('register') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">{{ __('Sign Up Free') }}</a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">2</span>
                    <h2 class="text-2xl font-bold">{{ __('Choose Your Plan') }}</h2>
                </div>
                <p class="text-gray-700 ml-14">{{ __('Select a plan that fits your needs. Start with our free tier or upgrade for advanced features.') }}</p>
                <div class="ml-14 mt-4">
                    <a href="{{ url('/pricing-plans') }}" class="inline-block text-indigo-600 hover:underline">{{ __('View Pricing Plans →') }}</a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">3</span>
                    <h2 class="text-2xl font-bold">{{ __('Start Translating') }}</h2>
                </div>
                <p class="text-gray-700 ml-14">{{ __('Use our web interface, API, or integrations to start translating with cultural intelligence.') }}</p>
                <div class="ml-14 mt-4 space-x-4">
                    <a href="{{ route('dashboard') }}" class="inline-block text-indigo-600 hover:underline">{{ __('Web Dashboard →') }}</a>
                    <a href="{{ route('docs.show', 'api') }}" class="inline-block text-indigo-600 hover:underline">{{ __('API Docs →') }}</a>
                </div>
            </div>
        </div>
        
        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-2">{{ __('Need Help?') }}</h3>
            <p class="text-blue-800 text-sm mb-4">{{ __('Our support team is here to help you get started.') }}</p>
            <a href="{{ route('contact') }}" class="text-blue-600 hover:underline font-semibold">{{ __('Contact Support →') }}</a>
        </div>
    </div>
</div>
@endsection
