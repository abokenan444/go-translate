<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->title }} - Cultural Translate</title>
    <meta name="description" content="{{ $page->meta_description ?? '' }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <a href="/" class="text-2xl font-bold text-indigo-600">Cultural Translate</a>
            </div>
        </header>

        <!-- Content -->
        <main class="container mx-auto px-4 py-12">
            <article class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
                {!! $page->content !!}
            </article>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8 mt-12">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
