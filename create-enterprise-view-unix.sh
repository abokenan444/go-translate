#!/bin/bash
cd /var/www/cultural-translate-platform
mkdir -p resources/views/enterprise

cat > resources/views/enterprise/pricing.blade.php << 'ENDOFFILE'
@extends('layouts.app')

@section('title', 'Enterprise Pricing - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Enterprise Pricing</h1>
            <p class="text-xl text-gray-600">Flexible plans for businesses of all sizes</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pay As You Go</h3>
                <p class="text-gray-600 mb-6">Perfect for businesses with variable translation needs</p>
                <div class="text-4xl font-bold text-indigo-600 mb-6">Custom</div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>No minimum commitment</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Competitive per-word rates</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>24/7 support</span>
                    </li>
                </ul>
                <a href="{{ route('enterprise.request-form') }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
            </div>

            <div class="bg-indigo-600 rounded-lg shadow-lg p-8 text-white transform scale-105">
                <div class="text-sm font-semibold mb-2">MOST POPULAR</div>
                <h3 class="text-2xl font-bold mb-4">Committed Volume</h3>
                <p class="text-indigo-100 mb-6">Best value for consistent translation needs</p>
                <div class="text-4xl font-bold mb-6">Custom</div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-indigo-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Volume discounts</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-indigo-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Priority support</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-indigo-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Dedicated account manager</span>
                    </li>
                </ul>
                <a href="{{ route('enterprise.request-form') }}" class="block w-full text-center bg-white text-indigo-600 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">Get Started</a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Hybrid</h3>
                <p class="text-gray-600 mb-6">Combine base commitment with flexible add-ons</p>
                <div class="text-4xl font-bold text-indigo-600 mb-6">Custom</div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Best of both worlds</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Predictable baseline costs</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Flexible scaling</span>
                    </li>
                </ul>
                <a href="{{ route('enterprise.request-form') }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
            </div>
        </div>

        <div class="text-center">
            <p class="text-gray-600 mb-4">Need help choosing the right plan?</p>
            <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Contact our sales team</a>
        </div>
    </div>
</div>
@endsection
ENDOFFILE

php artisan view:clear
echo "Enterprise pricing view created successfully!"
