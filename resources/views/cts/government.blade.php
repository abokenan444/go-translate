@extends('layouts.app')
@section('title', __('Government Portal'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('Government & Official Documents Portal') }}</h1>
            <p class="text-xl">{{ __('Certified translations for government submissions and official use') }}</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4">{{ __('Official Document Translation Services') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('CulturalTranslate provides certified translation services for government agencies, embassies, and official institutions worldwide. Our CTS™ certified translations are accepted by government bodies in over 150 countries.') }}</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Accepted Document Types') }}</h3>
                <ul class="space-y-2 text-gray-700">
                    <li>• {{ __('Birth Certificates') }}</li>
                    <li>• {{ __('Marriage Certificates') }}</li>
                    <li>• {{ __('Diplomas & Transcripts') }}</li>
                    <li>• {{ __('Legal Contracts') }}</li>
                    <li>• {{ __('Immigration Documents') }}</li>
                    <li>• {{ __('Court Documents') }}</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Government Features') }}</h3>
                <ul class="space-y-2 text-gray-700">
                    <li>• {{ __('Sworn Translator Seal') }}</li>
                    <li>• {{ __('Apostille Services') }}</li>
                    <li>• {{ __('Notarization') }}</li>
                    <li>• {{ __('Express Processing') }}</li>
                    <li>• {{ __('Secure Delivery') }}</li>
                    <li>• {{ __('24/7 Support') }}</li>
                </ul>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('official-documents.index') }}" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-900 transition">{{ __('Submit Official Document') }}</a>
        </div>
    </div>
</div>
@endsection
