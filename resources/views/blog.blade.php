@extends('layouts.app')

{{-- SEO Meta Tags --}}
@section('title', 'Blog - CulturalTranslate: Insights on Translation, AI, and Localization')

@section('meta')
    <meta name="description" content="Explore the latest articles and insights from CulturalTranslate on the future of translation, artificial intelligence in language, and global localization strategies.">
    <meta name="keywords" content="translation, AI, localization, blog, CulturalTranslate, language technology, featured articles">
    <meta property="og:title" content="CulturalTranslate Blog">
    <meta property="og:description" content="Your source for the latest insights on translation, AI, and localization strategies.">
    <meta property="og:type" content="website">
    {{-- Add more Open Graph and Twitter Card tags as needed --}}
@endsection

@section('content')
    <main class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
        {{-- Blog Header/Hero Section --}}
        <header class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                CulturalTranslate Blog
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Your source for the latest insights on <strong class="text-indigo-600 dark:text-indigo-400">translation</strong>، <strong class="text-indigo-600 dark:text-indigo-400">AI</strong>، and <strong class="text-indigo-600 dark:text-indigo-400">localization</strong> strategies.
            </p>
        </header>

        {{-- Featured Article Section (Large Card) --}}
        <section class="mb-20">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-b-2 border-indigo-500 pb-2">
                Featured Insight
            </h2>
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-xl overflow-hidden transition duration-300 hover:shadow-indigo-500/50 md:flex">
                <div class="md:w-1/2">
                    {{-- Placeholder for Featured Image --}}
                    <a href="#" class="block">
                        <div class="h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                            <span class="text-lg font-medium">Featured Article Image Placeholder</span>
                        </div>
                    </a>
                </div>
                <div class="p-8 md:w-1/2 flex flex-col justify-center">
                    <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">
                        AI & Localization
                    </span>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition duration-150 ease-in-out">
                            The Rise of Generative AI in Cultural Adaptation
                        </a>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        An in-depth look at how generative AI models are revolutionizing the localization process, from content creation to cultural nuance detection.
                    </p>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <span class="mr-4">By Jane Doe</span>
                        <span>October 26, 2025</span>
                    </div>
                    <a href="#" class="mt-6 inline-block text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-500 transition duration-150 ease-in-out">
                        Read Article &rarr;
                    </a>
                </div>
            </div>
        </section>

        {{-- Main Blog Post Grid --}}
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-b-2 border-gray-200 dark:border-gray-700 pb-2">
                Latest Articles
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                {{-- Article Card 1: Translation --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="text-lg font-medium">Translation Image Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2 block">
                            Translation
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            The Art of Transcreation: Beyond Word-for-Word
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Discover how transcreation ensures your message resonates culturally, not just linguistically, across global markets.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By John Smith</span>
                            <span>Sept 15, 2025</span>
                        </div>
                    </div>
                </article>

                {{-- Article Card 2: AI --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400">
                            <span class="text-lg font-medium">AI Image Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider mb-2 block">
                            Artificial Intelligence
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            Leveraging LLMs for High-Quality Machine Translation
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            A guide to integrating Large Language Models (LLMs) into your translation workflow for speed and accuracy.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By Sarah Lee</span>
                            <span>Aug 20, 2025</span>
                        </div>
                    </div>
                </article>

                {{-- Article Card 3: Localization --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-red-100 dark:bg-red-900 flex items-center justify-center text-red-600 dark:text-red-400">
                            <span class="text-lg font-medium">Localization Image Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider mb-2 block">
                            Localization
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            Beyond Language: Localizing Your Product for the MENA Region
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Key considerations and best practices for a successful product launch in the Middle East and North Africa.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By Omar Hassan</span>
                            <span>July 10, 2025</span>
                        </div>
                    </div>
                </article>

                {{-- Add more articles as needed for a full page --}}
                {{-- Article Card 4: Translation Tools --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                            <span class="text-lg font-medium">Tools Image Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 uppercase tracking-wider mb-2 block">
                            Tools & Tech
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            Choosing the Right CAT Tool for Your Team
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            A comparative review of the top Computer-Assisted Translation (CAT) tools available today.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By Alex Chen</span>
                            <span>June 5, 2025</span>
                        </div>
                    </div>
                </article>

                {{-- Article Card 5: Cultural Nuance --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-400">
                            <span class="text-lg font-medium">Culture Image Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-2 block">
                            Cultural Insights
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            The Impact of Color in Global Marketing Campaigns
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Understanding the cultural significance of colors to avoid costly localization mistakes.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By Jane Doe</span>
                            <span>May 1, 2025</span>
                        </div>
                    </div>
                </article>

                {{-- Article Card 6: Future of AI --}}
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <a href="#" class="block">
                        <div class="h-48 bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <span class="text-lg font-medium">Future AI Placeholder</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2 block">
                            Future of AI
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            Ethical Considerations in AI-Powered Translation
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Examining the ethical framework required for responsible use of AI in sensitive translation projects.
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>By John Smith</span>
                            <span>Apr 12, 2025</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        {{-- Pagination Section (Responsive) --}}
        <section class="flex justify-center mt-12">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span class="sr-only">Previous</span>
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" aria-current="page" class="z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 text-indigo-600 dark:text-indigo-400 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    1
                </a>
                <a href="#" class="bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    2
                </a>
                <a href="#" class="hidden md:inline-flex bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 relative items-center px-4 py-2 border text-sm font-medium">
                    3
                </a>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300">
                    ...
                </span>
                <a href="#" class="hidden md:inline-flex bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 relative items-center px-4 py-2 border text-sm font-medium">
                    10
                </a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span class="sr-only">Next</span>
                    <!-- Heroicon name: solid/chevron-right -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
        </section>

    </main>
@endsection
