@extends('layouts.app')

@section('title', 'Press - CulturalTranslate')

@section('meta_tags')
    <meta name="description" content="Official press releases, media kit, and company news for CulturalTranslate.">
    <meta name="keywords" content="CulturalTranslate, press, media, news, press releases, media kit, company news, translation, localization">
    <meta property="og:title" content="Press - CulturalTranslate">
    <meta property="og:description" content="Official press releases, media kit, and company news for CulturalTranslate.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/press') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Press - CulturalTranslate">
    <meta name="twitter:description" content="Official press releases, media kit, and company news for CulturalTranslate.">
    <link rel="canonical" href="{{ url('/press') }}">
@endsection

@section('content')
    <!-- Tailwind CSS classes for modern, clean design -->
    <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">

        <!-- Hero Section -->
        <header class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 sm:text-6xl lg:text-7xl tracking-tight">
                CulturalTranslate Press Center
            </h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Stay up-to-date with the latest news, announcements, and media resources from CulturalTranslate.
            </p>
        </header>

        <!-- Press Releases Section -->
        <section class="mb-20">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-2 border-indigo-500 pb-2">
                Latest Press Releases
            </h2>
            <div class="space-y-10">
                <!-- Release 1 -->
                <article class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <p class="text-sm text-indigo-600 font-semibold mb-2">October 26, 2025</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                        CulturalTranslate Secures $10M in Series A Funding to Expand Global Reach
                    </h3>
                    <p class="text-gray-700 mb-4">
                        The new funding round, led by Global Ventures, will accelerate the development of our AI-powered cultural context engine and support expansion into new markets across Asia and Europe.
                    </p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 ease-in-out">
                        Read Full Release &rarr;
                    </a>
                </article>

                <!-- Release 2 -->
                <article class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <p class="text-sm text-indigo-600 font-semibold mb-2">September 15, 2025</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                        Introducing Contextual AI: The Next Generation of Localization
                    </h3>
                    <p class="text-gray-700 mb-4">
                        CulturalTranslate launches its proprietary Contextual AI, offering unprecedented accuracy in translating nuances, idioms, and cultural references for global brands.
                    </p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 ease-in-out">
                        Read Full Release &rarr;
                    </a>
                </article>

                <!-- Release 3 -->
                <article class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <p class="text-sm text-indigo-600 font-semibold mb-2">August 1, 2025</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                        CulturalTranslate Named "Startup to Watch" by TechGlobal Magazine
                    </h3>
                    <p class="text-gray-700 mb-4">
                        Recognized for its innovative approach to language and cultural barriers, CulturalTranslate is highlighted as a leader in the future of global communication technology.
                    </p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 ease-in-out">
                        Read Full Release &rarr;
                    </a>
                </article>
            </div>
        </section>

        <!-- Media Kit Section -->
        <section class="mb-20">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-2 border-indigo-500 pb-2">
                Media Kit & Resources
            </h2>
            <p class="text-lg text-gray-700 mb-8">
                Access our official brand assets, executive biographies, and company fact sheet for your coverage.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Resource 1: Logos -->
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-900">Official Logos</h3>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Download high-resolution logos in various formats (PNG, SVG) for print and digital use.
                    </p>
                    <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                        Download Logo Pack (ZIP)
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>

                <!-- Resource 2: Brand Guidelines -->
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-900">Brand Guidelines</h3>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Our official guide on color palettes, typography, and proper usage of the CulturalTranslate brand.
                    </p>
                    <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                        Download PDF
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>

                <!-- Resource 3: Fact Sheet -->
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-900">Company Fact Sheet</h3>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Key statistics, mission statement, executive bios, and company history in a concise document.
                    </p>
                    <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                        Download PDF
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Media Contact Section -->
        <section class="text-center p-10 bg-indigo-50 rounded-lg shadow-inner">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Media Inquiries
            </h2>
            <p class="text-lg text-gray-700 mb-6 max-w-2xl mx-auto">
                For all press-related questions, interview requests, or partnership opportunities, please contact our dedicated media relations team.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="text-left">
                    <p class="text-xl font-semibold text-indigo-600">Email:</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <a href="mailto:press@culturaltranslate.com" class="hover:text-indigo-800 transition duration-150">
                            press@culturaltranslate.com
                        </a>
                    </p>
                </div>
                <div class="hidden sm:block text-gray-400 text-3xl">|</div>
                <div class="text-left">
                    <p class="text-xl font-semibold text-indigo-600">Phone:</p>
                    <p class="text-2xl font-bold text-gray-900">
                        +1 (555) 123-4567
                    </p>
                </div>
            </div>
        </section>

    </div>
@endsection
