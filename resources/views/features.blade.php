@extends('layouts.app')

@section('title', 'AI Translation Features - Voice, Cultural Context & API | CulturalTranslate')
@section('meta_description', 'Explore advanced AI translation features: voice translation with tone preservation, cultural context analysis, real-time API, document translation, and multi-language support.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">{{ __('messages.features_title') }}</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                {{ __('messages.features_subtitle') }}
            </p>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: AI Voice Translation -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">AI Voice Translation</h3>
                    <p class="text-gray-600 mb-4">
                        Translate voice with cultural tone preservation and emotional context. Perfect for international calls and meetings.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Real-time voice translation</li>
                        <li>• Tone and emotion preservation</li>
                        <li>• 50+ languages supported</li>
                        <li>• High accuracy speech recognition</li>
                    </ul>
                </div>

                <!-- Feature 2: Cultural Context Analysis -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Cultural Context Analysis</h3>
                    <p class="text-gray-600 mb-4">
                        Preserve cultural nuances and context in translations. Adapt idioms and expressions for local audiences.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Cultural adaptation engine</li>
                        <li>• Idiom localization</li>
                        <li>• Tone adjustment</li>
                        <li>• Regional preferences</li>
                    </ul>
                </div>

                <!-- Feature 3: Real-time Translation API -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Real-time Translation API</h3>
                    <p class="text-gray-600 mb-4">
                        RESTful API for seamless integration with your applications. Fast, reliable, and scalable.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• RESTful API endpoints</li>
                        <li>• Webhook support</li>
                        <li>• Multi-language SDKs</li>
                        <li>• 99.9% uptime SLA</li>
                    </ul>
                </div>

                <!-- Feature 4: Document Translation -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Document Translation</h3>
                    <p class="text-gray-600 mb-4">
                        Translate PDFs, Word documents, and presentations while preserving formatting and layout.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• PDF, DOCX, PPTX support</li>
                        <li>• Layout preservation</li>
                        <li>• Batch processing</li>
                        <li>• OCR for scanned documents</li>
                    </ul>
                </div>

                <!-- Feature 5: Team Collaboration -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Team Collaboration</h3>
                    <p class="text-gray-600 mb-4">
                        Work together with your team on translation projects. Share glossaries and translation memories.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Multi-user workspaces</li>
                        <li>• Role-based access control</li>
                        <li>• Shared glossaries</li>
                        <li>• Activity tracking</li>
                    </ul>
                </div>

                <!-- Feature 6: Translation Memory -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Translation Memory</h3>
                    <p class="text-gray-600 mb-4">
                        Save and reuse previous translations for consistency and cost savings across all projects.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Automatic matching</li>
                        <li>• Cost reduction</li>
                        <li>• Consistency guarantee</li>
                        <li>• Import/Export TM</li>
                    </ul>
                </div>

                <!-- Feature 7: Custom Glossaries -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Custom Glossaries</h3>
                    <p class="text-gray-600 mb-4">
                        Define brand-specific terminology and ensure consistent translations across all content.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Brand terminology</li>
                        <li>• Industry-specific terms</li>
                        <li>• Multi-language glossaries</li>
                        <li>• Auto-apply to translations</li>
                    </ul>
                </div>

                <!-- Feature 8: Quality Assurance -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Quality Assurance</h3>
                    <p class="text-gray-600 mb-4">
                        Automated quality checks ensure accurate and consistent translations every time.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Grammar checking</li>
                        <li>• Consistency validation</li>
                        <li>• Terminology verification</li>
                        <li>• Quality scoring</li>
                    </ul>
                </div>

                <!-- Feature 9: Analytics & Insights -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Analytics & Insights</h3>
                    <p class="text-gray-600 mb-4">
                        Track translation performance, costs, and usage with detailed analytics and reports.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Usage statistics</li>
                        <li>• Cost breakdown</li>
                        <li>• Performance metrics</li>
                        <li>• Custom reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Experience the Future of Translation</h2>
            <p class="text-xl text-gray-300 mb-8">
                Start your 14-day free trial today. No credit card required.
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-indigo-700 transition text-lg">
                Get Started Free
            </a>
        </div>
    </section>
</div>
@endsection
