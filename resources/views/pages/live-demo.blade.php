@extends('layouts.app')

@section('title', __('Live Demo: Experience CulturalTranslate\'s Cultural AI Translation'))
@section('description', __('Experience the CulturalTranslate live demo: an interactive AI translation platform that preserves cultural context, tone, and nuance for truly accurate communication.'))

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">{{ __('Live Demo') }}</h1>
        <p class="text-xl opacity-90 max-w-3xl mx-auto">
            {{ __('Experience the CulturalTranslate live demo: an interactive AI translation platform that preserves cultural context, tone, and nuance for truly accurate communication.') }}
        </p>
        <p class="text-sm opacity-75 mt-4">{{ __('Last Updated') }}: {{ date('F j, Y') }}</p>
    </div>
</div>

<!-- Main Demo Section -->
<div class="container mx-auto px-4 py-16">
    <div class="max-w-6xl mx-auto">
        
        <!-- Demo Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Try CulturalTranslate Now - Free Demo') }}</h2>
            <p class="text-lg text-gray-600">{{ __('Experience AI-powered cultural translation in real-time. No registration required.') }}</p>
        </div>

        <!-- Language Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 items-end">
            <div>
                <label class="block font-semibold mb-2 text-gray-700">{{ __('From') }}</label>
                <select id="demoSourceLang" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-base focus:border-indigo-500 focus:outline-none">
                    <option value="en">🇬🇧 English</option>
                    <option value="ar">🇸🇦 Arabic - العربية</option>
                    <option value="es">🇪🇸 Spanish - Español</option>
                    <option value="fr">🇫🇷 French - Français</option>
                    <option value="de">🇩🇪 German - Deutsch</option>
                    <option value="it">🇮🇹 Italian - Italiano</option>
                    <option value="pt">🇵🇹 Portuguese - Português</option>
                    <option value="ru">🇷🇺 Russian - Русский</option>
                    <option value="zh">🇨🇳 Chinese - 中文</option>
                    <option value="ja">🇯🇵 Japanese - 日本語</option>
                    <option value="ko">🇰🇷 Korean - 한국어</option>
                    <option value="hi">🇮🇳 Hindi - हिन्दी</option>
                    <option value="tr">🇹🇷 Turkish - Türkçe</option>
                    <option value="nl">🇳🇱 Dutch - Nederlands</option>
                </select>
            </div>
            
            <div class="text-center">
                <button onclick="swapLanguages()" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    <svg class="w-6 h-6 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>
            </div>
            
            <div>
                <label class="block font-semibold mb-2 text-gray-700">{{ __('To') }}</label>
                <select id="demoTargetLang" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-base focus:border-indigo-500 focus:outline-none">
                    <option value="en">🇬🇧 English</option>
                    <option value="ar" selected>🇸🇦 Arabic - العربية</option>
                    <option value="es">🇪🇸 Spanish - Español</option>
                    <option value="fr">🇫🇷 French - Français</option>
                    <option value="de">🇩🇪 German - Deutsch</option>
                    <option value="it">🇮🇹 Italian - Italiano</option>
                    <option value="pt">🇵🇹 Portuguese - Português</option>
                    <option value="ru">🇷🇺 Russian - Русский</option>
                    <option value="zh">🇨🇳 Chinese - 中文</option>
                    <option value="ja">🇯🇵 Japanese - 日本語</option>
                    <option value="ko">🇰🇷 Korean - 한국어</option>
                    <option value="hi">🇮🇳 Hindi - हिन्दी</option>
                    <option value="tr">🇹🇷 Turkish - Türkçe</option>
                    <option value="nl">🇳🇱 Dutch - Nederlands</option>
                </select>
            </div>
        </div>

        <!-- Text Areas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-semibold mb-2 text-gray-700">{{ __('Your Text') }} ({{ __('max 500 characters') }})</label>
                <textarea id="demoSourceText" 
                          placeholder="{{ __('Enter text to translate...') }}" 
                          maxlength="500"
                          class="w-full h-48 px-4 py-3 border-2 border-gray-300 rounded-lg text-base resize-y focus:border-indigo-500 focus:outline-none"></textarea>
                <div class="text-right text-sm text-gray-500 mt-1">
                    <span id="charCount">0</span>/500 {{ __('characters') }}
                </div>
            </div>
            
            <div>
                <label class="block font-semibold mb-2 text-gray-700">{{ __('Translation') }}</label>
                <div id="demoTranslatedText" 
                     class="w-full h-48 px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50 overflow-y-auto text-gray-500 text-base">
                    {{ __('Translation will appear here...') }}
                </div>
                <div id="translationStats" class="text-right text-sm text-gray-500 mt-1 hidden">
                    {{ __('Quality Score') }}: <span id="qualityScore">-</span>% | {{ __('Words') }}: <span id="wordCount">-</span>
                </div>
            </div>
        </div>

        <!-- Translate Button -->
        <div class="text-center mb-8">
            <button id="translateBtn" 
                    onclick="performDemoTranslation()" 
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transition transform hover:-translate-y-1">
                <span id="translateBtnText">🚀 {{ __('Translate Now') }}</span>
                <span id="translateBtnLoader" class="hidden">⏳ {{ __('Translating...') }}</span>
            </button>
        </div>

        <!-- Messages -->
        <div id="demoMessage" class="hidden p-4 rounded-lg mb-6 text-center"></div>

        <!-- Quick Examples -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h4 class="font-semibold text-gray-900 mb-4">{{ __('Try These Examples:') }}</h4>
            <div class="flex flex-wrap gap-3">
                <button onclick="loadExample('Welcome to our platform! We are excited to help you grow your business globally.')" 
                        class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition">
                    👋 {{ __('Welcome Message') }}
                </button>
                <button onclick="loadExample('Our innovative solution helps businesses reach customers worldwide with culturally adapted content.')" 
                        class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition">
                    💼 {{ __('Marketing Copy') }}
                </button>
                <button onclick="loadExample('Thank you for your order. Your package will be delivered within 3-5 business days.')" 
                        class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition">
                    📦 {{ __('Customer Support') }}
                </button>
                <button onclick="loadExample('Join our community of innovators and transform the way you communicate with the world.')" 
                        class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition">
                    🌍 {{ __('Call to Action') }}
                </button>
            </div>
        </div>

        <!-- Features Highlight -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center p-6">
                <div class="text-5xl mb-3">🎯</div>
                <h4 class="font-semibold text-gray-900 mb-2">{{ __('Cultural Context') }}</h4>
                <p class="text-sm text-gray-600">{{ __('Preserves cultural nuances and context') }}</p>
            </div>
            <div class="text-center p-6">
                <div class="text-5xl mb-3">⚡</div>
                <h4 class="font-semibold text-gray-900 mb-2">{{ __('Real-time') }}</h4>
                <p class="text-sm text-gray-600">{{ __('Instant AI-powered translations') }}</p>
            </div>
            <div class="text-center p-6">
                <div class="text-5xl mb-3">🌍</div>
                <h4 class="font-semibold text-gray-900 mb-2">{{ __('14 Languages') }}</h4>
                <p class="text-sm text-gray-600">{{ __('Support for major world languages') }}</p>
            </div>
            <div class="text-center p-6">
                <div class="text-5xl mb-3">🔒</div>
                <h4 class="font-semibold text-gray-900 mb-2">{{ __('Secure & Private') }}</h4>
                <p class="text-sm text-gray-600">{{ __('Your data is never stored') }}</p>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 rounded-xl text-center mt-12">
            <h3 class="text-3xl font-bold mb-4">{{ __('Ready for Unlimited Translations?') }}</h3>
            <p class="text-lg opacity-90 mb-6">{{ __('Sign up now and get access to advanced features, API integration, and priority support.') }}</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition">
                {{ __('Start Free Trial') }} →
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Character counter
document.getElementById('demoSourceText').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Swap languages
function swapLanguages() {
    const source = document.getElementById('demoSourceLang');
    const target = document.getElementById('demoTargetLang');
    const temp = source.value;
    source.value = target.value;
    target.value = temp;
}

