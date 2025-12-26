@extends('layouts.app')
@section('title', 'Physical Copy - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Physical Copy</h1>
        <p class="text-xl mb-8">Professional translation services tailored to your needs</p>
        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition text-lg font-semibold">
            Get Started
        </a>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Service Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold mb-2">High Quality</h3>
                        <p class="text-gray-600">Professional translation with cultural adaptation</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                        <p class="text-gray-600">Quick turnaround time for all projects</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Secure & Confidential</h3>
                        <p class="text-gray-600">Your documents are safe with us</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold mb-2">24/7 Support</h3>
                        <p class="text-gray-600">We're here to help anytime you need</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-gray-600 mb-8">Join thousands of satisfied customers</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Start Free Trial
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-indigo-600 border-2 border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
