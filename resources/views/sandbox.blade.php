@extends('layouts.app')

@section('title', 'API Sandbox - Cultural Translate')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">API Sandbox</h1>
            <p class="text-xl mb-8">Test our Translation API in a safe, isolated environment</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Get API Key
                </a>
                <a href="{{ route('developers') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                    Documentation
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sandbox Features -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Sandbox Features</h2>
            
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-bold mb-3">Isolated Environment</h3>
                    <p class="text-gray-600">Test without affecting production data</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold mb-3">Real-time Testing</h3>
                    <p class="text-gray-600">Instant API responses and debugging</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold mb-3">Request Logging</h3>
                    <p class="text-gray-600">Track all API calls and responses</p>
                </div>
            </div>

            <!-- Interactive Playground -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-6">Try It Now</h3>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Request Panel -->
                    <div>
                        <h4 class="font-semibold mb-4">Request</h4>
                        <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
<pre>POST /api/v1/translate
Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

{
  "text": "Hello, world!",
  "source_language": "en",
  "target_language": "ar",
  "tone": "professional"
}</pre>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Text to Translate</label>
                            <textarea class="w-full border rounded-lg p-3" rows="3" placeholder="Enter text...">Hello, world!</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Source Language</label>
                                <select class="w-full border rounded-lg p-2">
                                    <option value="en">English</option>
                                    <option value="ar">Arabic</option>
                                    <option value="es">Spanish</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Target Language</label>
                                <select class="w-full border rounded-lg p-2">
                                    <option value="ar">Arabic</option>
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                </select>
                            </div>
                        </div>
                        
                        <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                            Send Request
                        </button>
                    </div>
                    
                    <!-- Response Panel -->
                    <div>
                        <h4 class="font-semibold mb-4">Response</h4>
                        <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
<pre>{
  "success": true,
  "data": {
    "translated_text": "مرحباً بالعالم!",
    "source_language": "en",
    "target_language": "ar",
    "tone": "professional",
    "confidence": 0.98
  },
  "meta": {
    "request_id": "req_abc123",
    "processing_time": "0.245s"
  }
}</pre>
                        </div>
                        
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center gap-2 text-green-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">200 OK</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Request completed successfully</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rate Limits -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-semibold text-blue-900 mb-3">Sandbox Rate Limits</h4>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Requests per minute:</span>
                        <span class="font-bold text-blue-900 ml-2">100</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Max text length:</span>
                        <span class="font-bold text-blue-900 ml-2">5,000 chars</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Concurrent requests:</span>
                        <span class="font-bold text-blue-900 ml-2">10</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Code Examples -->
<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Quick Start Examples</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- cURL Example -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="text-gray-700">cURL</span>
                    </h3>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
<pre>curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, world!",
    "source_language": "en",
    "target_language": "ar"
  }'</pre>
                    </div>
                </div>
                
                <!-- JavaScript Example -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="text-gray-700">JavaScript</span>
                    </h3>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
<pre>const response = await fetch(
  'https://api.culturaltranslate.com/v1/translate',
  {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_API_KEY',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      text: 'Hello, world!',
      source_language: 'en',
      target_language: 'ar'
    })
  }
);
const data = await response.json();</pre>
                    </div>
                </div>
                
                <!-- Python Example -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="text-gray-700">Python</span>
                    </h3>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
<pre>import requests

response = requests.post(
    'https://api.culturaltranslate.com/v1/translate',
    headers={
        'Authorization': 'Bearer YOUR_API_KEY',
        'Content-Type': 'application/json'
    },
    json={
        'text': 'Hello, world!',
        'source_language': 'en',
        'target_language': 'ar'
    }
)
data = response.json()</pre>
                    </div>
                </div>
                
                <!-- PHP Example -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="text-gray-700">PHP</span>
                    </h3>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
<pre>$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 
  'https://api.culturaltranslate.com/v1/translate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer YOUR_API_KEY',
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
  'text' => 'Hello, world!',
  'source_language' => 'en',
  'target_language' => 'ar'
]));
$response = curl_exec($ch);</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Building?</h2>
        <p class="text-xl mb-8">Get your API key and start translating in minutes</p>
        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
            Get Started Free
        </a>
    </div>
</div>
@endsection
