@extends('layouts.app')

@section('title', 'Live Demo - Try Cultural Translation | ديمو مباشر - جرب الترجمة الثقافية')
@section('meta_description', 'Experience cultural translation in action. Test our API with real translation | جرب الترجمة الثقافية بشكل مباشر')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    
    <!-- Hero Section -->
    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 rounded-full text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Free Trial Account - No Credit Card Required
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                <span class="block">تجربة الترجمة الثقافية</span>
                <span class="block text-3xl md:text-4xl mt-2">Experience Cultural Translation</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mt-3 text-4xl">في الوقت الفعلي | In Real-Time</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-12">
                اختبر API المتقدم لدينا مع الترجمة المباشرة والتكامل الثقافي<br>
                Test our advanced API with live translation and cultural integration
            </p>
        </div>
    </section>

    <!-- Live Translation Demo -->
    <section class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span>ساحة الترجمة المباشرة | Live Translation Playground</span>
                    </h2>
                </div>

                <div class="p-8" x-data="translationDemo()">
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Input -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                النص المصدر | Source Text
                            </label>
                            <textarea 
                                x-model="sourceText"
                                class="w-full h-48 px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"
                                placeholder="أدخل النص للترجمة... | Enter text to translate..."
                            ></textarea>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">اللغة المصدر | Source Language</label>
                                    <select x-model="sourceLang" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option value="en">English | إنجليزي</option>
                                        <option value="ar">Arabic | عربي</option>
                                        <option value="es">Spanish | إسباني</option>
                                        <option value="fr">French | فرنسي</option>
                                        <option value="de">German | ألماني</option>
                                        <option value="ja">Japanese | ياباني</option>
                                        <option value="zh">Chinese | صيني</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">اللغة الهدف | Target Language</label>
                                    <select x-model="targetLang" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option value="ar">Arabic | عربي</option>
                                        <option value="en">English | إنجليزي</option>
                                        <option value="es">Spanish | إسباني</option>
                                        <option value="fr">French | فرنسي</option>
                                        <option value="de">German | ألماني</option>
                                        <option value="ja">Japanese | ياباني</option>
                                        <option value="zh">Chinese | صيني</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">الثقافة المستهدفة | Target Culture</label>
                                <select x-model="targetCulture" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="saudi_arabia">السعودية (رسمي) | Saudi Arabia (Formal)</option>
                                    <option value="egypt">مصر (محادثة) | Egypt (Conversational)</option>
                                    <option value="morocco">المغرب (ودي) | Morocco (Friendly)</option>
                                    <option value="usa">أمريكا | USA (American English)</option>
                                    <option value="uk">بريطانيا | UK (British English)</option>
                                    <option value="spain">إسبانيا | Spain (Castilian)</option>
                                    <option value="mexico">المكسيك | Mexico (Latin American)</option>
                                </select>
                            </div>

                            <button 
                                @click="translate()"
                                :disabled="loading"
                                class="mt-6 w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg disabled:opacity-50"
                            >
                                <span x-show="!loading">✨ ترجمة مع التكيف الثقافي | Translate with Cultural Adaptation</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    جاري الترجمة... | Translating...
                                </span>
                            </button>
                        </div>

                        <!-- Output -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                النص المترجم | Translated Text
                            </label>
                            <div class="w-full h-48 px-4 py-3 border-2 border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20 rounded-lg dark:text-white overflow-y-auto">
                                <span x-show="!translatedText" class="text-gray-500 dark:text-gray-400">الترجمة ستظهر هنا... | Translation will appear here...</span>
                                <span x-text="translatedText" class="whitespace-pre-wrap"></span>
                            </div>

                            <!-- Cultural Insights -->
                            <div x-show="culturalInsights.length > 0" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-300 dark:border-blue-700 rounded-lg">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">🎯 التكيفات الثقافية المطبقة | Cultural Adaptations Applied:</h4>
                                <ul class="space-y-1 text-sm text-blue-800 dark:text-blue-200">
                                    <template x-for="insight in culturalInsights" :key="insight">
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span x-text="insight"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <!-- API Response -->
                            <div x-show="apiResponse" class="mt-4">
                                <details class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                    <summary class="cursor-pointer font-semibold text-gray-700 dark:text-gray-300">📡 استجابة API | API Response</summary>
                                    <pre class="mt-2 text-xs text-gray-600 dark:text-gray-400 overflow-x-auto" x-text="apiResponse"></pre>
                                </details>
                            </div>
                            
                            <!-- Error Message -->
                            <div x-show="errorMessage" class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-700 rounded-lg">
                                <h4 class="font-semibold text-red-900 dark:text-red-300 mb-2">❌ خطأ | Error:</h4>
                                <p class="text-sm text-red-800 dark:text-red-200" x-text="errorMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- API Testing Interface -->
    <section class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    API Testing Console
                </h2>

                <div class="space-y-6" x-data="apiTester()">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">HTTP Method</label>
                            <select x-model="method" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="POST">POST</option>
                                <option value="GET">GET</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Endpoint</label>
                            <input x-model="endpoint" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white font-mono text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Request Headers</label>
                        <textarea x-model="headers" class="w-full h-24 px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white font-mono text-xs" placeholder='{"Authorization": "Bearer YOUR_API_KEY", "Content-Type": "application/json"}'></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Request Body (JSON)</label>
                        <textarea x-model="requestBody" class="w-full h-32 px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white font-mono text-xs" placeholder='{"text": "Hello world", "source_language": "en", "target_language": "ar"}'></textarea>
                    </div>

                    <button @click="sendRequest()" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-all">
                        Send Request →
                    </button>

                    <div x-show="response" class="mt-6 bg-gray-900 rounded-lg p-6 overflow-x-auto">
                        <h4 class="text-green-400 font-semibold mb-2">Response:</h4>
                        <pre class="text-green-300 text-xs" x-text="response"></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 px-4">
        <div class="max-w-4xl mx-auto bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-12 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-indigo-100 mb-8">Create your free account and get full API access in minutes</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all shadow-lg">
                    Start Free Trial →
                </a>
                <a href="{{ route('api-docs') }}" class="bg-indigo-700 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-indigo-800 transition-all border-2 border-white">
                    View Documentation
                </a>
            </div>
        </div>
    </section>

