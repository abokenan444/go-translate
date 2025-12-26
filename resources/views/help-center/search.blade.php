@extends('layouts.app')

@section('title', 'Search Results - Help Center')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-800">Home</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('help-center.index') }}" class="text-purple-600 hover:text-purple-800">Help Center</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">Search Results</li>
            </ol>
        </nav>

        <div class="max-w-4xl mx-auto">
            <!-- Search Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">🔍 Search Results</h1>
                <p class="text-xl text-gray-600 mb-8">
                    Found <strong>{{ count($results) }}</strong> results for "<strong>{{ $query }}</strong>"
                </p>
                
                <!-- Search Bar -->
                <form action="{{ route('help-center.search') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $query }}" placeholder="Search for articles..." 
                               class="w-full px-6 py-4 rounded-full border-2 border-purple-300 focus:border-purple-600 focus:outline-none text-lg">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            @if(count($results) > 0)
                <div class="space-y-6">
                    @foreach($results as $result)
                    <a href="{{ route('help-center.article', 'article-slug') }}" 
                       class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-3">
                            <span class="inline-block bg-purple-100 text-purple-800 text-sm font-semibold px-4 py-2 rounded-full">
                                {{ $result['category'] }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $result['title'] }}</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $result['excerpt'] }}</p>
                        <div class="mt-4 text-purple-600 font-semibold flex items-center">
                            Read more 
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-6">🔍</div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">No results found</h2>
                    <p class="text-gray-600 text-lg mb-8">
                        We couldn't find any articles matching "{{ $query }}"
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('help-center.index') }}" 
                           class="bg-purple-600 text-white font-semibold px-8 py-3 rounded-full hover:bg-purple-700 transition">
                            Browse All Articles
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="bg-gray-200 text-gray-800 font-semibold px-8 py-3 rounded-full hover:bg-gray-300 transition">
                            Contact Support
                        </a>
                    </div>
                </div>
            @endif

            <!-- Popular Categories -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">📖 Browse by Category</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-1">
                        <div class="text-4xl mb-3">🚀</div>
                        <h3 class="text-xl font-bold text-gray-900">Getting Started</h3>
                    </a>
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-1">
                        <div class="text-4xl mb-3">✅</div>
                        <h3 class="text-xl font-bold text-gray-900">Certification</h3>
                    </a>
                    <a href="#" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition transform hover:-translate-y-1">
                        <div class="text-4xl mb-3">🌍</div>
                        <h3 class="text-xl font-bold text-gray-900">Translation Services</h3>
                    </a>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-lg p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Didn't find what you're looking for?</h2>
                <p class="text-lg mb-6">Contact our support team for personalized assistance</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-purple-600 font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
