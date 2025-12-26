<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultural Translate Documentation</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-xl text-indigo-600">Cultural Translate Docs</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16">
        <!-- Sidebar -->
        <div class="w-64 bg-white h-screen fixed border-r border-gray-200 overflow-y-auto hidden md:block">
            <div class="p-6">
                <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mb-4">Getting Started</h3>
                <ul class="space-y-2">
                    <li><a href="/docs" class="text-gray-900 hover:text-indigo-600 font-medium">Introduction</a></li>
                    <li><a href="/docs/authentication" class="text-gray-600 hover:text-indigo-600">Authentication</a></li>
                    <li><a href="/docs/quickstart" class="text-gray-600 hover:text-indigo-600">Quick Start</a></li>
                </ul>
                
                <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mt-8 mb-4">API Reference</h3>
                <ul class="space-y-2">
                    <li><a href="/docs/api-translate" class="text-gray-600 hover:text-indigo-600">Translate</a></li>
                    <li><a href="/docs/api-quality" class="text-gray-600 hover:text-indigo-600">Quality Score</a></li>
                </ul>

                <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mt-8 mb-4">SDKs</h3>
                <ul class="space-y-2">
                    <li><a href="/docs/sdk-php" class="text-gray-600 hover:text-indigo-600">PHP</a></li>
                    <li><a href="/docs/sdk-python" class="text-gray-600 hover:text-indigo-600">Python</a></li>
                    <li><a href="/docs/sdk-js" class="text-gray-600 hover:text-indigo-600">JavaScript</a></li>
                </ul>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 md:ml-64 p-8">
            <div class="max-w-3xl mx-auto prose">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
