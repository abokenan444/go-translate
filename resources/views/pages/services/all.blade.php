@extends('layouts.app')

@section('title', 'Our Services - CulturalTranslate')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Translation Services</h1>
        <p class="text-xl mb-8">Professional AI-powered translation with cultural adaptation</p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Certified Translation -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-certificate text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Certified Translation</h3>
                <p class="text-gray-600 mb-6">Official certified translations for legal documents, diplomas, and official papers.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Legal documents
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Academic certificates
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Official stamps
                    </li>
                </ul>
                <a href="{{ route('services.certified-translation') }}" class="block w-full text-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Learn More
                </a>
            </div>

            <!-- Document Translation -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-file-alt text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Document Translation</h3>
                <p class="text-gray-600 mb-6">Fast and accurate translation for business documents and content.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Business documents
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Marketing materials
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Technical manuals
                    </li>
                </ul>
                <a href="{{ route('services.document-translation') }}" class="block w-full text-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Learn More
                </a>
            </div>

            <!-- Physical Copy -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-print text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Physical Copy Delivery</h3>
                <p class="text-gray-600 mb-6">Get your translated documents printed and delivered to your doorstep.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Professional printing
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Worldwide shipping
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Secure packaging
                    </li>
                </ul>
                <a href="{{ route('services.physical-copy') }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Learn More
                </a>
            </div>

            <!-- Enterprise Solutions -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-building text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise Solutions</h3>
                <p class="text-gray-600 mb-6">Custom translation solutions for large organizations and enterprises.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Dedicated account manager
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Custom API integration
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Volume discounts
                    </li>
                </ul>
                <a href="{{ route('services.enterprise') }}" class="block w-full text-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Learn More
                </a>
            </div>

            <!-- Partner Program -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-handshake text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Partner Program</h3>
                <p class="text-gray-600 mb-6">Join our partner network and grow your business with us.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        White-label solutions
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Revenue sharing
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Marketing support
                    </li>
                </ul>
                <a href="{{ route('services.partners') }}" class="block w-full text-center px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    Learn More
                </a>
            </div>

            <!-- Affiliate Program -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-users text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Affiliate Program</h3>
                <p class="text-gray-600 mb-6">Earn commissions by referring customers to our platform.</p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Up to 30% commission
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Lifetime cookies
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Marketing materials
                    </li>
                </ul>
                <a href="{{ route('services.affiliate') }}" class="block w-full text-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-gray-600 mb-8">Choose the service that fits your needs</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Start Free Trial
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-indigo-600 border-2 border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                Contact Sales
            </a>
        </div>
    </div>
</section>
@endsection
