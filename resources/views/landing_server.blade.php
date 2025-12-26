<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
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
<option value="af">Afrikaans</option>
<option value="sq">Albanian</option>
<option value="am">Amharic</option>
<option value="ar">Arabic</option>
<option value="hy">Armenian</option>
<option value="az">Azerbaijani</option>
<option value="eu">Basque</option>
<option value="be">Belarusian</option>
<option value="bn">Bengali</option>
<option value="bs">Bosnian</option>
<option value="bg">Bulgarian</option>
<option value="ca">Catalan</option>
<option value="ceb">Cebuano</option>
<option value="zh-CN">Chinese (Simplified)</option>
<option value="zh-TW">Chinese (Traditional)</option>
<option value="co">Corsican</option>
<option value="hr">Croatian</option>
<option value="cs">Czech</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="eo">Esperanto</option>
<option value="et">Estonian</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="fy">Frisian</option>
<option value="gl">Galician</option>
<option value="ka">Georgian</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="gu">Gujarati</option>
<option value="ht">Haitian Creole</option>
<option value="ha">Hausa</option>
<option value="haw">Hawaiian</option>
<option value="he">Hebrew</option>
<option value="hi">Hindi</option>
<option value="hmn">Hmong</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="ig">Igbo</option>
<option value="id">Indonesian</option>
<option value="ga">Irish</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="jv">Javanese</option>
<option value="kn">Kannada</option>
<option value="kk">Kazakh</option>
<option value="km">Khmer</option>
<option value="rw">Kinyarwanda</option>
<option value="ko">Korean</option>
<option value="ku">Kurdish</option>
<option value="ky">Kyrgyz</option>
<option value="lo">Lao</option>
<option value="la">Latin</option>
<option value="lv">Latvian</option>
<option value="lt">Lithuanian</option>
<option value="lb">Luxembourgish</option>
<option value="mk">Macedonian</option>
<option value="mg">Malagasy</option>
<option value="ms">Malay</option>
<option value="ml">Malayalam</option>
<option value="mt">Maltese</option>
<option value="mi">Maori</option>
<option value="mr">Marathi</option>
<option value="mn">Mongolian</option>
<option value="my">Myanmar (Burmese)</option>
<option value="ne">Nepali</option>
<option value="no">Norwegian</option>
<option value="ny">Nyanja (Chichewa)</option>
<option value="or">Odia (Oriya)</option>
<option value="ps">Pashto</option>
<option value="fa">Persian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="pa">Punjabi</option>
<option value="ro">Romanian</option>
<option value="ru">Russian</option>
<option value="sm">Samoan</option>
<option value="gd">Scots Gaelic</option>
<option value="sr">Serbian</option>
<option value="st">Sesotho</option>
<option value="sn">Shona</option>
<option value="sd">Sindhi</option>
<option value="si">Sinhala (Sinhalese)</option>
<option value="sk">Slovak</option>
<option value="sl">Slovenian</option>
<option value="so">Somali</option>
<option value="es">Spanish</option>
<option value="su">Sundanese</option>
<option value="sw">Swahili</option>
<option value="sv">Swedish</option>
<option value="tl">Tagalog (Filipino)</option>
<option value="tg">Tajik</option>
<option value="ta">Tamil</option>
<option value="tt">Tatar</option>
<option value="te">Telugu</option>
<option value="th">Thai</option>
<option value="tr">Turkish</option>
<option value="tk">Turkmen</option>
<option value="uk">Ukrainian</option>
<option value="ur">Urdu</option>
<option value="ug">Uyghur</option>
<option value="uz">Uzbek</option>
<option value="vi">Vietnamese</option>
<option value="cy">Welsh</option>
<option value="xh">Xhosa</option>
<option value="yi">Yiddish</option>
<option value="yo">Yoruba</option>
<option value="zu">Zulu</option>
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
<option value="af">Afrikaans</option>
<option value="sq">Albanian</option>
<option value="am">Amharic</option>
<option value="ar">Arabic</option>
<option value="hy">Armenian</option>
<option value="az">Azerbaijani</option>
<option value="eu">Basque</option>
<option value="be">Belarusian</option>
<option value="bn">Bengali</option>
<option value="bs">Bosnian</option>
<option value="bg">Bulgarian</option>
<option value="ca">Catalan</option>
<option value="ceb">Cebuano</option>
<option value="zh-CN">Chinese (Simplified)</option>
<option value="zh-TW">Chinese (Traditional)</option>
<option value="co">Corsican</option>
<option value="hr">Croatian</option>
<option value="cs">Czech</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="eo">Esperanto</option>
<option value="et">Estonian</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="fy">Frisian</option>
<option value="gl">Galician</option>
<option value="ka">Georgian</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="gu">Gujarati</option>
<option value="ht">Haitian Creole</option>
<option value="ha">Hausa</option>
<option value="haw">Hawaiian</option>
<option value="he">Hebrew</option>
<option value="hi">Hindi</option>
<option value="hmn">Hmong</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="ig">Igbo</option>
<option value="id">Indonesian</option>
<option value="ga">Irish</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="jv">Javanese</option>
<option value="kn">Kannada</option>
<option value="kk">Kazakh</option>
<option value="km">Khmer</option>
<option value="rw">Kinyarwanda</option>
<option value="ko">Korean</option>
<option value="ku">Kurdish</option>
<option value="ky">Kyrgyz</option>
<option value="lo">Lao</option>
<option value="la">Latin</option>
<option value="lv">Latvian</option>
<option value="lt">Lithuanian</option>
<option value="lb">Luxembourgish</option>
<option value="mk">Macedonian</option>
<option value="mg">Malagasy</option>
<option value="ms">Malay</option>
<option value="ml">Malayalam</option>
<option value="mt">Maltese</option>
<option value="mi">Maori</option>
<option value="mr">Marathi</option>
<option value="mn">Mongolian</option>
<option value="my">Myanmar (Burmese)</option>
<option value="ne">Nepali</option>
<option value="no">Norwegian</option>
<option value="ny">Nyanja (Chichewa)</option>
<option value="or">Odia (Oriya)</option>
<option value="ps">Pashto</option>
<option value="fa">Persian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="pa">Punjabi</option>
<option value="ro">Romanian</option>
<option value="ru">Russian</option>
<option value="sm">Samoan</option>
<option value="gd">Scots Gaelic</option>
<option value="sr">Serbian</option>
<option value="st">Sesotho</option>
<option value="sn">Shona</option>
<option value="sd">Sindhi</option>
<option value="si">Sinhala (Sinhalese)</option>
<option value="sk">Slovak</option>
<option value="sl">Slovenian</option>
<option value="so">Somali</option>
<option value="es">Spanish</option>
<option value="su">Sundanese</option>
<option value="sw">Swahili</option>
<option value="sv">Swedish</option>
<option value="tl">Tagalog (Filipino)</option>
<option value="tg">Tajik</option>
<option value="ta">Tamil</option>
<option value="tt">Tatar</option>
<option value="te">Telugu</option>
<option value="th">Thai</option>
<option value="tr">Turkish</option>
<option value="tk">Turkmen</option>
<option value="uk">Ukrainian</option>
<option value="ur">Urdu</option>
<option value="ug">Uyghur</option>
<option value="uz">Uzbek</option>
<option value="vi">Vietnamese</option>
<option value="cy">Welsh</option>
<option value="xh">Xhosa</option>
<option value="yi">Yiddish</option>
<option value="yo">Yoruba</option>
<option value="zu">Zulu</option>
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

    <!-- Official Documents Section -->
    @include('components.official-documents-section')

    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Professional Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive translation solutions with enterprise-grade security and certified professional services
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Certified Translation</h3>
                    <p class="text-gray-600 mb-6">
                        Official certified translations with dual stamps from Cultural Translate and certified partners. Accepted by embassies, universities, and government institutions worldwide.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Dual stamp certification
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            QR code verification
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Globally recognized
                        </li>
                    </ul>
                    <a href="{{ route('services.certified-translation') }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Physical Copy Delivery</h3>
                    <p class="text-gray-600 mb-6">
                        Professional printing and worldwide shipping of your certified documents. Track your shipment in real-time from printing to delivery.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Professional printing
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Worldwide shipping
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Real-time tracking
                        </li>
                    </ul>
                    <a href="{{ route('services.physical-copy') }}" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Certified Partners</h3>
                    <p class="text-gray-600 mb-6">
                        Join our network of certified translation partners. Access exclusive tools, manage documents, and grow your translation business.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Partner dashboard
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Document management
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Earnings tracking
                        </li>
                    </ul>
                    <a href="{{ route('services.partners') }}" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-yellow-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Affiliate Program</h3>
                    <p class="text-gray-600 mb-6">
                        Earn commissions by referring clients to our platform. Get access to marketing materials, tracking tools, and competitive payouts.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Competitive commissions
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Marketing materials
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Real-time analytics
                        </li>
                    </ul>
                    <a href="{{ route('services.affiliate') }}" class="inline-flex items-center text-yellow-600 font-semibold hover:text-yellow-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise Solutions</h3>
                    <p class="text-gray-600 mb-6">
                        Tailored translation solutions for large organizations. Custom integrations, dedicated support, and volume discounts.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Custom integrations
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Dedicated support
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Volume discounts
                        </li>
                    </ul>
                    <a href="{{ route('services.enterprise') }}" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-indigo-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Document Translation</h3>
                    <p class="text-gray-600 mb-6">
                        Professional translation for 73+ document types across 100+ languages. Fast turnaround with quality assurance.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            100+ languages
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            73+ document types
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Quality assured
                        </li>
                    </ul>
                    <a href="{{ route('services.document-translation') }}" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-700">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('services.all') }}" class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors text-lg">
                    View All Services
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
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
            const resultDiv = document.getElementById('demoResult');
            const btnText = document.getElementById('demoBtnText');
            const btnLoader = document.getElementById('demoBtnLoader');
            const errorDiv = document.getElementById('demoError');
            const btn = document.getElementById('demoTranslateBtn');

            // Validation
            if (!sourceText) {
                errorDiv.textContent = 'Please enter text to translate';
                errorDiv.classList.remove('hidden');
                return;
            }

            if (sourceLang === targetLang) {
                errorDiv.textContent = 'Please select different languages';
                errorDiv.classList.remove('hidden');
                return;
            }

            // Hide error, show loading
            errorDiv.classList.add('hidden');
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            btn.disabled = true;
            resultDiv.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div><span class="ml-3 text-gray-500">Translating...</span></div>';

            // Create abort controller with 30 second timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000);

            try {
                console.log('Starting translation request...');
                const response = await fetch('/api/v1/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        text: sourceText,
                        source: sourceLang,
                        target: targetLang,
                        tone: 'formal',
                        cultural_adaptation: true
                    }),
                    signal: controller.signal
                });

                clearTimeout(timeoutId);
                console.log('Response received:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response error:', errorText);
                    throw new Error(`Server error: ${response.status}`);
                }

                const data = await response.json();
                console.log('Translation data:', data);

                if (data.success && data.translation) {
                    // Success - show translation with details
                    const ctsScore = data.cts_score ? (parseFloat(String(data.cts_score).replace(',', '.')) * 100).toFixed(0) : '95';
                    const isRtl = ['ar', 'he', 'fa', 'ur', 'ar-sa', 'ar-eg', 'ar-ae'].includes(targetLang);
                    resultDiv.innerHTML = `
                        <div class="space-y-4">
                            <p class="text-lg leading-relaxed ${isRtl ? 'text-right' : ''}" dir="${isRtl ? 'rtl' : 'ltr'}">${data.translation}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        CTS Score: ${ctsScore}%
                                    </span>
                                    <span>${data.source_language_name || sourceLang} → ${data.target_language_name || targetLang}</span>
                                </div>
                                <button onclick="copyToClipboard(this, '${data.translation.replace(/'/g, "&#39;").replace(/"/g, "&quot;")}')" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    throw new Error(data.message || data.error || 'Translation failed');
                }
            } catch (error) {
                clearTimeout(timeoutId);
                console.error('Translation error:', error);
                
                let errorMessage = 'Translation failed. Please try again.';
                if (error.name === 'AbortError') {
                    errorMessage = 'Request timed out. Please try again.';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
                resultDiv.innerHTML = '<p class="text-gray-400 italic">Your translation will appear here...</p>';
            } finally {
                // Reset button
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
                btn.disabled = false;
            }
        }

        function copyToClipboard(btn, text) {
            // Decode HTML entities
            const textarea = document.createElement('textarea');
            textarea.innerHTML = text;
            const decodedText = textarea.value;
            
            navigator.clipboard.writeText(decodedText).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Copied!';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                }, 2000);
            }).catch(err => {
                console.error('Copy failed:', err);
            });
        }

        function copyTranslation(text) {
            copyToClipboard(event.target.closest('button'), text);
        }
    </script>
<script src="{{ asset('js/translation-limit.js') }}"></script>
</body>
</html>
