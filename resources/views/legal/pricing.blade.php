@extends('layouts.app')

@section('title', 'Pricing & Plans - Cultural Translate Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Pricing & Plans</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Transparent Pricing • No Hidden Fees
                </p>
            </div>
        </div>
    </div>

    <!-- General Notice -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl">
            <p class="text-blue-900 font-medium">
                💡 <strong>Notice:</strong> Prices listed below represent standard service tiers. Certain institutional or governmental services may require custom agreements. All prices are shown <strong>excluding VAT</strong> or applicable taxes.
            </p>
        </div>
    </div>

    <!-- Customer Accounts Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Customer Accounts</h2>
            <p class="text-xl text-gray-600">Individual users and small teams</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-16">
            <!-- Free Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-gray-200 hover:border-emerald-500 transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-gray-900">€0</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                    <p class="text-sm text-gray-600">Perfect for testing</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Limited AI translations</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Basic features</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Community support</span>
                        </li>
                    </ul>
                    <a href="/register" class="block w-full text-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>

            <!-- Starter Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-emerald-500 hover:border-emerald-600 transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-emerald-600">€19</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                    <p class="text-sm text-gray-600">For individuals</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">50,000 tokens/month</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">AI translations</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Email support</span>
                        </li>
                    </ul>
                    <a href="/pricing" class="block w-full text-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                        Choose Plan
                    </a>
                </div>
            </div>

            <!-- Pro Plan -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-purple-500 hover:border-purple-600 transition-all transform hover:scale-105 relative">
                <div class="absolute top-0 right-0 bg-purple-500 text-white px-4 py-1 text-sm font-semibold rounded-bl-lg">
                    POPULAR
                </div>
                <div class="p-6 bg-gradient-to-br from-purple-50 to-indigo-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pro</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-purple-600">€49</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                    <p class="text-sm text-gray-600">For professionals</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">200,000 tokens/month</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">AI + Cultural adaptation</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Priority support</span>
                        </li>
                    </ul>
                    <a href="/pricing" class="block w-full text-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        Choose Plan
                    </a>
                </div>
            </div>

            <!-- Business Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-500 hover:border-blue-600 transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-blue-50 to-cyan-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Business</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-blue-600">€99</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                    <p class="text-sm text-gray-600">For small teams</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">High-volume processing</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Limited API access</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Dedicated support</span>
                        </li>
                    </ul>
                    <a href="/pricing" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Choose Plan
                    </a>
                </div>
            </div>
        </div>

        <!-- Affiliate Accounts -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl shadow-lg p-8 mb-16 border border-yellow-200">
            <div class="flex items-center justify-between flex-wrap gap-6">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Affiliate Accounts</h3>
                    <p class="text-gray-700 mb-4">Earn commission by referring customers</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Free registration
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Commission-based earnings
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            No subscription fees
                        </li>
                    </ul>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-extrabold text-yellow-600 mb-2">€0</div>
                    <p class="text-gray-600 mb-4">Always Free</p>
                    <a href="/register" class="inline-block px-8 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors shadow-lg">
                        Join Affiliate Program
                    </a>
                </div>
            </div>
        </div>

        <!-- Translator Accounts -->
        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl shadow-lg p-8 mb-16 border border-teal-200">
            <div class="flex items-center justify-between flex-wrap gap-6">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Translator Accounts</h3>
                    <p class="text-gray-700 mb-4">Work as a professional translator</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            No subscription fee
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Paid per completed review
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Platform commission applies
                        </li>
                    </ul>
                    <p class="text-sm text-gray-600 mt-4">
                        <em>Rates vary by language pair and document type</em>
                    </p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-extrabold text-teal-600 mb-2">€0</div>
                    <p class="text-gray-600 mb-4">Per-Job Payment</p>
                    <a href="/register" class="inline-block px-8 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-colors shadow-lg">
                        Apply as Translator
                    </a>
                </div>
            </div>
        </div>

        <!-- Partner Accounts -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Partner Accounts</h2>
            <p class="text-xl text-gray-600">For agencies and resellers</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-16">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-gray-200 hover:border-indigo-500 transition-all">
                <div class="p-6 bg-gradient-to-br from-gray-50 to-slate-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Partner Basic</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-gray-900">€99</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Team management</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Basic reporting</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-indigo-500 hover:border-indigo-600 transition-all">
                <div class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Partner Pro</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-indigo-600">€249</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Advanced reporting</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">API access</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-purple-500 hover:border-purple-600 transition-all">
                <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise Partner</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-3xl font-extrabold text-purple-600">Custom</span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6">Contact us for custom enterprise solutions</p>
                    <a href="mailto:sales@culturaltranslate.com" class="block w-full text-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>

        <!-- University Accounts -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">University Accounts</h2>
            <p class="text-xl text-gray-600">For academic institutions</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-16">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-200 hover:border-blue-500 transition-all">
                <div class="p-6 bg-gradient-to-br from-blue-50 to-cyan-50">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Academic Basic</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-blue-600">€99</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">For departments</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-500 hover:border-blue-600 transition-all">
                <div class="p-6 bg-gradient-to-br from-blue-100 to-indigo-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Research</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-blue-600">€199</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">For research teams</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-indigo-500 hover:border-indigo-600 transition-all">
                <div class="p-6 bg-gradient-to-br from-indigo-100 to-purple-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Institutional</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-indigo-600">€399</span>
                        <span class="text-gray-600 ml-2">/month</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Full university access</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Government Accounts -->
        <div class="bg-gradient-to-r from-slate-700 to-gray-800 rounded-2xl shadow-2xl p-12 text-white text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="text-4xl font-bold mb-4">Government Accounts</h2>
            <p class="text-xl text-gray-300 mb-6">
                By invitation or approval only
            </p>
            <div class="bg-white/10 rounded-xl p-6 max-w-2xl mx-auto mb-8">
                <p class="text-gray-200 leading-relaxed mb-4">
                    Government services are offered with custom contractual agreements including:
                </p>
                <ul class="text-left space-y-2 text-gray-200">
                    <li>• One-time onboarding fee</li>
                    <li>• Monthly service retainer</li>
                    <li>• Per-document certification fees</li>
                    <li>• Jurisdiction-specific governance</li>
                </ul>
            </div>
            <a href="mailto:government@culturaltranslate.com" class="inline-block px-10 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-lg">
                Contact Government Services
            </a>
        </div>

        <!-- Pricing Changes Notice -->
        <div class="mt-16 bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
            <p class="text-gray-700">
                <strong>Notice:</strong> We reserve the right to modify pricing with reasonable notice. Existing subscriptions remain valid until renewal. Educational discounts may apply for verified academic institutions.
            </p>
        </div>
    </div>
</div>
@endsection
