@extends('layouts.app')
@section('title', __('Partner Program'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('CulturalTranslate Partner Program') }}</h1>
            <p class="text-xl">{{ __('Join our global network of translation partners') }}</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4">{{ __('Why Become a Partner?') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('Partner with CulturalTranslate to expand your business, access cutting-edge translation technology, and serve clients worldwide with CTS™ certified translations.') }}</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Revenue Share') }}</h3>
                <p class="text-gray-700">{{ __('Earn up to 30% commission on all translations') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('White Label') }}</h3>
                <p class="text-gray-700">{{ __('Offer CTS™ certified translations under your brand') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('API Access') }}</h3>
                <p class="text-gray-700">{{ __('Full API integration for seamless workflows') }}</p>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('contact') }}" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">{{ __('Apply Now') }}</a>
        </div>
    </div>
</div>
@endsection
