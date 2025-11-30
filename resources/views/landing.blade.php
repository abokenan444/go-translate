<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.site_title') }}</title>
    <meta name="description" content="{{ __('messages.site_description') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.hero_title') }}</h1>
                <p class="text-xl mb-8">
                    {{ __('messages.hero_subtitle') }}
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('trial') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        {{ __('messages.hero_cta_start') }}
                    </a>
                    <a href="#features" class="border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                        {{ __('messages.hero_cta_discover') }}
                    </a>
                </div>
                <div class="mt-6 flex gap-6 justify-center text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ __('messages.hero_no_credit_card') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ __('messages.hero_free_trial') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalUsers }}</div>
                    <div class="text-gray-600">{{ __('messages.stats_registered_users') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalSubscriptions }}</div>
                    <div class="text-gray-600">{{ __('messages.stats_active_subscriptions') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalPages }}</div>
                    <div class="text-gray-600">{{ __('messages.stats_published_pages') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalCompanies }}</div>
                    <div class="text-gray-600">{{ __('messages.stats_active_companies') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Live Translation Demo -->
    <section id="demo" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold mb-4">{{ __('messages.demo_title') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('messages.demo_subtitle') }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <!-- Language Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.demo_from') }}</label>
                            <select id="demoSourceLang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="en">{{ __('messages.lang_english') }}</option>
                                <option value="ar">{{ __('messages.lang_arabic') }}</option>
                                <option value="es">{{ __('messages.lang_spanish') }}</option>
                                <option value="fr">{{ __('messages.lang_french') }}</option>
                                <option value="de">{{ __('messages.lang_german') }}</option>
                            </select>
                        </div>

                        <div class="flex items-end justify-center">
                            <button onclick="swapDemoLanguages()" class="px-4 py-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.demo_to') }}</label>
                            <select id="demoTargetLang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="en">{{ __('messages.lang_english') }}</option>
                                <option value="ar" selected>{{ __('messages.lang_arabic') }}</option>
                                <option value="es">{{ __('messages.lang_spanish') }}</option>
                                <option value="fr">{{ __('messages.lang_french') }}</option>
                                <option value="de">{{ __('messages.lang_german') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Text Areas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.demo_source_text') }}</label>
                            <textarea id="demoSourceText" rows="8" placeholder="{{ __('messages.demo_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.demo_translation') }}</label>
                            <div id="demoTranslatedText" class="w-full h-full min-h-[200px] px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    {{ __('messages.demo_result_placeholder') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex flex-col items-center gap-4">
                        <button onclick="translateDemo()" id="demoTranslateBtn" class="w-full md:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-12 py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition flex items-center justify-center gap-3 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span id="demoBtnText">{{ __('messages.demo_translate_btn') }}</span>
                            <div id="demoBtnLoader" class="hidden">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                            </div>
                        </button>

                        <p class="text-sm text-gray-500">
                            {{ __('messages.demo_free_trial_note') }}
                        </p>

                        <div id="demoError" class="hidden w-full p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-center"></div>
                    </div>

                    <!-- Example Texts -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">{{ __('messages.demo_try_examples') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="loadExample('Welcome to our platform! We are excited to help you grow your business globally.')" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm hover:bg-indigo-100 transition">
                                {{ __('messages.demo_example_welcome') }}
                            </button>
                            <button onclick="loadExample('Our innovative solution helps businesses reach customers worldwide with culturally adapted content.')" class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm hover:bg-purple-100 transition">
                                {{ __('messages.demo_example_marketing') }}
                            </button>
                            <button onclick="loadExample('Thank you for your order. Your package will be delivered within 3-5 business days.')" class="px-4 py-2 bg-pink-50 text-pink-700 rounded-lg text-sm hover:bg-pink-100 transition">
                                {{ __('messages.demo_example_support') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Features Highlight -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">{{ __('messages.demo_feature_instant') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('messages.demo_feature_instant_desc') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">{{ __('messages.demo_feature_cultural') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('messages.demo_feature_cultural_desc') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">{{ __('messages.demo_feature_secure') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('messages.demo_feature_secure_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">{{ __('messages.features_title') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('messages.features_subtitle') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_cultural_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_cultural_desc') }}</p>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_fast_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_fast_desc') }}</p>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_security_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_security_desc') }}</p>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_memory_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_memory_desc') }}</p>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_glossary_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_glossary_desc') }}</p>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.feature_api_title') }}</h3>
                        <p class="text-gray-600">{{ __('messages.feature_api_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-4">{{ __('messages.cta_title') }}</h2>
                <p class="text-xl mb-8">{{ __('messages.cta_subtitle') }}</p>
                <a href="{{ route('trial') }}" class="inline-block bg-white text-indigo-600 px-12 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition">
                    {{ __('messages.cta_button') }}
                </a>
            </div>
        </div>
    </section>

    @include('components.footer')

    <script>
        function swapDemoLanguages() {
            const source = document.getElementById('demoSourceLang');
            const target = document.getElementById('demoTargetLang');
            const temp = source.value;
            source.value = target.value;
            target.value = temp;
        }

        function loadExample(text) {
            document.getElementById('demoSourceText').value = text;
            document.getElementById('demoSourceLang').value = 'en';
            document.getElementById('demoTargetLang').value = 'ar';
        }

        async function translateDemo() {
            const sourceText = document.getElementById('demoSourceText').value.trim();
            const sourceLang = document.getElementById('demoSourceLang').value;
            const targetLang = document.getElementById('demoTargetLang').value;

            if (!sourceText) {
                showDemoError("{{ __('messages.demo_error_empty') }}");
                return;
            }

            if (sourceLang === targetLang) {
                showDemoError("{{ __('messages.demo_error_same_lang') }}");
                return;
            }

            const btn = document.getElementById('demoTranslateBtn');
            const btnText = document.getElementById('demoBtnText');
            const btnLoader = document.getElementById('demoBtnLoader');
            const translatedDiv = document.getElementById('demoTranslatedText');
            
            btn.disabled = true;
            btnText.textContent = "{{ __('messages.demo_translating') }}";
            btnLoader.classList.remove('hidden');
            btnText.classList.add('hidden');
            document.getElementById('demoError').classList.add('hidden');

            try {
                const response = await fetch('/api/translate/demo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        text: sourceText,
                        source_language: sourceLang,
                        target_language: targetLang
                    })
                });

                const data = await response.json();

                if (data.success) {
                    translatedDiv.innerHTML = `<div class="text-gray-800">${data.translation}</div>`;
                } else {
                    showDemoError(data.message || 'Translation failed');
                }
            } catch (error) {
                showDemoError('An error occurred. Please try again.');
            } finally {
                btn.disabled = false;
                btnText.textContent = "{{ __('messages.demo_translate_btn') }}";
                btnLoader.classList.add('hidden');
                btnText.classList.remove('hidden');
            }
        }

        function showDemoError(message) {
            const errorDiv = document.getElementById('demoError');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }
    </script>
</body>
</html>
