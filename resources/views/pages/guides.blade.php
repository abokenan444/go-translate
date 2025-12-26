@extends('layouts.app')

@section('title', 'Guides - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.guides') }}</h1>
                <p class="text-xl opacity-90">Learn how to use CulturalTranslate effectively</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <!-- Getting Started -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8">Getting Started</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Create an Account</h3>
                    <p class="text-gray-600 mb-4">Sign up for a free account to get started. No credit card required for the trial period.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Visit the <a href="/register" class="text-blue-600 hover:underline">registration page</a></li>
                        <li>• Fill in your details</li>
                        <li>• Verify your email</li>
                        <li>• Start translating!</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-indigo-600">2</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Choose Your Plan</h3>
                    <p class="text-gray-600 mb-4">Select a plan that fits your needs. Upgrade or downgrade anytime.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• <strong>Free:</strong> 10 translations/day</li>
                        <li>• <strong>Basic:</strong> 100k chars/month</li>
                        <li>• <strong>Professional:</strong> 500k chars/month</li>
                        <li>• <strong>Enterprise:</strong> Unlimited</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-purple-600">3</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Start Translating</h3>
                    <p class="text-gray-600 mb-4">Use our intuitive interface to translate text, documents, or integrate via API.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Paste or type your text</li>
                        <li>• Select source and target languages</li>
                        <li>• Choose AI model (GPT-4, GPT-3.5, etc.)</li>
                        <li>• Click "Translate Now"</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-green-600">4</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Advanced Features</h3>
                    <p class="text-gray-600 mb-4">Explore advanced options for better translations.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Cultural Adaptation</li>
                        <li>• Brand Voice Preservation</li>
                        <li>• Formal/Casual Tone</li>
                        <li>• Glossary Terms</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature Guides -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8">Feature Guides</h2>
            
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">📝 Text Translation</h3>
                    <p class="text-gray-600 mb-4">Translate text up to 10,000 characters at once with our advanced AI models.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Steps:</p>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Go to Dashboard → Translate</li>
                            <li>Enter your text in the source box</li>
                            <li>Select source and target languages</li>
                            <li>Choose your preferred AI model</li>
                            <li>Enable advanced options if needed</li>
                            <li>Click "Translate Now"</li>
                        </ol>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">🎤 Voice Translation</h3>
                    <p class="text-gray-600 mb-4">Upload audio files or record directly to translate speech to text.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Supported formats:</p>
                        <p class="text-gray-600">MP3, WAV, M4A, OGG (max 25MB)</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">🖼️ Image Translation</h3>
                    <p class="text-gray-600 mb-4">Extract and translate text from images using OCR technology.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Supported formats:</p>
                        <p class="text-gray-600">JPG, PNG, WEBP (max 10MB)</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">📄 PDF Translation</h3>
                    <p class="text-gray-600 mb-4">Translate entire PDF documents while preserving formatting.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Features:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <li>Preserves original formatting</li>
                            <li>Handles multi-page documents</li>
                            <li>Async processing for large files</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">🔐 Certified Translation</h3>
                    <p class="text-gray-600 mb-4">Get officially certified translations for legal documents.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Process:</p>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Go to Certified Translation page</li>
                            <li>Select document type (Passport, Birth Certificate, etc.)</li>
                            <li>Upload your document</li>
                            <li>Choose source and target languages</li>
                            <li>Submit and wait for processing</li>
                            <li>Download certified translation with official stamp</li>
                        </ol>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold mb-3">🔌 API Integration</h3>
                    <p class="text-gray-600 mb-4">Integrate CulturalTranslate into your applications.</p>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="font-semibold mb-2">Quick Start:</p>
                        <pre class="bg-gray-900 text-green-400 p-4 rounded mt-2 overflow-x-auto"><code>curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, world!",
    "source_language": "en",
    "target_language": "ar"
  }'</code></pre>
                        <p class="text-gray-600 mt-2">See full <a href="/api-docs" class="text-blue-600 hover:underline">API documentation</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Practices -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8">Best Practices</h2>
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-4">💡 Tips for Better Translations</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Use clear, simple language in your source text</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Enable "Cultural Adaptation" for marketing content</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Provide context for ambiguous terms</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Use GPT-4 or GPT-4-turbo for highest quality</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Review and edit translations for critical content</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">✓</span>
                        <span>Create glossaries for consistent terminology</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Need Help? -->
        <div class="max-w-5xl mx-auto text-center">
            <div class="bg-white rounded-xl shadow-lg p-12">
                <h2 class="text-3xl font-bold mb-4">Need More Help?</h2>
                <p class="text-xl text-gray-600 mb-8">Our support team is here to assist you</p>
                <div class="flex justify-center gap-4">
                    <a href="/help" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Visit Help Center
                    </a>
                    <a href="/support" class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
