@extends('layouts.app')

@section('title', 'Cultural Risk Engine - AI-Powered Cultural Analysis')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Cultural Risk Engine</h1>
            <p class="text-2xl text-gray-600">AI-Powered Real-Time Cultural Risk Detection</p>
        </div>

        <!-- Introduction -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">What is the Cultural Risk Engine?</h2>
            <p class="text-lg text-gray-700 mb-4">
                Our <strong>AI-powered Cultural Risk Engine</strong> analyzes translations in real-time to detect potential cultural sensitivities, inappropriate content, and cross-cultural communication risks before they become problems.
            </p>
            <p class="text-lg text-gray-700">
                Built on advanced machine learning models trained on millions of cross-cultural interactions, our engine identifies subtle cultural nuances that traditional translation tools miss, protecting your brand reputation and ensuring culturally appropriate communication.
            </p>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Key Features</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="border-l-4 border-purple-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">⚡ Real-Time Analysis</h3>
                    <p class="text-gray-700">Instant cultural sensitivity analysis as you translate, with immediate alerts for potential issues.</p>
                </div>

                <div class="border-l-4 border-pink-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🎯 Risk Scoring</h3>
                    <p class="text-gray-700">Automatic risk scoring (Low, Medium, High) with detailed explanations and recommendations.</p>
                </div>

                <div class="border-l-4 border-red-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🌍 Multi-Cultural Perspective</h3>
                    <p class="text-gray-700">Evaluation from multiple cultural perspectives simultaneously to catch issues across different audiences.</p>
                </div>

                <div class="border-l-4 border-orange-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">📊 Context-Aware Recommendations</h3>
                    <p class="text-gray-700">Smart suggestions for culturally appropriate alternatives based on your specific context and audience.</p>
                </div>

                <div class="border-l-4 border-blue-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✅ Compliance Checking</h3>
                    <p class="text-gray-700">Automatic verification of compliance with cultural norms, regulations, and industry standards.</p>
                </div>

                <div class="border-l-4 border-green-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">📈 Learning & Improvement</h3>
                    <p class="text-gray-700">Continuous learning from user feedback and new cultural data to improve accuracy over time.</p>
                </div>
            </div>
        </div>

        <!-- Risk Categories -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">What We Detect</h2>
            
            <div class="space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-red-900 mb-2">🚨 High-Risk Content</h3>
                    <p class="text-red-700">Religious sensitivities, political references, taboo topics, offensive language, and culturally inappropriate imagery.</p>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-orange-900 mb-2">⚠️ Medium-Risk Content</h3>
                    <p class="text-gray-700">Idioms that don't translate well, humor that may not land, gender-specific language, and cultural stereotypes.</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-yellow-900 mb-2">⚡ Low-Risk Content</h3>
                    <p class="text-gray-700">Minor cultural preferences, formatting conventions, date/time formats, and measurement units.</p>
                </div>
            </div>
        </div>

        <!-- Use Cases -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Perfect For</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🏢</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Global Brands</h3>
                    <p class="text-gray-600">Protect your brand reputation across international markets</p>
                </div>

                <div class="text-center p-6">
                    <div class="text-5xl mb-4">📱</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Marketing Teams</h3>
                    <p class="text-gray-600">Ensure campaigns resonate with diverse audiences</p>
                </div>

                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🎓</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Educational Content</h3>
                    <p class="text-gray-600">Create culturally sensitive learning materials</p>
                </div>

                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🏛️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Government Agencies</h3>
                    <p class="text-gray-600">Communicate effectively with diverse populations</p>
                </div>

                <div class="text-center p-6">
                    <div class="text-5xl mb-4">💼</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Legal Documents</h3>
                    <p class="text-gray-600">Ensure cultural appropriateness in contracts and agreements</p>
                </div>

                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🌐</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Content Creators</h3>
                    <p class="text-gray-600">Reach global audiences without cultural missteps</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Try Cultural Risk Engine</h2>
            <p class="text-xl mb-8">Protect your brand with AI-powered cultural risk detection</p>
            <a href="{{ route('official.documents.upload') }}" class="inline-block px-8 py-4 bg-white text-purple-600 font-bold rounded-lg hover:bg-gray-100 transition text-lg">
                Start Free Trial
            </a>
        </div>

    </div>
</div>
@endsection
