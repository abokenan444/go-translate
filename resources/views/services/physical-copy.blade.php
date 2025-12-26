@extends('layouts.app')

@section('title', 'Certified Translation Service - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Certified Translation Service</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Official certified translations with dual stamps from Cultural Translate and our certified partners. Accepted by embassies, universities, courts, and government institutions worldwide.
                </p>
                <div class="mt-8 flex gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-all transform hover:scale-105">
                        Get Started
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-800 transition-all">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Our Certified Translation?</h2>
            <p class="text-xl text-gray-600">Professional certification with global acceptance</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Dual Stamp Certification</h3>
                <p class="text-gray-600 leading-relaxed">
                    Every certified translation includes two official stamps: one from Cultural Translate and one from our certified partner. This dual certification ensures maximum acceptance by institutions worldwide.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">QR Code Verification</h3>
                <p class="text-gray-600 leading-relaxed">
                    Each certified document includes a unique QR code that links to our verification system. Institutions can instantly verify the authenticity, certificate ID, translation date, and partner information.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Global Acceptance</h3>
                <p class="text-gray-600 leading-relaxed">
                    Our certified translations are recognized and accepted by embassies, universities, courts, immigration offices, and government institutions in over 150 countries worldwide.
                </p>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Simple process, professional results</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Upload Document</h3>
                    <p class="text-gray-600">Upload your document securely through our platform</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Professional Translation</h3>
                    <p class="text-gray-600">Certified translators translate your document</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Dual Stamp Applied</h3>
                    <p class="text-gray-600">Both Cultural Translate and partner stamps applied</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Receive & Verify</h3>
                    <p class="text-gray-600">Download PDF with QR code verification</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Supported Documents -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Supported Document Types</h2>
            <p class="text-xl text-gray-600">We certify all types of official documents</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-blue-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Identity Documents</h3>
                <p class="text-sm text-gray-600">Passports, ID cards, driver's licenses</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-indigo-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Educational</h3>
                <p class="text-sm text-gray-600">Diplomas, transcripts, certificates</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-green-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Legal Documents</h3>
                <p class="text-sm text-gray-600">Contracts, agreements, court documents</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-purple-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Business Documents</h3>
                <p class="text-sm text-gray-600">Certificates, licenses, financial statements</p>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Transparent Pricing</h2>
                <p class="text-xl text-blue-100">Professional certification at competitive rates</p>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 max-w-2xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-2xl font-bold mb-4">What's Included</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Professional translation by certified translators</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Dual stamp certification (Cultural Translate + Partner)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Unique certificate ID and QR code</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Digital PDF with watermarked stamps</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Online verification system</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>24/7 customer support</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="text-center mb-6">
                            <div class="text-5xl font-bold mb-2">Custom</div>
                            <p class="text-blue-100">Based on document type and language pair</p>
                        </div>
                        <a href="{{ route('register') }}" class="block w-full py-4 bg-white text-blue-600 rounded-lg font-semibold text-center hover:bg-blue-50 transition-all transform hover:scale-105">
                            Get a Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-12 text-center text-white">
            <h2 class="text-4xl font-bold mb-4">Ready to Get Your Documents Certified?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Experience CTS™ certified translation with cultural intelligence and verification
            </p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-all transform hover:scale-105">
                    Start Now
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-800 transition-all border-2 border-white">
                    Contact Us
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
