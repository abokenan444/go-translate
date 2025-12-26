@extends('layouts.app')
@section('title', 'Press Kit - Cultural Translate')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Press Kit</h1>
            <p class="text-2xl text-gray-600">Media resources and brand assets</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">About CulturalTranslate</h2>
            <p class="text-lg text-gray-700 mb-4">
                CulturalTranslate is the world's leading AI-powered cultural translation platform, serving over 1 million users across 150+ countries. Our proprietary CTS™ (Cultural Translation Standard) ensures the highest quality translations that preserve cultural context and meaning.
            </p>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">📥 Brand Assets</h3>
                <p class="text-gray-700 mb-4">Download our logos, colors, and brand guidelines</p>
                <button class="px-6 py-3 bg-blue-600 text-white rounded-lg">Download Assets</button>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">📰 Press Releases</h3>
                <p class="text-gray-700 mb-4">Latest news and announcements</p>
                <button class="px-6 py-3 bg-purple-600 text-white rounded-lg">View Releases</button>
            </div>
        </div>
    </div>
</div>
@endsection
