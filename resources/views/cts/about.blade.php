@extends('layouts.app')

@section('title', __('About CTS™ - Cultural Translation Standard'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('CTS™ - Cultural Translation Standard') }}</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    {{ __('Building the Standard for Certified Cultural Communication') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- What is CTS Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('What is CTS™?') }}</h2>
            <div class="prose prose-lg max-w-none text-gray-700">
                <p>
                    {{ __('CTS™ (Cultural Translation Standard) is a comprehensive framework developed by CulturalTranslate to ensure the highest quality in cross-cultural communication. Unlike traditional translation services that focus solely on linguistic accuracy, CTS™ evaluates and certifies translations based on cultural intelligence, contextual appropriateness, and audience sensitivity.') }}
                </p>
                <p class="mt-4">
                    {{ __('Our standard has been developed in collaboration with linguists, cultural experts, and international organizations to create a unified benchmark for culturally-aware translation services worldwide.') }}
                </p>
            </div>
        </div>

        <!-- Key Principles -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Core Principles of CTS™') }}</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-indigo-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('1. Linguistic Integrity') }}</h3>
                    <p class="text-gray-700">{{ __('Ensuring grammatical accuracy, terminology consistency, and stylistic appropriateness in the target language.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('2. Cultural Adaptation') }}</h3>
                    <p class="text-gray-700">{{ __('Adapting content to align with cultural norms, values, and expectations of the target audience.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-pink-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('3. Sensitivity Awareness') }}</h3>
                    <p class="text-gray-700">{{ __('Identifying and addressing religious, political, and social sensitivities to avoid misunderstandings or offense.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('4. Legal Compliance') }}</h3>
                    <p class="text-gray-700">{{ __('Ensuring translations meet legal requirements and industry-specific regulations in the target market.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('5. Audience Tolerance') }}</h3>
                    <p class="text-gray-700">{{ __('Evaluating content appropriateness for different audience segments and age groups.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-600">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('6. Quality Assurance') }}</h3>
                    <p class="text-gray-700">{{ __('Multi-layer review process including human expert validation and AI-powered quality checks.') }}</p>
                </div>
            </div>
        </div>

        <!-- Certification Levels -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('CTS™ Certification Levels') }}</h2>
            <div class="space-y-6">
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-lg border border-green-300">
                    <div class="flex items-center mb-3">
                        <span class="bg-green-600 text-white px-4 py-1 rounded-full text-sm font-semibold mr-3">{{ __('Level 1') }}</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('CTS™ Basic') }}</h3>
                    </div>
                    <p class="text-gray-700">{{ __('Linguistic accuracy and basic cultural awareness. Suitable for general content and internal communications.') }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-300">
                    <div class="flex items-center mb-3">
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold mr-3">{{ __('Level 2') }}</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('CTS™ Professional') }}</h3>
                    </div>
                    <p class="text-gray-700">{{ __('Advanced cultural adaptation and sensitivity screening. Ideal for marketing materials and customer-facing content.') }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-300">
                    <div class="flex items-center mb-3">
                        <span class="bg-purple-600 text-white px-4 py-1 rounded-full text-sm font-semibold mr-3">{{ __('Level 3') }}</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('CTS™ Certified') }}</h3>
                    </div>
                    <p class="text-gray-700">{{ __('Full compliance with all CTS™ standards including legal review. Required for official documents and government submissions.') }}</p>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Why Choose CTS™ Certified Translations?') }}</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('Global Recognition') }}</h3>
                    <p class="text-gray-600">{{ __('Accepted by governments and international organizations') }}</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('Quality Assurance') }}</h3>
                    <p class="text-gray-600">{{ __('Multi-layer validation process ensures highest accuracy') }}</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('Cultural Intelligence') }}</h3>
                    <p class="text-gray-600">{{ __('Beyond translation - true cross-cultural communication') }}</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">{{ __('Get Your Content CTS™ Certified') }}</h2>
            <p class="text-xl text-indigo-100 mb-8">{{ __('Join thousands of organizations trusting CTS™ for their global communication needs') }}</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    {{ __('Get Started') }}
                </a>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
