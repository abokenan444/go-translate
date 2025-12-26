<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'GoTranslate Documentation')</title>
    <meta name="description" content="GoTranslate — AI-Powered Cultural Translation Platform Documentation">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose h1 { font-size: 2.25rem; font-weight: 700; margin-bottom: 1.5rem; color: #111827; }
        .prose h2 { font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937; }
        .prose p { margin-bottom: 1rem; color: #4b5563; line-height: 1.75; }
        .prose pre { background-color: #1f2937; color: #f3f4f6; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; }
        .prose code { font-family: monospace; background-color: #f3f4f6; padding: 0.2rem 0.4rem; border-radius: 0.25rem; color: #ef4444; }
        .prose pre code { background-color: transparent; color: inherit; padding: 0; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #4b5563; }
        .prose li { margin-bottom: 0.5rem; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-blue-600">GoTranslate</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/docs" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Documentation
                        </a>
                        <a href="/api-docs" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            API Reference
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 flex-shrink-0">
                <nav class="sticky top-24 space-y-1">
                    <div class="pb-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Getting Started</h3>
                        <a href="/docs/index" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Introduction</a>
                        <a href="/docs/installation" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Installation</a>
                        <a href="/docs/configuration" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Configuration</a>
                    </div>
                    <div class="pb-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Core Concepts</h3>
                        <a href="/docs/projects" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Projects</a>
                        <a href="/docs/translations" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Translations</a>
                        <a href="/docs/glossary" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Glossary</a>
                    </div>
                    <div class="pb-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Advanced</h3>
                        <a href="/docs/api" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">API Usage</a>
                        <a href="/docs/webhooks" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">Webhooks</a>
                        <a href="/docs/cli" class="block px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900">CLI Tool</a>
                    </div>
                </nav>
            </aside>

            <!-- Content -->
            <main class="flex-1 min-w-0">
                <div class="bg-white shadow sm:rounded-lg p-8 prose max-w-none">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
