<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الترجمة الثقافية - CulturalTranslate</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #0D0D0D 0%, #1a1a2e 100%);
            min-height: 100vh;
        }
        
        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(108, 99, 255, 0.3);
            transform: translateY(-2px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Premium Gradient Button */
        .btn-premium {
            background: linear-gradient(135deg, #6C63FF 0%, #5A52D5 100%);
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-premium:hover {
            background: linear-gradient(135deg, #5A52D5 0%, #4840B0 100%);
            box-shadow: 0 15px 40px rgba(108, 99, 255, 0.5);
            transform: translateY(-2px);
        }
        
        .btn-premium:active {
            transform: translateY(0);
        }
        
        /* Animated Loader */
        .loader {
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid #6C63FF;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Neon Glow Effect */
        .neon-glow {
            box-shadow: 0 0 20px rgba(108, 99, 255, 0.4),
                        0 0 40px rgba(108, 99, 255, 0.2);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(108, 99, 255, 0.5);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(108, 99, 255, 0.7);
        }
        
        /* Input Focus Glow */
        .input-glow:focus {
            border-color: #6C63FF;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1),
                        0 0 20px rgba(108, 99, 255, 0.2);
        }
        
        /* Typing Animation */
        @keyframes typing {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        .typing-indicator span {
            animation: typing 1.4s infinite;
        }
        
        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        /* Success Animation */
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .success-animation {
            animation: successPulse 0.5s ease;
        }
    </style>
</head>
<body class="text-white">
    <!-- Premium Navigation -->
    <nav class="glass-card border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-8">
                    <a href="/" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                        Cultural Translate
                    </a>
                    <div class="hidden md:flex gap-6">
                        <a href="/dashboard" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            الرئيسية
                        </a>
                        <a href="/dashboard/translate" class="text-purple-400 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            الترجمة
                        </a>
                        <a href="/dashboard/history" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            السجل
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Hero Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent">
                الترجمة الثقافية بالذكاء الاصطناعي
            </h1>
            <p class="text-xl text-gray-400">ترجمة تفهم العاطفة والسياق والثقافة</p>
        </div>

        <!-- Main Translation Card -->
        <div class="glass-card rounded-2xl p-8 mb-8 neon-glow">
            <!-- Language Selection Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">اللغة المصدر</label>
                    <select id="sourceLang" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white input-glow focus:outline-none transition">
                        @foreach($languages as $language)
                            <option value="{{ $language["code"] }}" {{ $language["code"] == "en" ? "selected" : "" }} class="bg-gray-900">{{ $language["native_name"] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end justify-center">
                    <button onclick="swapLanguages()" class="p-3 bg-white/5 hover:bg-purple-600/20 border border-white/10 rounded-xl transition">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">اللغة الهدف</label>
                    <select id="targetLang" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white input-glow focus:outline-none transition">
                        @foreach($languages as $language)
                            <option value="{{ $language["code"] }}" {{ $language["code"] == "ar" ? "selected" : "" }} class="bg-gray-900">{{ $language["native_name"] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tone & Context Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">نمط الترجمة</label>
                    <select id="tone" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white input-glow focus:outline-none transition">
                        @foreach($tones as $tone)
                            <option value="{{ $tone["code"] }}" class="bg-gray-900">{{ $tone["icon"] }} {{ $tone["name"] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">السياق (اختياري)</label>
                    <input type="text" id="context" placeholder="مثال: مقال تسويقي، محتوى طبي..." class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition">
                </div>
            </div>

            <!-- Text Areas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Source Text -->
                <div class="relative">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-sm font-medium text-gray-300">النص الأصلي</label>
                        <span id="sourceCount" class="text-sm text-purple-400 font-mono">0 كلمة</span>
                    </div>
                    <textarea 
                        id="sourceText" 
                        rows="14" 
                        placeholder="أدخل النص للترجمة..."
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none resize-none transition"
                    ></textarea>
                </div>

                <!-- Translated Text -->
                <div class="relative">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-sm font-medium text-gray-300">الترجمة</label>
                        <button onclick="copyTranslation()" class="text-sm text-purple-400 hover:text-purple-300 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            نسخ
                        </button>
                    </div>
                    <div class="relative">
                        <textarea 
                            id="translatedText" 
                            rows="14" 
                            placeholder="ستظهر الترجمة هنا..."
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 resize-none transition"
                            readonly
                        ></textarea>
                        <div id="typingIndicator" class="hidden absolute bottom-4 right-4 typing-indicator flex gap-1">
                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button 
                    onclick="translateText()" 
                    id="translateBtn" 
                    class="flex-1 btn-premium text-white px-8 py-4 rounded-xl font-semibold text-lg flex items-center justify-center gap-3"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span id="btnText">ترجمة الآن</span>
                    <div id="btnLoader" class="loader hidden"></div>
                </button>
                <button 
                    onclick="clearAll()" 
                    class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-xl font-semibold transition"
                >
                    مسح
                </button>
            </div>

            <!-- Messages -->
            <div id="errorMsg" class="hidden mt-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="errorText"></span>
            </div>
            
            <div id="successMsg" class="hidden mt-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 flex items-center gap-3 success-animation">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="successText"></span>
            </div>
        </div>

        <!-- Features Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card rounded-xl p-6 text-center">
                <div class="w-14 h-14 bg-purple-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">ترجمة فورية</h3>
                <p class="text-gray-400 text-sm">احصل على ترجمات دقيقة في ثوانٍ</p>
            </div>

            <div class="glass-card rounded-xl p-6 text-center">
                <div class="w-14 h-14 bg-blue-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">سياق ثقافي</h3>
                <p class="text-gray-400 text-sm">ترجمة تحترم الثقافات المختلفة</p>
            </div>

            <div class="glass-card rounded-xl p-6 text-center">
                <div class="w-14 h-14 bg-pink-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">آمن ومحمي</h3>
                <p class="text-gray-400 text-sm">بياناتك محمية بالكامل</p>
            </div>
        </div>
    </div>

    <script>
        // Word count
        document.getElementById('sourceText').addEventListener('input', function() {
            const text = this.value.trim();
            const wordCount = text ? text.split(/\s+/).length : 0;
            document.getElementById('sourceCount').textContent = wordCount + ' كلمة';
        });

        // Swap languages
        function swapLanguages() {
            const source = document.getElementById('sourceLang');
            const target = document.getElementById('targetLang');
            const temp = source.value;
            source.value = target.value;
            target.value = temp;
        }

        // Translate text
        async function translateText() {
            const sourceText = document.getElementById('sourceText').value.trim();
            const sourceLang = document.getElementById('sourceLang').value;
            const targetLang = document.getElementById('targetLang').value;
            const context = document.getElementById('context').value.trim();
            const tone = document.getElementById('tone').value;

            if (!sourceText) {
                showError('الرجاء إدخال نص للترجمة');
                return;
            }

            if (sourceLang === targetLang) {
                showError('الرجاء اختيار لغات مختلفة');
                return;
            }

            const btn = document.getElementById('translateBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const typingIndicator = document.getElementById('typingIndicator');
            
            btn.disabled = true;
            btnText.textContent = 'جاري الترجمة...';
            btnLoader.classList.remove('hidden');
            typingIndicator.classList.remove('hidden');
            hideMessages();

            try {
                const response = await fetch('/api/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        text: sourceText,
                        source_language: sourceLang,
                        target_language: targetLang,
                        context: context,
                        tone: tone
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('translatedText').value = data.translated_text;
                    showSuccess('تمت الترجمة بنجاح! ✨');
                } else {
                    showError(data.message || 'حدث خطأ أثناء الترجمة');
                }
            } catch (error) {
                showError('حدث خطأ في الاتصال. الرجاء المحاولة مرة أخرى.');
            } finally {
                btn.disabled = false;
                btnText.textContent = 'ترجمة الآن';
                btnLoader.classList.add('hidden');
                typingIndicator.classList.add('hidden');
            }
        }

        // Copy translation
        function copyTranslation() {
            const text = document.getElementById('translatedText').value;
            if (text) {
                navigator.clipboard.writeText(text);
                showSuccess('تم نسخ الترجمة! 📋');
            }
        }

        // Clear all
        function clearAll() {
            document.getElementById('sourceText').value = '';
            document.getElementById('translatedText').value = '';
            document.getElementById('context').value = '';
            document.getElementById('sourceCount').textContent = '0 كلمة';
            hideMessages();
        }

        // Show error
        function showError(message) {
            const errorMsg = document.getElementById('errorMsg');
            const errorText = document.getElementById('errorText');
            errorText.textContent = message;
            errorMsg.classList.remove('hidden');
            setTimeout(() => errorMsg.classList.add('hidden'), 5000);
        }

        // Show success
        function showSuccess(message) {
            const successMsg = document.getElementById('successMsg');
            const successText = document.getElementById('successText');
            successText.textContent = message;
            successMsg.classList.remove('hidden');
            setTimeout(() => successMsg.classList.add('hidden'), 3000);
        }

        // Hide messages
        function hideMessages() {
            document.getElementById('errorMsg').classList.add('hidden');
            document.getElementById('successMsg').classList.add('hidden');
        }

        // Ctrl+Enter to translate
        document.getElementById('sourceText').addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                translateText();
            }
        });
    </script>
</body>
</html>
