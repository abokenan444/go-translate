@extends('layouts.app')
@section('title', __('Cultural Risk Engine'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('Cultural Risk Engine') }}</h1>
            <p class="text-xl">{{ __('AI-Powered Cultural Sensitivity Analysis') }}</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4">{{ __('What is the Cultural Risk Engine?') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('Our Cultural Risk Engine is an advanced AI system that analyzes content for potential cultural, religious, political, and social sensitivities before publication. It helps organizations avoid costly mistakes and maintain positive relationships across diverse markets.') }}</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Real-Time Analysis') }}</h3>
                <p class="text-gray-700">{{ __('Instant feedback on cultural risks as you create content') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Multi-Layer Screening') }}</h3>
                <p class="text-gray-700">{{ __('Comprehensive evaluation across religious, political, and social dimensions') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">{{ __('Actionable Insights') }}</h3>
                <p class="text-gray-700">{{ __('Clear recommendations for mitigating identified risks') }}</p>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('register') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition">{{ __('Try Risk Engine') }}</a>
        </div>
    </div>
</div>
@endsection
