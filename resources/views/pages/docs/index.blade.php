@extends('layouts.app')
@section('title', 'Documentation - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Documentation</h1>
        <p class="text-xl mb-8">Everything you need to know about CulturalTranslate</p>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('docs.show', 'getting-started') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <i class="fas fa-rocket text-indigo-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Getting Started</h3>
                <p class="text-gray-600">Quick start guide for new users</p>
            </a>
            <a href="{{ route('api-docs') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <i class="fas fa-code text-purple-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">API Reference</h3>
                <p class="text-gray-600">Complete API documentation</p>
            </a>
            <a href="{{ route('docs.show', 'integrations') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <i class="fas fa-plug text-blue-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Integrations</h3>
                <p class="text-gray-600">Connect with your favorite tools</p>
            </a>
        </div>
    </div>
</section>
@endsection