// Load example
function loadExample(text) {
    document.getElementById('demoSourceText').value = text;
    document.getElementById('charCount').textContent = text.length;
}

// Perform translation
async function performDemoTranslation() {
    const sourceText = document.getElementById('demoSourceText').value.trim();
    const sourceLang = document.getElementById('demoSourceLang').value;
    const targetLang = document.getElementById('demoTargetLang').value;
    
    if (!sourceText) {
        showMessage('{{ __("Please enter text to translate") }}', 'error');
        return;
    }
    
    if (sourceLang === targetLang) {
        showMessage('{{ __("Please select different source and target languages") }}', 'error');
        return;
    }
    
    const btn = document.getElementById('translateBtn');
    const btnText = document.getElementById('translateBtnText');
    const btnLoader = document.getElementById('translateBtnLoader');
    
    btn.disabled = true;
    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    
    try {
        const response = await fetch('/api/demo-translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                text: sourceText,
                source_language: sourceLang,
                target_language: targetLang
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('demoTranslatedText').textContent = data.translated_text;
            document.getElementById('demoTranslatedText').classList.remove('text-gray-500');
            document.getElementById('demoTranslatedText').classList.add('text-gray-900');
            
            if (data.quality_score) {
                document.getElementById('qualityScore').textContent = Math.round(data.quality_score);
            }
            if (data.word_count) {
                document.getElementById('wordCount').textContent = data.word_count;
            }
            document.getElementById('translationStats').classList.remove('hidden');
            
            showMessage('✅ {{ __("Translation completed successfully!") }}', 'success');
        } else {
            showMessage(data.error || data.message || '{{ __("Translation failed") }}', 'error');
        }
    } catch (error) {
        showMessage('{{ __("Network error. Please try again.") }}', 'error');
        console.error('Translation error:', error);
    } finally {
        btn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
    }
}

// Show message
function showMessage(text, type) {
    const msgEl = document.getElementById('demoMessage');
    msgEl.textContent = text;
    msgEl.classList.remove('hidden');
    
    if (type === 'success') {
        msgEl.className = 'p-4 rounded-lg mb-6 text-center bg-green-100 text-green-800 border-2 border-green-500';
    } else {
        msgEl.className = 'p-4 rounded-lg mb-6 text-center bg-red-100 text-red-800 border-2 border-red-500';
    }
    
    setTimeout(() => {
        msgEl.classList.add('hidden');
    }, 5000);
}

// Enter key support
document.getElementById('demoSourceText').addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        performDemoTranslation();
    }
});
</script>
@endpush
@endsection
