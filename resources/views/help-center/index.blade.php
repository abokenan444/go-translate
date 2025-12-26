@extends('layouts.app')

@section('title', 'Help Center - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">📚 Help Center</h1>
            <p class="text-xl text-gray-600 mb-8">Find answers to your questions</p>
            
            <!-- Search Bar -->
            <form action="{{ route('help-center.search') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" name="q" placeholder="Search for articles..." 
                           class="w-full px-6 py-4 rounded-full border-2 border-purple-300 focus:border-purple-600 focus:outline-none text-lg">
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Popular Articles -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">⭐ Popular Articles</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($popularArticles as $article)
                <a href="{{ route('help-center.article', 'article-slug') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <span class="inline-block bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                        {{ $article['category'] }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $article['title'] }}</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($article['views']) }} views</p>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Categories -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">📖 Browse by Category</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">{{ $category['icon'] }}</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $category['name'] }}</h3>
                    <ul class="space-y-3">
                        @foreach($category['articles'] as $article)
                        <li>
                            <a href="{{ route('help-center.article', 'article-slug') }}" class="text-purple-600 hover:text-purple-800 flex justify-between items-center">
                                <span>{{ $article['title'] }}</span>
                                <span class="text-gray-400 text-sm">{{ number_format($article['views']) }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-16 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Still Need Help?</h2>
            <p class="text-xl mb-6">Our support team is here to help you</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('contact') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Contact Support
                </a>
                <a href="mailto:support@culturaltranslate.com" class="bg-purple-700 px-8 py-3 rounded-lg font-semibold hover:bg-purple-800 transition">
                    Email Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
