<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('app.direction', 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locale Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Language/Locale Test Page</h1>
        
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-blue-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Current Locale</h2>
                <p class="text-2xl text-blue-600">{{ app()->getLocale() }}</p>
            </div>
            
            <div class="bg-green-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Session Locale</h2>
                <p class="text-2xl text-green-600">{{ Session::get('locale', 'Not Set') }}</p>
            </div>
            
            <div class="bg-purple-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Cookie Locale</h2>
                <p class="text-2xl text-purple-600">{{ request()->cookie('locale', 'Not Set') }}</p>
            </div>
            
            <div class="bg-orange-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Config Locale</h2>
                <p class="text-2xl text-orange-600">{{ config('app.locale') }}</p>
            </div>
            
            <div class="bg-red-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Fallback Locale</h2>
                <p class="text-2xl text-red-600">{{ config('app.fallback_locale') }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded">
                <h2 class="font-bold text-lg mb-2">Direction</h2>
                <p class="text-2xl text-gray-600">{{ config('app.direction', 'ltr') }}</p>
            </div>
        </div>
        
        <div class="bg-indigo-50 p-6 rounded mb-8">
            <h2 class="font-bold text-lg mb-4">Language Switcher</h2>
            <div class="flex flex-wrap gap-2">
                @php
                $languages = [
                    'en' => ['name' => 'English', 'flag' => '🇬🇧'],
                    'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
                    'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
                    'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
                    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
                    'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
                    'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
                    'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
                    'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
                    'ja' => ['name' => '日本語', 'flag' => '🇯🇵'],
                    'ko' => ['name' => '한국어', 'flag' => '🇰🇷'],
                    'hi' => ['name' => 'हिन्दी', 'flag' => '🇮🇳'],
                    'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷'],
                    'nl' => ['name' => 'Nederlands', 'flag' => '🇳🇱'],
                ];
                @endphp
                
                @foreach($languages as $code => $lang)
                <a href="{{ route('language.switch', $code) }}" 
                   class="px-4 py-2 rounded-lg border-2 transition {{ app()->getLocale() == $code ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400' }}">
                    {{ $lang['flag'] }} {{ $lang['name'] }} ({{ strtoupper($code) }})
                </a>
                @endforeach
            </div>
        </div>
        
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="bg-yellow-50 p-4 rounded">
            <h2 class="font-bold text-lg mb-2">All Session Data</h2>
            <pre class="text-xs overflow-auto">{{ json_encode(Session::all(), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</body>
</html>
