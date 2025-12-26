@extends('layouts.app')

@section('title', 'About Us - CulturalTranslate')
@section('meta_description', 'CulturalTranslate is building the global standard for cultural intelligence, certified communication, and cross-border trust.')

@section('content')
<!-- Hero Section - Government Grade -->
<section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 border border-amber-500/30 rounded-full text-amber-400 text-sm font-medium mb-8">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Controlled Pilot Phase
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Building the Global Standard<br>
                <span class="text-amber-400">for Cultural Intelligence</span>
            </h1>
            <p class="text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
                CulturalTranslate was founded to address a fundamental gap in global communication: 
                language can be translated, but culture is often misinterpreted.
            </p>
        </div>
    </div>
</section>

<!-- Our Approach Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-slate-900 mb-8 text-center">Our Approach</h2>
            
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 md:p-12">
                <div class="prose prose-lg max-w-none text-slate-700">
                    <p class="text-lg leading-relaxed mb-6">
                        Rather than competing with traditional translation tools, we focus on <strong>defining standards</strong> 
                        for cultural intelligence, certified communication, and institutional trust.
                    </p>
                    <p class="text-lg leading-relaxed mb-6">
                        Our platform is currently in a <strong>controlled pilot phase</strong>, working with selected early adopters, 
                        partners, and institutions to validate the <strong>CTS™ (Cultural Translation Standard)</strong> framework.
                    </p>
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-r-lg my-8">
                        <p class="text-amber-900 font-medium italic text-lg">
                            "We believe global authority is not claimed — it is earned through governance, 
                            verification, and responsible deployment."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What Makes Us Different -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-slate-900 mb-4 text-center">What Makes Us Different</h2>
        <p class="text-slate-600 text-center mb-12 max-w-2xl mx-auto">
            We are not a translation company. We are a platform defining how culture, language, and trust are verified globally.
        </p>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Traditional Approach -->
            <div class="bg-white rounded-xl p-8 border border-slate-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-500">Traditional Translation</h3>
                </div>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start">
                        <span class="text-slate-400 mr-3">•</span>
                        Word-for-word conversion
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-400 mr-3">•</span>
                        No cultural context analysis
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-400 mr-3">•</span>
                        Unverifiable quality
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-400 mr-3">•</span>
                        No institutional recognition
                    </li>
                </ul>
            </div>
            
            <!-- Our Approach -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-8 text-white">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">CulturalTranslate Approach</h3>
                </div>
                <ul class="space-y-3 text-slate-300">
                    <li class="flex items-start">
                        <span class="text-amber-400 mr-3">✓</span>
                        Cultural intelligence framework
                    </li>
                    <li class="flex items-start">
                        <span class="text-amber-400 mr-3">✓</span>
                        Risk classification system
                    </li>
                    <li class="flex items-start">
                        <span class="text-amber-400 mr-3">✓</span>
                        Verifiable certifications
                    </li>
                    <li class="flex items-start">
                        <span class="text-amber-400 mr-3">✓</span>
                        Partner-based validation
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Platform Status - Transparent -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-slate-900 mb-4 text-center">Platform Status</h2>
        <p class="text-slate-600 text-center mb-12 max-w-2xl mx-auto">
            Transparent metrics from our controlled pilot phase
        </p>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Platform Adoption -->
            <div class="bg-slate-50 rounded-xl p-8 border border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Platform Adoption
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Registered Users</span>
                        <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\User::count()) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Active Subscriptions</span>
                        <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\Subscription::where('status', 'active')->count()) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-slate-600">Active Organizations</span>
                        <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\Company::where('status', 'active')->count()) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Framework Validation -->
            <div class="bg-slate-50 rounded-xl p-8 border border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Framework Validation
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Languages Supported</span>
                        <span class="text-2xl font-bold text-slate-900">116+</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Document Types</span>
                        <span class="text-2xl font-bold text-slate-900">15+</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-slate-600">Certification Levels</span>
                        <span class="text-2xl font-bold text-slate-900">4</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-sm text-slate-500">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Statistics are real-time from our production database
            </p>
        </div>
    </div>
</section>

<!-- CTS Framework Preview -->
<section class="py-20 bg-slate-900 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">The CTS™ Framework</h2>
            <p class="text-slate-400">
                Cultural Translation Standard - Our proprietary framework for certified communication
            </p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <div class="bg-slate-800/50 rounded-xl p-6 text-center border border-slate-700">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-400">A</span>
                </div>
                <h3 class="font-semibold text-white mb-2">CTS-A</h3>
                <p class="text-sm text-slate-400">AI-Assisted Cultural Translation</p>
            </div>
            
            <div class="bg-slate-800/50 rounded-xl p-6 text-center border border-slate-700">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-400">H</span>
                </div>
                <h3 class="font-semibold text-white mb-2">CTS-H</h3>
                <p class="text-sm text-slate-400">Human-Reviewed Translation</p>
            </div>
            
            <div class="bg-slate-800/50 rounded-xl p-6 text-center border border-slate-700">
                <div class="w-16 h-16 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-amber-400">C</span>
                </div>
                <h3 class="font-semibold text-white mb-2">CTS-C</h3>
                <p class="text-sm text-slate-400">Certified with Partner Validation</p>
            </div>
            
            <div class="bg-slate-800/50 rounded-xl p-6 text-center border border-slate-700">
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-400">G</span>
                </div>
                <h3 class="font-semibold text-white mb-2">CTS-G</h3>
                <p class="text-sm text-slate-400">Government-Ready Certified</p>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="/cts/standard" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg transition">
                View Full CTS™ Standard
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-slate-900 mb-4 text-center">Our Core Principles</h2>
        <p class="text-slate-600 text-center mb-12 max-w-2xl mx-auto">
            The foundations that guide everything we build
        </p>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Governance First</h3>
                <p class="text-slate-600">
                    Every feature, every certification is built with institutional accountability and compliance in mind.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Transparency</h3>
                <p class="text-slate-600">
                    Real metrics, honest communication, and verifiable claims. No inflated numbers, no misleading statistics.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Partner Validation</h3>
                <p class="text-slate-600">
                    We don't replace authorities. We work with licensed translators, notaries, and legal partners.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Compliance Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-slate-900 mb-8 text-center">Compliance & Security</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 border border-slate-200 flex items-start">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-1">GDPR Compliant</h3>
                        <p class="text-sm text-slate-600">Full compliance with European data protection regulations</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 border border-slate-200 flex items-start">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-1">Audit Trail Enabled</h3>
                        <p class="text-sm text-slate-600">Complete tracking of all document activities</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 border border-slate-200 flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-1">eIDAS Ready</h3>
                        <p class="text-sm text-slate-600">Digital certification architecture aligned with EU standards</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 border border-slate-200 flex items-start">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-1">Partner Jurisdiction</h3>
                        <p class="text-sm text-slate-600">Local compliance through certified partner network</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Explore?</h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Join our controlled pilot program and be part of defining the future of certified cultural communication.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition">
                Start Free Trial
            </a>
            <a href="/government-pilot" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition">
                Government Pilot Program
            </a>
        </div>
    </div>
</section>
@endsection
