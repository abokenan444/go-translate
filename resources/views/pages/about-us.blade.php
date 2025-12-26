@extends('layouts.app')
@section('title', 'About Us - Cultural Translate')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">About CulturalTranslate</h1>
            <p class="text-2xl text-gray-600">Bridging cultures through intelligent translation</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
            <p class="text-lg text-gray-700 mb-4">
                At CulturalTranslate, we believe that language is more than words—it's culture, context, and connection. Our mission is to break down language barriers while preserving the cultural richness that makes each language unique.
            </p>
            <p class="text-lg text-gray-700">
                Founded in 2020, we've grown from a small team of linguists and engineers to a global platform serving thousands of users across 150+ countries. Our AI-powered translation engine, combined with human expertise, ensures accuracy, cultural sensitivity, and authenticity in every translation.
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">130+</div>
                <p class="text-gray-700">Languages Supported</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-4xl font-bold text-purple-600 mb-2">1M+</div>
                <p class="text-gray-700">Documents Translated</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-4xl font-bold text-green-600 mb-2">150+</div>
                <p class="text-gray-700">Countries Served</p>
            </div>
        </div>
    </div>
</div>
@endsection
