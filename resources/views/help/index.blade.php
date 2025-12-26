@extends("layouts.app")

@section("title", "Help Center - Cultural Translate")

@section("content")
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">How can we help you?</h1>
                <p class="text-xl text-purple-100 max-w-3xl mx-auto mb-8">
                    Search our knowledge base or browse categories below
                </p>
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" placeholder="Search for help..." class="w-full px-6 py-4 rounded-lg text-gray-900 text-lg focus:outline-none focus:ring-4 focus:ring-purple-300">
                        <button class="absolute right-2 top-2 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Getting Started -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Getting Started</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-purple-600 hover:underline">How to create an account</a></li>
                    <li><a href="#" class="text-purple-600 hover:underline">How to upload documents</a></li>
                    <li><a href="#" class="text-purple-600 hover:underline">How to choose a subscription plan</a></li>
                    <li><a href="#" class="text-purple-600 hover:underline">How to integrate with your app</a></li>
                </ul>
            </div>

            <!-- Billing & Pricing -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Billing & Pricing</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-blue-600 hover:underline">Pricing plans explained</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">How to upgrade/downgrade</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Payment methods</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Refund policy</a></li>
                </ul>
            </div>

            <!-- API & Integrations -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">API & Integrations</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-green-600 hover:underline">API documentation</a></li>
                    <li><a href="#" class="text-green-600 hover:underline">Authentication</a></li>
                    <li><a href="#" class="text-green-600 hover:underline">Rate limits</a></li>
                    <li><a href="#" class="text-green-600 hover:underline">Webhooks</a></li>
                </ul>
            </div>

            <!-- Translation Quality -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Translation Quality</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-yellow-600 hover:underline">How we ensure quality</a></li>
                    <li><a href="#" class="text-yellow-600 hover:underline">Supported languages</a></li>
                    <li><a href="#" class="text-yellow-600 hover:underline">Certified translations</a></li>
                    <li><a href="#" class="text-yellow-600 hover:underline">Review process</a></li>
                </ul>
            </div>

            <!-- Account & Security -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Account & Security</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-red-600 hover:underline">Reset password</a></li>
                    <li><a href="#" class="text-red-600 hover:underline">Two-factor authentication</a></li>
                    <li><a href="#" class="text-red-600 hover:underline">Data privacy</a></li>
                    <li><a href="#" class="text-red-600 hover:underline">Delete account</a></li>
                </ul>
            </div>

            <!-- Troubleshooting -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Troubleshooting</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-indigo-600 hover:underline">Translation not working</a></li>
                    <li><a href="#" class="text-indigo-600 hover:underline">Upload errors</a></li>
                    <li><a href="#" class="text-indigo-600 hover:underline">API errors</a></li>
                    <li><a href="#" class="text-indigo-600 hover:underline">Common issues</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">Quick answers to common questions</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <details class="bg-white rounded-lg shadow-md p-6">
                    <summary class="text-lg font-semibold text-gray-900 cursor-pointer">How accurate are the translations?</summary>
                    <p class="mt-4 text-gray-600">Our translations combine AI technology with human review to ensure accuracy rates above 95%. For certified translations, we guarantee 100% accuracy.</p>
                </details>

                <details class="bg-white rounded-lg shadow-md p-6">
                    <summary class="text-lg font-semibold text-gray-900 cursor-pointer">What file formats are supported?</summary>
                    <p class="mt-4 text-gray-600">We support PDF, DOCX, XLSX, PPTX, TXT, and many other formats. You can upload files up to 100MB in size.</p>
                </details>

                <details class="bg-white rounded-lg shadow-md p-6">
                    <summary class="text-lg font-semibold text-gray-900 cursor-pointer">How long does translation take?</summary>
                    <p class="mt-4 text-gray-600">AI translations are instant. Human-reviewed translations typically take 24-48 hours depending on document length and language pair.</p>
                </details>

                <details class="bg-white rounded-lg shadow-md p-6">
                    <summary class="text-lg font-semibold text-gray-900 cursor-pointer">Can I cancel my subscription anytime?</summary>
                    <p class="mt-4 text-gray-600">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.</p>
                </details>

                <details class="bg-white rounded-lg shadow-md p-6">
                    <summary class="text-lg font-semibold text-gray-900 cursor-pointer">Is my data secure?</summary>
                    <p class="mt-4 text-gray-600">Absolutely. We use bank-level encryption (AES-256) and are fully GDPR compliant. Your documents are automatically deleted after 30 days.</p>
                </details>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Still need help?</h2>
            <p class="text-xl text-purple-100 mb-8">Our support team is here to help you 24/7</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route("contact") }}" class="px-8 py-4 bg-white text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition-all">
                    Contact Support
                </a>
                <a href="mailto:support@culturaltranslate.com" class="px-8 py-4 bg-purple-700 text-white rounded-lg font-semibold hover:bg-purple-800 transition-all">
                    Email Us
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
