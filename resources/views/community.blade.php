@extends('layouts.app')

@section('title', 'Community - CulturalTranslate')

@section('meta_tags')
    <meta name="description" content="Join the CulturalTranslate community for forums, discussions, and support. Connect with language enthusiasts and experts.">
    <meta name="keywords" content="CulturalTranslate community, language forums, translation discussion, user support, language exchange">
    <meta property="og:title" content="Community - CulturalTranslate">
    <meta property="og:description" content="Join the CulturalTranslate community for forums, discussions, and support. Connect with language enthusiasts and experts.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/community') }}">
    <meta name="twitter:card" content="summary_large_image">
@endsection

@section('content')
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Hero Section --}}
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl">
                    CulturalTranslate <span class="text-indigo-600">Community</span>
                </h1>
                <p class="mt-4 text-xl text-gray-600">
                    Connect with language enthusiasts, share knowledge, and get support.
                </p>
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="#forums" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out">
                        Explore Forums
                    </a>
                    <a href="#guidelines" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                        Community Guidelines
                    </a>
                </div>
            </div>

            {{-- Key Sections/Topics --}}
            <div id="forums" class="mt-20">
                <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                    Popular Discussion Topics
                </h2>
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Topic Card 1: General Discussion --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl transition duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4m-7 0L3 11m3 3h4m-4 0v4m-6-4h6m-6 0v4m6-4v4"/>
                                </svg>
                                <h3 class="ml-4 text-xl font-medium text-gray-900">General Chat</h3>
                            </div>
                            <p class="mt-4 text-gray-500">
                                Talk about anything related to language, culture, and the CulturalTranslate platform.
                            </p>
                            <div class="mt-6">
                                <a href="#" class="text-base font-medium text-indigo-600 hover:text-indigo-500">
                                    View Threads &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Topic Card 2: Translation Help --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl transition duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <h3 class="ml-4 text-xl font-medium text-gray-900">Translation Help</h3>
                            </div>
                            <p class="mt-4 text-gray-500">
                                Post your tricky translation questions and get advice from the community's experts.
                            </p>
                            <div class="mt-6">
                                <a href="#" class="text-base font-medium text-green-600 hover:text-green-500">
                                    View Threads &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Topic Card 3: Feature Requests & Feedback --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl transition duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343 5.657l-.707.707m2.828-2.828l-.707.707m2.828-2.828l-.707.707M12 20h.01M12 7v3m0 4h.01"/>
                                </svg>
                                <h3 class="ml-4 text-xl font-medium text-gray-900">Feedback & Ideas</h3>
                            </div>
                            <p class="mt-4 text-gray-500">
                                Share your ideas for new features and provide feedback on the platform.
                            </p>
                            <div class="mt-6">
                                <a href="#" class="text-base font-medium text-yellow-600 hover:text-yellow-500">
                                    View Threads &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Latest Activity/Threads (Placeholder) --}}
            <div class="mt-20">
                <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                    Latest Community Activity
                </h2>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        {{-- Thread Item 1 --}}
                        <li class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    <a href="#" class="hover:underline">What are the best resources for learning Moroccan Arabic?</a>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        General Chat
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-5 5h10a5 5 0 00-5-5z" clip-rule="evenodd" />
                                        </svg>
                                        Posted by: User_A
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <p>
                                        Last reply: <time datetime="2025-12-09">1 hour ago</time>
                                    </p>
                                </div>
                            </div>
                        </li>
                        {{-- Thread Item 2 --}}
                        <li class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    <a href="#" class="hover:underline">Bug Report: Incorrect character encoding in Arabic translation</a>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Feedback & Ideas
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-5 5h10a5 5 0 00-5-5z" clip-rule="evenodd" />
                                        </svg>
                                        Posted by: Dev_User
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <p>
                                        Last reply: <time datetime="2025-12-09">3 hours ago</time>
                                    </p>
                                </div>
                            </div>
                        </li>
                        {{-- Thread Item 3 --}}
                        <li class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    <a href="#" class="hover:underline">How to translate idiomatic expressions in Japanese?</a>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Translation Help
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-5 5h10a5 5 0 00-5-5z" clip-rule="evenodd" />
                                        </svg>
                                        Posted by: Language_Lover
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <p>
                                        Last reply: <time datetime="2025-12-09">5 hours ago</time>
                                    </p>
                                </div>
                            </div>
                        </li>
                        {{-- Add a link to view all threads --}}
                        <li class="px-4 py-4 sm:px-6 text-center bg-gray-100">
                            <a href="#" class="text-base font-medium text-indigo-600 hover:text-indigo-500">
                                View All Threads &rarr;
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Call to Action: Start a Discussion --}}
            <div class="mt-20 bg-white shadow-xl rounded-lg p-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Ready to Join the Conversation?
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Ask a question, share your knowledge, or start a new discussion today.
                </p>
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out text-lg">
                        Start a New Thread
                    </a>
                </div>
            </div>

            {{-- Community Guidelines Section --}}
            <div id="guidelines" class="mt-20">
                <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                    Community Guidelines
                </h2>
                <div class="max-w-3xl mx-auto text-gray-700 space-y-6 text-lg">
                    <p>
                        Our community thrives on respect and a shared passion for language and culture. Please take a moment to review our core guidelines before participating.
                    </p>
                    <ul class="list-disc list-inside space-y-3 pl-5">
                        <li><strong>Be Respectful:</strong> Treat all members with kindness and courtesy, even when you disagree.</li>
                        <li><strong>Stay On Topic:</strong> Keep discussions relevant to language, translation, culture, or the CulturalTranslate platform.</li>
                        <li><strong>No Spam or Self-Promotion:</strong> Do not post unsolicited advertisements or excessive self-promotional content.</li>
                        <li><strong>Help Others:</strong> Share your expertise and assist fellow members with their questions and challenges.</li>
                    </ul>
                    <p class="text-center pt-4">
                        <a href="#" class="text-base font-medium text-indigo-600 hover:text-indigo-500">
                            Read the Full Code of Conduct &rarr;
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
