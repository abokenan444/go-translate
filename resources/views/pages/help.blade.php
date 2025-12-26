@extends('layouts.app')

@section('title', 'Help Center - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.help_center') }}</h1>
                <p class="text-xl mb-8 opacity-90">{{ __('messages.help_subtitle') }}</p>
                
                <!-- Search Box -->
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" 
                           placeholder="{{ __('messages.search_help') }}"
                           class="w-full px-6 py-4 rounded-lg text-gray-900 text-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
                    <button class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        {{ __('messages.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Getting Started</h3>
                <p class="text-gray-600 mb-4">Learn the basics of using CulturalTranslate</p>
                <a href="/guides" class="text-indigo-600 font-semibold hover:text-indigo-700">View Guides →</a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Contact Support</h3>
                <p class="text-gray-600 mb-4">Get help from our support team</p>
                <a href="/support" class="text-purple-600 font-semibold hover:text-purple-700">Contact Us →</a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">API Documentation</h3>
                <p class="text-gray-600 mb-4">Integrate CulturalTranslate into your apps</p>
                <a href="/api-docs" class="text-green-600 font-semibold hover:text-green-700">View Docs →</a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">How do I get started with CulturalTranslate?</h3>
                    <p class="text-gray-600">Simply sign up for a free account, choose your plan, and start translating. Our intuitive interface makes it easy to get started in minutes.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">What languages are supported?</h3>
                    <p class="text-gray-600">We support 13 major languages including English, Arabic, Spanish, French, German, Italian, Portuguese, Russian, Chinese, Japanese, Korean, Hindi, and Turkish.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">How accurate are the translations?</h3>
                    <p class="text-gray-600">Our AI-powered translations achieve 95-99% accuracy depending on your plan. We use GPT-4 and GPT-4-turbo for premium plans to ensure the highest quality.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">Can I cancel my subscription anytime?</h3>
                    <p class="text-gray-600">Yes, you can cancel your subscription at any time from your account settings. No questions asked.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">Do you offer API access?</h3>
                    <p class="text-gray-600">Yes, all paid plans include API access. Check our API documentation for integration details.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-2">Is my data secure?</h3>
                    <p class="text-gray-600">Absolutely. We use enterprise-grade encryption and follow SOC 2 compliance standards to protect your data.</p>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="mt-16 text-center">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-12 text-white">
                <h2 class="text-3xl font-bold mb-4">Still have questions?</h2>
                <p class="text-xl mb-8 opacity-90">Our support team is here to help you 24/7</p>
                <a href="/support" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
