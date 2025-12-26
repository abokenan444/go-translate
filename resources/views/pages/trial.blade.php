@extends('layouts.app')
@section('title', 'Free Trial - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Start Your Free Trial</h1>
        <p class="text-xl mb-8">Try all features free for 14 days. No credit card required.</p>
        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition text-lg font-semibold">
            Start Free Trial Now
        </a>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">What's Included in Your Trial</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-infinity text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">100,000 Characters</h3>
                <p class="text-gray-600">Translate up to 100k characters during your trial</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-globe text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">50+ Languages</h3>
                <p class="text-gray-600">Access to all supported languages</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-robot text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">AI-Powered</h3>
                <p class="text-gray-600">GPT-4, Google Translate, DeepL support</p>
            </div>
        </div>
    </div>
</section>
@endsection
