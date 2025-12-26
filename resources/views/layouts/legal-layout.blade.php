<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    
    <title>@yield('title', 'CulturalTranslate')</title>
    <meta name="description" content="@yield('description', 'Cultural Translation Platform')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'CulturalTranslate')">
    <meta property="og:description" content="@yield('description', 'Cultural Translation Platform')">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">CulturalTranslate</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="/features" class="text-gray-700 hover:text-blue-600">Features</a>
                    <a href="/pricing" class="text-gray-700 hover:text-blue-600">Pricing</a>
                    <a href="/login" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="/signup" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-400">
                <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
                <div class="mt-4 space-x-4">
                    <a href="/privacy-policy" class="hover:text-white">Privacy</a>
                    <a href="/terms-of-service" class="hover:text-white">Terms</a>
                    <a href="/security" class="hover:text-white">Security</a>
                    <a href="/contact" class="hover:text-white">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
