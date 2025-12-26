@extends('layouts.app')

@section('title', 'CTS™ Standard v1.0 - Cultural Translation Standard')
@section('description', 'CTS™ is a proprietary framework defining how cultural context, linguistic accuracy, and institutional requirements are validated together.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 border border-amber-500/30 rounded-full text-amber-400 text-sm font-medium mb-8">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Official Framework Specification
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                CTS™ Standard
            </h1>
            <p class="text-2xl text-amber-400 font-semibold mb-4">Cultural Translation Standard v1.0</p>
            <p class="text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                A proprietary framework developed by CulturalTranslate to define how cultural context, 
                linguistic accuracy, and institutional requirements are validated together.
            </p>
        </div>
    </div>
</section>

<!-- Purpose & Scope Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">1</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Purpose & Scope</h2>
            </div>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-lg text-slate-600 leading-relaxed mb-6">
                    The CTS™ (Cultural Translation Standard) defines how linguistic accuracy, 
                    cultural context, and institutional requirements are validated together 
                    within certified communication workflows.
                </p>
                
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">CTS™ applies to:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Government and institutional documents</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Legal and certified translations</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Cross-border corporate communication</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">High-risk cultural and regulatory contexts</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Principles Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">2</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Core Principles</h2>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">CTS™ is based on five governing principles:</p>
            
            <div class="grid md:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🌍</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Cultural Context First</h3>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏛️</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Institutional Accuracy</h3>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⚠️</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Risk Classification</h3>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">👁️</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Human Oversight</h3>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">✓</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Verifiable Trust</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cultural Risk Classification Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">3</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Cultural Risk Classification</h2>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">CTS™ classifies content into four cultural risk levels:</p>
            
            <div class="space-y-4">
                <!-- Level 1 -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-green-500 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">1</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Level 1 – Informational</h3>
                                <p class="text-slate-600">General content, documentation, guides</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium">Low Risk</span>
                    </div>
                </div>
                
                <!-- Level 2 -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">2</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Level 2 – Commercial / Brand</h3>
                                <p class="text-slate-600">Marketing, advertising, brand communication</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Medium Risk</span>
                    </div>
                </div>
                
                <!-- Level 3 -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-amber-500 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">3</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Level 3 – Legal / Regulatory</h3>
                                <p class="text-slate-600">Contracts, legal documents, compliance materials</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">High Risk</span>
                    </div>
                </div>
                
                <!-- Level 4 -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-red-500 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">4</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Level 4 – Governmental / Sovereign</h3>
                                <p class="text-slate-600">Official government documents, diplomatic communication</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-medium">Critical Risk</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-slate-100 rounded-xl p-6">
                <h4 class="font-bold text-slate-900 mb-3">Each level determines:</h4>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-600">Translation depth</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-600">Review requirements</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-600">Certification eligibility</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Certification Levels Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-slate-900">4</span>
                </div>
                <h2 class="text-3xl font-bold">Certification Levels</h2>
            </div>
            
            <p class="text-lg text-slate-300 mb-8">CTS™ provides four certification levels based on validation depth:</p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- CTS-A -->
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-white text-xl font-bold">CTS-A</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">AI-Assisted</h3>
                            <p class="text-slate-400 text-sm">Cultural Translation</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">AI-powered translation with cultural context analysis. Suitable for Level 1-2 content.</p>
                </div>
                
                <!-- CTS-H -->
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <span class="text-white text-xl font-bold">CTS-H</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Human-Reviewed</h3>
                            <p class="text-slate-400 text-sm">Cultural Translation</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">AI translation reviewed by human experts. Required for Level 2-3 content.</p>
                </div>
                
                <!-- CTS-C -->
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center">
                            <span class="text-white text-xl font-bold">CTS-C</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Certified</h3>
                            <p class="text-slate-400 text-sm">Partner Validation</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">Full certification with partner validation and audit trail. For Level 3 content.</p>
                </div>
                
                <!-- CTS-G -->
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                            <span class="text-white text-xl font-bold">CTS-G</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Government-Ready</h3>
                            <p class="text-slate-400 text-sm">Certified Communication</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">Highest certification with full compliance. Required for Level 4 sovereign content.</p>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-slate-400 italic">Each level is logged, auditable, and publicly verifiable.</p>
            </div>
        </div>
    </div>
</section>

<!-- Partner Validation Model Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">5</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Partner-Based Validation Model</h2>
            </div>
            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
                <p class="text-lg text-amber-800 font-medium">
                    ⚠️ CTS™ does not replace national authorities.
                </p>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">
                Certified outputs are issued in collaboration with authorized partners:
            </p>
            
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">Partner Types</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Licensed translators</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Notaries</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Legal firms</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Authorized local partners</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">CulturalTranslate Provides</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Platform infrastructure</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Verification system</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Audit trail</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Quality standards</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Audit & Verification Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">6</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Audit & Verification</h2>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">Every CTS™ output includes comprehensive verification mechanisms:</p>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Unique Certificate ID</h3>
                    <p class="text-slate-600 text-sm">Every document receives a unique, traceable identifier</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Public Verification</h3>
                    <p class="text-slate-600 text-sm">Online verification endpoint accessible to all parties</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">QR-Based Validation</h3>
                    <p class="text-slate-600 text-sm">Instant verification through scannable QR codes</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Immutable Audit Trail</h3>
                    <p class="text-slate-600 text-sm">Tamper-resistant record of all activities</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Timestamped Records</h3>
                    <p class="text-slate-600 text-sm">Precise issuance timestamps for legal compliance</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Digital Signatures</h3>
                    <p class="text-slate-600 text-sm">Cryptographic verification of authenticity</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Governance Model Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">7</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Governance Model</h2>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">CTS™ is governed under a framework-aligned model:</p>
            
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <div class="text-3xl mb-4">🇪🇺</div>
                    <h3 class="font-bold text-slate-900 mb-2">GDPR Compliant</h3>
                    <p class="text-slate-600 text-sm">Full alignment with European data protection standards</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="text-3xl mb-4">📜</div>
                    <h3 class="font-bold text-slate-900 mb-2">eIDAS-Ready</h3>
                    <p class="text-slate-600 text-sm">Digital certification architecture aligned with EU regulations</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="text-3xl mb-4">🤝</div>
                    <h3 class="font-bold text-slate-900 mb-2">Partner-Based</h3>
                    <p class="text-slate-600 text-sm">Jurisdictional alignment through local partnerships</p>
                </div>
            </div>
            
            <div class="bg-slate-900 text-white rounded-xl p-8 text-center">
                <p class="text-xl font-medium">
                    "CTS™ authority is earned through adoption, not claimed by default."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Versioning Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-600">8</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">Versioning & Transparency</h2>
            </div>
            
            <p class="text-lg text-slate-600 mb-8">CTS™ is a living standard, continuously improved through transparent governance:</p>
            
            <div class="bg-white rounded-xl p-8 shadow-md">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-slate-900 mb-4">Standard Lifecycle</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700">Versioned releases</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700">Public documentation</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700">Change logs</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700">Backward compatibility</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-slate-900 mb-4">Current Version</h3>
                        <div class="bg-amber-50 rounded-xl p-6 border border-amber-200">
                            <div class="text-center">
                                <span class="text-4xl font-bold text-amber-600">v1.0</span>
                                <p class="text-slate-600 mt-2">Initial Release</p>
                                <p class="text-sm text-slate-500 mt-1">December 2024</p>
                            </div>
                        </div>
                    </div>
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
                Ready to Implement CTS™?
            </h2>
            <p class="text-xl text-amber-100 mb-8">
                Contact us to learn how CTS™ can enhance your organization's cultural communication compliance.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/verify-certificate" class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Verify Certificate
                </a>
                <a href="/governance-recognition" class="inline-flex items-center justify-center px-8 py-4 bg-amber-700 text-white font-semibold rounded-xl hover:bg-amber-800 transition-colors">
                    Governance Framework
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
