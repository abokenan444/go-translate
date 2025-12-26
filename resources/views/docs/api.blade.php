@extends('layouts.app')
@section('title', __('API Documentation'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('API Documentation') }}</h1>
            <p class="text-xl">{{ __('Integrate CulturalTranslate into your applications') }}</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 class="font-semibold mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#authentication" class="text-indigo-600 hover:underline">{{ __('Authentication') }}</a></li>
                        <li><a href="#endpoints" class="text-indigo-600 hover:underline">{{ __('Endpoints') }}</a></li>
                        <li><a href="#examples" class="text-indigo-600 hover:underline">{{ __('Examples') }}</a></li>
                        <li><a href="#errors" class="text-indigo-600 hover:underline">{{ __('Error Handling') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="lg:col-span-3 space-y-8">
                <div id="authentication" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold mb-4">{{ __('Authentication') }}</h2>
                    <p class="text-gray-700 mb-4">{{ __('All API requests require authentication using an API key. Include your API key in the Authorization header:') }}</p>
                    <pre class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto"><code>Authorization: Bearer YOUR_API_KEY</code></pre>
                </div>
                <div id="endpoints" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold mb-4">{{ __('API Endpoints') }}</h2>
                    <div class="space-y-4">
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="font-mono text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">POST</span> /api/v1/translate</p>
                            <p class="text-gray-700 text-sm mt-1">{{ __('Translate text or documents') }}</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="font-mono text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">GET</span> /api/v1/languages</p>
                            <p class="text-gray-700 text-sm mt-1">{{ __('Get supported languages') }}</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="font-mono text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">GET</span> /api/v1/status/{id}</p>
                            <p class="text-gray-700 text-sm mt-1">{{ __('Check translation status') }}</p>
                        </div>
                    </div>
                </div>
                <div id="examples" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold mb-4">{{ __('Example Request') }}</h2>
                    <pre class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto text-sm"><code>curl -X POST https://culturaltranslate.com/api/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, world!",
    "source_language": "en",
    "target_language": "ar",
    "cultural_adaptation": true
  }'</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
