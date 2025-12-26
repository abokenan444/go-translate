@extends('layouts.app')

@section('title', $article['title'] . ' - Help Center')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-800">Home</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('help-center') }}" class="text-purple-600 hover:text-purple-800">Help Center</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">{{ $article['title'] }}</li>
            </ol>
        </nav>

        <div class="max-w-4xl mx-auto">
            <!-- Article Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <span class="inline-block bg-purple-100 text-purple-800 text-sm font-semibold px-4 py-2 rounded-full mb-4">
                    {{ $article['category'] }}
                </span>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $article['title'] }}</h1>
                <div class="flex items-center text-gray-600 text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Last updated: {{ $article['updated_at']->format('F j, Y') }}
                </div>
            </div>

            <!-- Article Content -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="prose prose-lg max-w-none">
                    {!! $article['content'] !!}
                </div>
            </div>

            <!-- Helpful Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Was this article helpful?</h3>
                <div class="flex justify-center space-x-4">
                    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-full transition">
                        👍 Yes
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold px-8 py-3 rounded-full transition">
                        👎 No
                    </button>
                </div>
            </div>

            <!-- Related Articles -->
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">📚 Related Articles</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:-translate-y-1">
                        <span class="inline-block bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                            Certification
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">What is CTS certification?</h3>
                        <p class="text-gray-600 text-sm">3,200 views</p>
                    </a>
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:-translate-y-1">
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                            Translation
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Certificate validity period</h3>
                        <p class="text-gray-600 text-sm">1,450 views</p>
                    </a>
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:-translate-y-1">
                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                            Process
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Quality assurance process</h3>
                        <p class="text-gray-600 text-sm">1,200 views</p>
                    </a>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-lg p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Still need help?</h2>
                <p class="text-lg mb-6">Our support team is here to assist you</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-purple-600 font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