</div>

<script>
function translationDemo() {
    return {
        sourceText: '',
        translatedText: '',
        sourceLang: 'en',
        targetLang: 'ar',
        targetCulture: 'saudi_arabia',
        loading: false,
        culturalInsights: [],
        apiResponse: '',
        errorMessage: '',
        
        async translate() {
            if (!this.sourceText.trim()) {
                this.errorMessage = 'الرجاء إدخال نص للترجمة | Please enter text to translate';
                return;
            }
            
            this.loading = true;
            this.translatedText = '';
            this.culturalInsights = [];
            this.apiResponse = '';
            this.errorMessage = '';
            
            try {
                const response = await fetch('/api/demo-translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        text: this.sourceText,
                        source_language: this.sourceLang,
                        target_language: this.targetLang,
                        target_culture: this.targetCulture,
                        apply_cultural_adaptation: true
                    })
                });
                
                const data = await response.json();
                this.apiResponse = JSON.stringify(data, null, 2);
                
                if (response.ok && data.success) {
                    this.translatedText = data.data.translated_text;
                    this.culturalInsights = data.data.cultural_insights || [];
                    this.errorMessage = '';
                } else {
                    this.errorMessage = data.message || data.error || 'حدث خطأ في الترجمة | Translation failed';
                }
            } catch (error) {
                this.errorMessage = 'خطأ في الاتصال | Connection error: ' + error.message;
                this.apiResponse = JSON.stringify({ error: error.message }, null, 2);
            } finally {
                this.loading = false;
            }
        }
    }
}

function apiTester() {
    return {
        method: 'POST',
        endpoint: '/api/demo-translate',
        headers: '{\n  "Content-Type": "application/json"\n}',
        requestBody: '{\n  "text": "Hello world",\n  "source_language": "en",\n  "target_language": "ar",\n  "target_culture": "saudi_arabia"\n}',
        response: '',
        
        async sendRequest() {
            try {
                const headers = JSON.parse(this.headers);
                headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
                
                const options = {
                    method: this.method,
                    headers: headers
                };
                
                if (this.method === 'POST' && this.requestBody.trim()) {
                    options.body = this.requestBody;
                }
                
                const response = await fetch(this.endpoint, options);
                const data = await response.json();
                
                this.response = JSON.stringify(data, null, 2);
            } catch (error) {
                this.response = JSON.stringify({ error: error.message }, null, 2);
            }
        }
    }
}
</script>
@endsection
