@extends('layouts.app')
@section('title', 'Help Center - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Help Center</h1>
        <p class="text-xl mb-8">Find answers to your questions</p>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <i class="fas fa-book text-indigo-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Getting Started</h3>
                <p class="text-gray-600 mb-4">Learn the basics of using CulturalTranslate</p>
                <a href="{{ route('docs.index') }}" class="text-indigo-600 hover:underline">Read Guide →</a>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <i class="fas fa-code text-purple-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">API Documentation</h3>
                <p class="text-gray-600 mb-4">Integrate our API into your applications</p>
                <a href="{{ route('api-docs') }}" class="text-indigo-600 hover:underline">View Docs →</a>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <i class="fas fa-question-circle text-blue-600 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">FAQs</h3>
                <p class="text-gray-600 mb-4">Frequently asked questions</p>
                <a href="{{ route('contact') }}" class="text-indigo-600 hover:underline">Contact Support →</a>
            </div>
        </div>
    </div>
</section>
@endsection
