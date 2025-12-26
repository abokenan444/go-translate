@extends('layouts.app')

@section('title', 'Government & Institutional Pilot Program - CulturalTranslate')
@section('description', 'Join our pilot program for certified cultural communication infrastructure. For governments, institutions, and enterprise organizations.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-blue-500/20 border border-blue-500/30 rounded-full text-blue-400 text-sm font-medium mb-8">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Institutional Partnership
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Government & Institutional<br>Pilot Program
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                Certified Cultural Communication Infrastructure for Organizations That Require the Highest Standards
            </p>
        </div>
    </div>
</section>

<!-- The Problem Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">The Challenge</h2>
                    <div class="w-24 h-1 bg-red-500 mb-8"></div>
                    <p class="text-lg text-slate-600 mb-6">
                        Governments and institutions face increasing risks in cross-border communication:
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Cultural Misinterpretation</h3>
                                <p class="text-sm text-slate-600">Messages lost in translation can cause diplomatic incidents</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Legal Ambiguity</h3>
                                <p class="text-sm text-slate-600">Unclear certification status creates compliance risks</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Verification Gaps</h3>
                                <p class="text-sm text-slate-600">No standardized way to verify translated documents</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Fragmented Trust</h3>
                                <p class="text-sm text-slate-600">Different standards across jurisdictions create friction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Approach Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Our Approach</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    CulturalTranslate addresses these challenges through a comprehensive framework
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">Cultural Intelligence</h3>
                    <p class="text-slate-600 text-sm">AI-powered frameworks for cultural context analysis</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">CTS™ Standards</h3>
                    <p class="text-slate-600 text-sm">Certified communication standards framework</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">Partner Validation</h3>
                    <p class="text-slate-600 text-sm">Local partner-based certification workflows</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">Public Verification</h3>
                    <p class="text-slate-600 text-sm">Digital certificates with verification infrastructure</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pilot Scope Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Pilot Program Scope</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    What's included in the institutional pilot program
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- What the Pilot Includes -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border border-green-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Pilot Scope
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Certified document translation</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Cultural risk classification</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Partner validation workflows</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Digital certificates with verification</span>
                        </li>
                    </ul>
                    
                    <div class="mt-6 pt-6 border-t border-green-200">
                        <div class="flex items-center space-x-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Duration:</strong> 60–90 days</span>
                        </div>
                    </div>
                </div>
                
                <!-- What We Provide -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border border-blue-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        CulturalTranslate Provides
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Translation & cultural intelligence platform</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">CTS™ certification framework</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Audit trail & verification infrastructure</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-700">Dedicated pilot support</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Governance Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Governance & Compliance</h2>
            <p class="text-xl text-slate-300 mb-12">
                All pilots are conducted with full transparency and compliance alignment
            </p>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🇪🇺</span>
                    </div>
                    <h3 class="text-lg font-bold mb-3">GDPR Aligned</h3>
                    <p class="text-slate-400 text-sm">Full compliance with European data protection regulations</p>
                </div>
                
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">⚖️</span>
                    </div>
                    <h3 class="text-lg font-bold mb-3">No Sovereignty Replacement</h3>
                    <p class="text-slate-400 text-sm">We work alongside existing authorities, never replacing them</p>
                </div>
                
                <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
                    <div class="w-16 h-16 bg-amber-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📋</span>
                    </div>
                    <h3 class="text-lg font-bold mb-3">Full Audit Trail</h3>
                    <p class="text-slate-400 text-sm">Complete transparency with partner-based certification</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Outcomes Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Expected Outcomes</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    What institutions can expect from the pilot program
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Reduced Cultural Risk</h3>
                    <p class="text-slate-600 text-sm">Minimized misinterpretation in cross-border communication</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Verifiable Outputs</h3>
                    <p class="text-slate-600 text-sm">Certified documents with public verification</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Improved Trust</h3>
                    <p class="text-slate-600 text-sm">Enhanced cross-border institutional confidence</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Long-term Foundation</h3>
                    <p class="text-slate-600 text-sm">Basis for ongoing cooperation and scaling</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Next Steps Section -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Next Steps</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    How to get started with the pilot program
                </p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-md flex items-center space-x-6">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-2xl font-bold">1</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Initial Contact</h3>
                        <p class="text-slate-600">Reach out to discuss your institution's requirements and goals</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md flex items-center space-x-6">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-2xl font-bold">2</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Memorandum of Understanding</h3>
                        <p class="text-slate-600">Define pilot scope, timeline, and success criteria</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md flex items-center space-x-6">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-2xl font-bold">3</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Pilot Onboarding</h3>
                        <p class="text-slate-600">Set up platform access, training, and support channels</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md flex items-center space-x-6">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-2xl font-bold">4</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Controlled Deployment</h3>
                        <p class="text-slate-600">Begin translation and certification with selected documents</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md flex items-center space-x-6">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-2xl font-bold">5</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Evaluation & Reporting</h3>
                        <p class="text-slate-600">Review results, gather feedback, and plan next steps</p>
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
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-12 border border-white/20">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Ready to Explore Partnership?
                </h2>
                <p class="text-xl text-amber-100 mb-8">
                    CulturalTranslate does not claim authority.<br>
                    We build the infrastructure through which trust is verified.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact for Pilot Program
                    </a>
                    <a href="/governance-recognition" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-amber-600 transition-colors">
                        View Governance Framework
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('components.footer')
@endsection
