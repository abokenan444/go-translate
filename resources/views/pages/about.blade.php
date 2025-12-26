@extends('layouts.app')

@section('title', 'About CulturalTranslate - Building Global Standards for Cultural Intelligence')
@section('description', 'Learn how CulturalTranslate is defining standards for cultural intelligence, certified communication, and cross-border institutional trust.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 border border-amber-500/30 rounded-full text-amber-400 text-sm font-medium mb-8">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Defining Global Standards
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                About CulturalTranslate
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                We are building the infrastructure for how culture, language, and trust are verified globally.
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Our Mission</h2>
                <div class="w-24 h-1 bg-amber-500 mx-auto"></div>
            </div>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-xl text-slate-700 leading-relaxed mb-6">
                    CulturalTranslate was founded to address a fundamental gap in global communication: 
                    <strong>language can be translated, but culture is often misinterpreted.</strong>
                </p>
                
                <p class="text-lg text-slate-600 leading-relaxed mb-6">
                    Rather than competing with traditional translation tools, we focus on defining standards 
                    for cultural intelligence, certified communication, and institutional trust.
                </p>
                
                <div class="bg-slate-50 border-l-4 border-amber-500 p-6 my-8 rounded-r-lg">
                    <p class="text-lg text-slate-700 italic mb-0">
                        "We believe global authority is not claimed — it is earned through governance, 
                        verification, and responsible deployment."
                    </p>
                </div>
                
                <p class="text-lg text-slate-600 leading-relaxed">
                    Our platform is currently in a <strong>controlled pilot phase</strong>, working with selected 
                    early adopters, partners, and institutions to validate the CTS™ (Cultural Translation Standard) framework.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">What We're Building</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    A comprehensive framework for certified cultural communication
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">CTS™ Standard</h3>
                    <p class="text-slate-600">
                        A proprietary framework defining how cultural context, linguistic accuracy, 
                        and institutional requirements are validated together.
                    </p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Cultural Intelligence</h3>
                    <p class="text-slate-600">
                        AI-powered analysis that identifies cultural risks, nuances, and adaptation 
                        requirements for cross-border communication.
                    </p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Certified Communication</h3>
                    <p class="text-slate-600">
                        Partner-based certification workflows that provide verifiable, 
                        audit-trail-enabled document authentication.
                    </p>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Institutional Trust</h3>
                    <p class="text-slate-600">
                        Infrastructure enabling governments and organizations to verify 
                        translated documents with confidence.
                    </p>
                </div>
                
                <!-- Card 5 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Verification Infrastructure</h3>
                    <p class="text-slate-600">
                        Public certificate verification, QR-based validation, and 
                        tamper-resistant audit trails.
                    </p>
                </div>
                
                <!-- Card 6 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Partner Network</h3>
                    <p class="text-slate-600">
                        Collaboration with licensed translators, notaries, legal firms, 
                        and authorized local partners worldwide.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Current Status Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Platform Status</h2>
                <p class="text-xl text-slate-600">Transparent metrics from our controlled pilot phase</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Platform Adoption -->
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-8 border border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-500 uppercase tracking-wider mb-6">Platform Adoption</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Registered Users</span>
                            <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\User::count()) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Active Subscriptions</span>
                            <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\Subscription::where('status', 'active')->count()) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Active Organizations</span>
                            <span class="text-2xl font-bold text-slate-900">{{ number_format(\App\Models\Company::where('status', 'active')->count()) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Framework Validation -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-8 border border-amber-200">
                    <h3 class="text-lg font-semibold text-amber-700 uppercase tracking-wider mb-6">Framework Validation</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Languages Supported</span>
                            <span class="text-2xl font-bold text-slate-900">116+</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Sector Combinations</span>
                            <span class="text-2xl font-bold text-slate-900">100+</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">CTS™ Framework</span>
                            <span class="text-2xl font-bold text-green-600">v1.0 Active</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-slate-500">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Stats updated in real-time from platform data
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Approach Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Our Approach</h2>
                <p class="text-xl text-slate-300">How we're different from traditional translation services</p>
            </div>
            
            <div class="space-y-8">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">We're NOT Competing With</h3>
                        <p class="text-slate-400">Google Translate, DeepL, or traditional translation tools. They serve a different purpose.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">We ARE Building</h3>
                        <p class="text-slate-400">A global standard for cultural intelligence, certified communication, and cross-border institutional trust.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Our Goal</h3>
                        <p class="text-slate-400">To become the trusted framework through which governments and institutions verify cultural communication.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Our Principles</h2>
                <p class="text-xl text-slate-600">The foundation of everything we build</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Transparency</h3>
                    <p class="text-slate-600 text-sm">Real metrics, honest communication, no inflated claims</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Governance</h3>
                    <p class="text-slate-600 text-sm">Compliance-first approach aligned with GDPR and eIDAS</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Partnership</h3>
                    <p class="text-slate-600 text-sm">Working with local authorities, not replacing them</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Innovation</h3>
                    <p class="text-slate-600 text-sm">Continuous improvement through responsible AI development</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-amber-500 to-amber-600">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Interested in Learning More?
            </h2>
            <p class="text-xl text-amber-100 mb-8">
                Explore our governance framework, CTS™ standard, or contact us for institutional partnerships.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/governance-recognition" class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Governance & Recognition
                </a>
                <a href="/cts/standard" class="inline-flex items-center justify-center px-8 py-4 bg-amber-700 text-white font-semibold rounded-xl hover:bg-amber-800 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    CTS™ Standard v1.0
                </a>
                <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-amber-600 transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@include('components.footer')
@endsection
