<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help-center - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    
    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Help-center</h1>
        <div class="bg-white rounded-lg shadow-sm p-8">
            <p class="text-gray-700">This page is under construction.</p>
        </div>
    </div>
    
    @include('components.footer')
</body>
</html>
