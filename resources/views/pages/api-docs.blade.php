@extends('layouts.app')

@section('title', __('pages.api_docs.title'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="sticky top-4 space-y-2">
                    <a href="#getting-started" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Getting Started</a>
                    <a href="#authentication" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Authentication</a>
                    <a href="#translation" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Translation API</a>
                    <a href="#voice" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Voice Translation</a>
                    <a href="#visual" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Visual Translation</a>
                    <a href="#errors" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Error Handling</a>
                </div>
            </div>

            <!-- Content -->
            <div class="md:col-span-3 space-y-12">
                
                <!-- Getting Started -->
                <section id="getting-started" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.getting_started') }}</h2>
                    <p class="text-gray-600 mb-6">{{ __('pages.api_docs.getting_started_intro') }}</p>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Base URL</div>
                        <code class="text-indigo-600">https://api.culturaltranslate.com/v1</code>
                        <p class="text-sm text-gray-600 mt-2">All endpoints are versioned and return JSON. Use HTTPS.</p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4">
                        <p class="text-sm text-blue-900">
                            <strong>Note:</strong> All API requests must be made over HTTPS. Requests over HTTP will fail.
                        </p>
                    </div>
                </section>

                <!-- Authentication -->
                <section id="authentication" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.authentication') }}</h2>
                    <p class="text-gray-600 mb-6">
                        Authenticate your API requests using Bearer tokens. You can generate API keys from your dashboard.
                    </p>
                                        <pre class="language-bash"><code>curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"text": "Hello", "target_language": "ar"}'</code></pre>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div>
<pre class="language-javascript"><code>await fetch('https://api.culturaltranslate.com/v1/translate', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer YOUR_API_KEY',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({ text: 'Hello', target_language: 'ar' })
});</code></pre>
                                                </div>
                                                <div>
<pre class="language-php"><code>$client = new \GuzzleHttp\Client(['base_uri' => 'https://api.culturaltranslate.com']);
$res = $client->post('/v1/translate', [
    'headers' => [ 'Authorization' => 'Bearer YOUR_API_KEY' ],
    'json' => [ 'text' => 'Hello', 'target_language' => 'ar' ]
]);
echo $res->getBody();</code></pre>
                                                </div>
                                        </div>
                </section>

                <!-- Translation API -->
                <section id="translation" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.translation_api') }}</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.translate_text') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('pages.api_docs.translate_text_intro') }}</p>
                    
                    <div class="mb-6">
                        <div class="bg-gray-50 rounded-t-lg px-4 py-2 border-b border-gray-200">
                            <code class="text-sm font-mono">POST /v1/translate</code>
                            <span class="ml-2 text-xs text-gray-500">Rate limit: 60 req/min</span>
                        </div>
                        <div class="bg-gray-900 rounded-b-lg p-4">
                            <pre class="language-javascript"><code>{
  "text": "Hello, world!",
  "source_language": "en",
  "target_language": "ar",
  "ai_model": "gpt-4",
  "cultural_adaptation": true,
  "preserve_brand_voice": true,
  "formal_tone": false
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-600">Use <code>cultural_adaptation</code> to apply region-specific idioms and tone.</div>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('pages.api_docs.response') }}</h4>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>{
  "success": true,
  "data": {
    "translated_text": "مرحباً بالعالم!",
    "source_language": "en",
    "target_language": "ar",
    "characters_used": 13,
    "quality_score": 0.95
  }
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Responses include quality metrics and usage counters.</div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.batch_translation') }}</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/translate/batch

{
  "texts": ["Hello", "Goodbye", "Thank you"],
  "source_language": "en",
  "target_language": "ar"
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Batch requests process texts sequentially and return an array of translations.</div>
                    </div>
                </section>

                <!-- Voice Translation -->
                <section id="voice" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.voice_translation') }}</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.speech_to_text') }}</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>POST /v1/voice/speech-to-text

{
  "audio_url": "https://example.com/audio.mp3",
  "source_language": "en"
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Supports Whisper, Azure Speech, and Deepgram. See <code>services.asr</code> config.</div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.text_to_speech') }}</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/voice/text-to-speech

{
  "text": "Hello, world!",
  "language": "en",
  "voice": "male",
  "cultural_tone": true
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Voices depend on provider (Azure, Google, ElevenLabs). See <code>services.tts</code>.</div>
                    </div>
                </section>

                <!-- Visual Translation -->
                <section id="visual" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.visual_translation') }}</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.translate_image') }}</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>POST /v1/visual/translate-image

{
  "image_url": "https://example.com/image.jpg",
  "target_language": "ar",
  "preserve_layout": true
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Enable layout preservation for screenshots and UI mocks.</div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pages.api_docs.translate_document') }}</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/visual/translate-document

{
  "document_url": "https://example.com/doc.pdf",
  "target_language": "ar",
  "format": "pdf"
}</code></pre>
                                                <div class="mt-3 text-sm text-gray-300">Documents support PDF, DOCX, and images. Long PDFs can be processed async.</div>
                    </div>
                </section>

                <!-- Error Handling -->
                <section id="errors" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('pages.api_docs.error_handling') }}</h2>
                    <p class="text-gray-600 mb-6">{{ __('pages.api_docs.error_handling_intro') }}</p>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-green-600 bg-green-50 p-4">
                            <div class="font-semibold text-green-900">200 - OK</div>
                            <div class="text-sm text-green-700">Request succeeded</div>
                        </div>
                        <div class="border-l-4 border-yellow-600 bg-yellow-50 p-4">
                            <div class="font-semibold text-yellow-900">400 - Bad Request</div>
                            <div class="text-sm text-yellow-700">Invalid request parameters</div>
                        </div>
                        <div class="border-l-4 border-red-600 bg-red-50 p-4">
                            <div class="font-semibold text-red-900">401 - Unauthorized</div>
                            <div class="text-sm text-red-700">Invalid or missing API key</div>
                        </div>
                        <div class="border-l-4 border-red-600 bg-red-50 p-4">
                            <div class="font-semibold text-red-900">429 - Too Many Requests</div>
                            <div class="text-sm text-red-700">Rate limit exceeded</div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 mt-6">
                        <p class="text-sm text-yellow-900"><strong>Rate Limits:</strong> 60 requests/min per API key by default. 429 indicates throttling. Contact support to raise limits.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mt-6">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Webhook Verification</div>
                        <pre class="language-php"><code>$signature = request()->header('X-Signature');
$secret = env('WEBHOOK_SECRET');
$expected = hash_hmac('sha256', request()->getContent(), $secret);
abort_unless(hash_equals($expected, $signature), 401);</code></pre>
                        <p class="text-sm text-gray-600 mt-2">All webhook payloads are signed with HMAC-SHA256. Reject mismatches.</p>
                    </div>
                </section>

            </div>
        </div>
    </div>

@endsection
