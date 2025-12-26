<?php 
// Ensure all required variables are available
if(!isset($languages)) {
    $languages = \App\Models\CulturalProfile::orderBy('region')->orderBy('name')->get();
}
if(!isset($industries)) {
    $industries = \App\Models\IndustryTemplate::orderBy('name')->get();
}
?>
<div class="max-w-6xl mx-auto" x-data="translateTab()">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-language mr-3 text-indigo-600"></i>
            {{ __('dashboard.translate_form.title') }}
        </h3>
        
        <!-- Language Selectors -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Source Language</label>
                <select id="translateSourceLang" x-model="sourceLanguage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="auto">Auto-detect</option>
                    @php
                        $regions = $languages->groupBy('region');
                    @endphp
                    @foreach($regions as $region => $langs)
                        <optgroup label="{{ $region }}">
                            @foreach($langs as $lang)
                                <option value="{{ $lang->locale }}">{{ $lang->name }} ({{ $lang->locale }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            
            <button @click="swapLanguages()" class="mt-8 md:mt-0 p-3 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition border border-gray-300" title="Swap languages">
                <i class="fas fa-exchange-alt"></i>
            </button>
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Language</label>
                <select id="translateTargetLang" x-model="targetLanguage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Select target language</option>
                    @foreach($regions as $region => $langs)
                        <optgroup label="{{ $region }}">
                            @foreach($langs as $lang)
                                <option value="{{ $lang->locale }}">{{ $lang->name }} ({{ $lang->locale }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Industry/Sector -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Industry/Sector (optional)</label>
            <select id="translateIndustry" x-model="industry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">Select industry (optional)</option>
                @foreach($industries as $industry)
                    <option value="{{ $industry->slug }}">{{ $industry->name }}</option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-gray-500">Select industry for specialized terminology.</p>
        </div>
        
        <!-- Optional Target Culture -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Target Culture (optional)</label>
            <input type="text" x-model="targetCulture" placeholder="e.g., ar-SA, fr-CA, en-GB" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
            <p class="mt-2 text-xs text-gray-500">Leave blank to use general target-language defaults.</p>
        </div>
        
        <!-- AI Model Selector -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">AI Model</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <button @click="aiModel = 'gpt-4'" :class="aiModel === 'gpt-4' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'" class="px-4 py-2 rounded-lg font-medium transition border">
                    GPT-4
                </button>
                <button @click="aiModel = 'gpt-3.5'" :class="aiModel === 'gpt-3.5' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'" class="px-4 py-2 rounded-lg font-medium transition border">
                    GPT-3.5
                </button>
                <button @click="aiModel = 'google'" :class="aiModel === 'google' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'" class="px-4 py-2 rounded-lg font-medium transition border">
                    Google
                </button>
                <button @click="aiModel = 'deepl'" :class="aiModel === 'deepl' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'" class="px-4 py-2 rounded-lg font-medium transition border">
                    DeepL
                </button>
            </div>
        </div>
        
        <!-- Options -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg grid grid-cols-1 md:grid-cols-4 gap-4">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="culturalAdaptation" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Cultural Adaptation</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="preserveBrandVoice" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Preserve Brand Voice</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="formalTone" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Formal Tone</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="extractOnly" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Extract text only (no translation)</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer md:col-span-3">
                <input type="checkbox" x-model="smartCorrect" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Smart Correction (fix spelling/grammar in source before translation)</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="applyGlossary" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Apply glossary terms</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" x-model="asyncPdf" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Async batch for large PDFs</span>
            </label>
        </div>
        
        <!-- Source Text -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Text to Translate</label>
            <textarea x-model="sourceText" @input="updateCharCount()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" rows="8" placeholder="{{ __('dashboard.translate_form.placeholder') }}"></textarea>
            <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                <div>
                    <span x-text="charCount"></span> / 10,000 characters
                </div>
                <div class="flex space-x-2">
                    <button @click="startVoiceInput()" class="px-3 py-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-microphone mr-1"></i> Voice
                    </button>
                    <label class="px-3 py-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition cursor-pointer">
                        <i class="fas fa-image mr-1"></i> Image
                        <input type="file" @change="handleImageUpload($event)" accept="image/*" class="hidden">
                    </label>
                    <label class="px-3 py-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition cursor-pointer">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                        <input type="file" @change="handlePdfUpload($event)" accept="application/pdf" class="hidden">
                    </label>
                    <button @click="clearText()" :disabled="!sourceText" :class="!sourceText ? 'opacity-50 cursor-not-allowed' : ''" class="px-3 py-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-times mr-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Translate Button -->
        <button @click="translate()" :disabled="translating || !sourceText" :class="translating || !sourceText ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg'" class="w-full gradient-bg text-white px-6 py-4 rounded-lg font-semibold transition mb-6">
            <span x-show="!translating">
                <i class="fas fa-magic mr-2"></i>{{ __('dashboard.translate_form.translate_button') }}
            </span>
            <span x-show="translating" class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin mr-2"></i> Translating...
            </span>
        </button>
        
        <!-- Async PDF Progress -->
        <div x-show="translating && asyncPdf" x-cloak class="mb-4 text-sm text-gray-600">
            <template x-if="pdfProgress && pdfProgress.total">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <span>PDF progress: </span>
                            <span x-text="`Chunk ${pdfProgress.current}/${pdfProgress.total}`"></span>
                            <span class="ml-2" x-text="`(${pdfProgress.chars} chars)`"></span>
                        </div>
                        <div class="text-xs text-gray-500" x-text="`${Math.round((pdfProgress.current / pdfProgress.total) * 100)}%`"></div>
                    </div>
                    <div class="w-full bg-gray-200 rounded h-2 overflow-hidden">
                        <div class="h-2 bg-indigo-500 transition-all" :style="`width: ${Math.max(0, Math.min(100, Math.round((pdfProgress.current / pdfProgress.total) * 100)))}%`"></div>
                    </div>
                </div>
            </template>
            <template x-if="pdfProgress && !pdfProgress.total">
                <div>Preparing PDF translation…</div>
            </template>
        </div>
        
        <!-- Translation Output -->
        <div x-show="translatedText" x-cloak class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>{{ __('dashboard.translate_form.translation_label') }}
                </span>
                <div class="flex space-x-2">
                    <button @click="copyTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-white rounded transition border border-gray-300">
                        <i class="fas fa-copy mr-1"></i> {{ __('dashboard.translate_form.copy') }}
                    </button>
                    <button @click="listenTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-white rounded transition border border-gray-300">
                        <i class="fas fa-volume-up mr-1"></i> {{ __('dashboard.translate_form.listen') }}
                    </button>
                    <button @click="downloadTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-white rounded transition border border-gray-300">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                </div>
            </div>
            <div class="text-gray-900 whitespace-pre-wrap bg-white p-4 rounded border border-gray-200" x-text="translatedText"></div>
            
            <!-- Quality Score -->
            <div x-show="qualityScore" class="mt-4 pt-4 border-t border-gray-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Quality Score</span>
                    <span class="text-sm font-bold" :class="qualityScore >= 90 ? 'text-green-600' : qualityScore >= 70 ? 'text-yellow-600' : 'text-red-600'" x-text="qualityScore + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all" :class="qualityScore >= 90 ? 'bg-green-500' : qualityScore >= 70 ? 'bg-yellow-500' : 'bg-red-500'" :style="`width: ${qualityScore}%`"></div>
                </div>
            </div>
        </div>
        
        <div x-show="!translatedText && !translating" x-cloak class="bg-gray-50 rounded-lg p-8 border border-dashed border-gray-300 text-center">
            <div class="text-gray-500">
                <i class="fas fa-file-alt text-3xl mb-3 opacity-30"></i>
                <p class="text-lg">{{ __('dashboard.translate_form.result_placeholder') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
function translateTab() {
    return {
        sourceLanguage: 'auto',
        targetLanguage: 'ar',
            targetCulture: '',
        aiModel: 'gpt-4',
        sourceText: '',
        translatedText: '',
        charCount: 0,
        translating: false,
        culturalAdaptation: true,
        preserveBrandVoice: false,
        formalTone: false,
            smartCorrect: false,
            extractOnly: false,
            applyGlossary: true,
            asyncPdf: false,
            pdfProgress: null,
            asyncJobId: '',
        qualityScore: null,
        
        getDefaultCulture(lang) {
            const defaultCultures = {
                'ar': 'ar-SA',
                'en': 'en-US',
                'es': 'es-ES',
                'fr': 'fr-FR',
                'de': 'de-DE',
                'it': 'it-IT',
                'pt': 'pt-BR',
                'ru': 'ru-RU',
                'zh': 'zh-CN',
                'ja': 'ja-JP',
                'ko': 'ko-KR',
                'hi': 'hi-IN',
                'tr': 'tr-TR',
                'nl': 'nl-NL'
            };
            return defaultCultures[lang] || null;
        },
        
        updateCharCount() {
            this.charCount = this.sourceText.length;
        },
        
        swapLanguages() {
            const prevSource = this.sourceLanguage;
            const prevTarget = this.targetLanguage;
            // Always swap texts
            [this.sourceText, this.translatedText] = [this.translatedText, this.sourceText];
            // Handle auto-detect gracefully
            if (prevSource === 'auto') {
                this.sourceLanguage = prevTarget;
                // Pick a sensible default for target when previous source was auto
                this.targetLanguage = (prevSource !== 'auto' && prevSource) ? prevSource : 'en';
            } else {
                [this.sourceLanguage, this.targetLanguage] = [prevTarget, prevSource];
            }
            this.updateCharCount();
        },
        
        async translate() {
            if (!this.sourceText) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'warning', message: 'Please enter text to translate' }
                });
                window.dispatchEvent(event);
                return;
            }
            
            this.translating = true;
            this.translatedText = '';
            this.qualityScore = null;
            
            try {
                const response = await fetch('/api/dashboard/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        text: this.sourceText,
                        source_language: this.sourceLanguage,
                        target_language: this.targetLanguage,
                        target_culture: this.targetCulture || this.getDefaultCulture(this.targetLanguage),
                        tone: 'professional',
                        ai_model: this.aiModel,
                        smart_correct: this.smartCorrect,
                        apply_glossary: this.applyGlossary,
                        cultural_adaptation: this.culturalAdaptation,
                        preserve_brand_voice: this.preserveBrandVoice,
                        formal_tone: this.formalTone
                    })
                });

                const contentType = response.headers.get('content-type') || '';
                let data;
                if (contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    const raw = await response.text();
                    throw new Error('HTML response received instead of JSON. First bytes: ' + raw.slice(0,120));
                }

                if (!response.ok) {
                    throw new Error(data.error || data.message || 'Translation failed');
                }

                this.translatedText = data.translated_text || data.translation || '';
                if (data.corrected_text) {
                    this.sourceText = data.corrected_text;
                    this.updateCharCount();
                }
                this.qualityScore = data.quality_score || 95;
                
                if (!this.translatedText) {
                    throw new Error('No translation received');
                }
                
                // Dispatch success toast
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'success', message: 'Translation completed successfully!' }
                });
                window.dispatchEvent(event);
            } catch (error) {
                console.error('Translation error:', error);
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Translation failed: ' + error.message }
                });
                window.dispatchEvent(event);
            } finally {
                this.translating = false;
            }
        },
        
        copyTranslation() {
            navigator.clipboard.writeText(this.translatedText).then(() => {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'success', message: 'Copied to clipboard!' }
                });
                window.dispatchEvent(event);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        },
        
        async listenTranslation() {
            try {
                const response = await fetch('/api/dashboard/text-to-speech', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        text: this.translatedText,
                        language: this.targetLanguage
                    })
                });
                const data = await response.json();
                if (data.audio_url) {
                    const audio = new Audio(data.audio_url);
                    audio.play();
                }
            } catch (error) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Failed to play audio' }
                });
                window.dispatchEvent(event);
            }
        },
        
        downloadTranslation() {
            const blob = new Blob([this.translatedText], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'translation.txt';
            a.click();
            URL.revokeObjectURL(url);
            const event = new CustomEvent('show-toast', {
                detail: { type: 'success', message: 'Translation downloaded!' }
            });
            window.dispatchEvent(event);
        },
        
        clearText() {
            this.sourceText = '';
            this.translatedText = '';
            this.charCount = 0;
            this.qualityScore = null;
        },
        
        async startVoiceInput() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Voice input not supported in your browser' }
                });
                window.dispatchEvent(event);
                return;
            }

            try {
                if (!this.isRecording) {
                    // Start recording
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.audioChunks = [];

                    this.mediaRecorder.ondataavailable = (event) => {
                        this.audioChunks.push(event.data);
                    };

                    this.mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        await this.processVoiceTranslation(audioBlob);
                        stream.getTracks().forEach(track => track.stop());
                    };

                    this.mediaRecorder.start();
                    this.isRecording = true;
                    
                    const event = new CustomEvent('show-toast', {
                        detail: { type: 'info', message: 'Recording... Click again to stop' }
                    });
                    window.dispatchEvent(event);
                } else {
                    // Stop recording
                    this.mediaRecorder.stop();
                    this.isRecording = false;
                }
            } catch (error) {
                console.error('Voice input error:', error);
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Microphone access denied' }
                });
                window.dispatchEvent(event);
            }
        },

        async processVoiceTranslation(audioBlob) {
            try {
                this.translating = true;
                const formData = new FormData();
                formData.append('audio', audioBlob, 'recording.webm');
                formData.append('source_lang', this.sourceLanguage);
                formData.append('target_lang', this.targetLanguage);
                formData.append('return_audio', '1');

                const response = await fetch('/api/subscription/voice/translate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.data) {
                    this.sourceText = data.data.transcribed_text || '';
                    this.translatedText = data.data.translated_text || '';
                    this.updateCharCount();

                    // Play output audio if available
                    if (data.data.audio_url) {
                        const audio = new Audio(data.data.audio_url);
                        audio.play();
                    }

                    const event = new CustomEvent('show-toast', {
                        detail: { 
                            type: 'success', 
                            message: `Voice translated! Cost: $${data.usage.cost}` 
                        }
                    });
                    window.dispatchEvent(event);
                } else {
                    throw new Error(data.message || 'Voice translation failed');
                }
            } catch (error) {
                console.error('Voice translation error:', error);
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: error.message || 'Voice translation failed' }
                });
                window.dispatchEvent(event);
            } finally {
                this.translating = false;
            }
        },
        
        async handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Please upload a valid image file (JPG, PNG, WEBP)' }
                });
                window.dispatchEvent(event);
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Image size must be less than 5MB' }
                });
                window.dispatchEvent(event);
                return;
            }
            
            try {
                this.translating = true;
                const formData = new FormData();
                formData.append('image', file);
                formData.append('target_language', this.targetLanguage);
                formData.append('source_language', this.sourceLanguage || 'auto');
                if (this.targetCulture) formData.append('target_culture', this.targetCulture);
                if (this.extractOnly) formData.append('extract_only', '1');
                if (this.applyGlossary === false) formData.append('apply_glossary', '0');
                
                const response = await fetch('/api/mte/image', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success && data.data) {
                    if (data.data.extracted_text) this.sourceText = data.data.extracted_text;
                    if (data.data.translated_text) this.translatedText = data.data.translated_text;
                    if (data.data.quality_score) this.qualityScore = data.data.quality_score;
                    
                    this.updateCharCount();
                    
                    const toastEvent = new CustomEvent('show-toast', {
                        detail: { type: 'success', message: 'Image translated successfully!' }
                    });
                    window.dispatchEvent(toastEvent);
                } else {
                    throw new Error(data.error || 'Image translation failed');
                }
            } catch (error) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Failed to translate image' }
                });
                window.dispatchEvent(event);
            } finally {
                this.translating = false;
            }
        },

        async handlePdfUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            if (file.type !== 'application/pdf') {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Please upload a valid PDF file' }
                });
                window.dispatchEvent(event);
                return;
            }

            // Validate file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'PDF size must be less than 10MB' }
                });
                window.dispatchEvent(event);
                return;
            }

            try {
                this.translating = true;
                const formData = new FormData();
                formData.append('pdf', file);
                formData.append('target_language', this.targetLanguage);
                formData.append('source_language', this.sourceLanguage || 'auto');
                
                // Auto-detect culture code if not specified
                const defaultCultures = {
                    'ar': 'ar-SA',
                    'en': 'en-US',
                    'es': 'es-ES',
                    'fr': 'fr-FR',
                    'de': 'de-DE',
                    'it': 'it-IT',
                    'pt': 'pt-BR',
                    'ru': 'ru-RU',
                    'zh': 'zh-CN',
                    'ja': 'ja-JP',
                    'ko': 'ko-KR',
                    'hi': 'hi-IN',
                    'tr': 'tr-TR',
                    'nl': 'nl-NL'
                };
                const culture = this.targetCulture || defaultCultures[this.targetLanguage] || null;
                if (culture) formData.append('target_culture', culture);
                if (this.extractOnly) formData.append('extract_only', '1');
                if (this.applyGlossary === false) formData.append('apply_glossary', '0');
                
                if (this.asyncPdf) {
                    const resp = await fetch('/api/mte/pdf/async', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    if (!resp.ok) {
                        throw new Error(`Server error: ${resp.status}`);
                    }

                    const d = await resp.json();
                    if (!d.success) throw new Error(d.error || 'Failed to start async PDF translation');
                    const jobId = d.job_id;
                    this.asyncJobId = jobId;
                    this.pdfProgress = { current: 0, total: null, chars: 0 };
                    const tick = async () => {
                        const s = await fetch(`/api/mte/pdf/status/${jobId}`, { headers: { 'Accept': 'application/json' } });
                        const sj = await s.json();
                        if (!s.ok || !sj.success) throw new Error(sj.error || 'Status check failed');
                        const state = sj.data || {};
                        if (state.progress) {
                            const pr = state.progress;
                            this.pdfProgress = {
                                current: pr.current || 0,
                                total: pr.total || null,
                                chars: pr.translated_chars_total || 0,
                            };
                        }
                        if (state.status === 'completed') {
                            const res = state.result || {};
                            if (res.extracted_text) this.sourceText = res.extracted_text;
                            if (res.translated_text) this.translatedText = res.translated_text;
                            if (res.extracted_text_length) {
                                let notice = `Extracted ${res.extracted_text_length} chars.`;
                                if (typeof res.chunks !== 'undefined') {
                                    const ch = res.chunks;
                                    const tot = res.total_translated_chars || 0;
                                    notice += ` Translated in ${ch} chunk(s), ~${tot} chars.`;
                                }
                                const event = new CustomEvent('show-toast', { detail: { type: 'info', message: notice } });
                                window.dispatchEvent(event);
                            }
                            const doneEvent = new CustomEvent('show-toast', { detail: { type: 'success', message: 'PDF translation completed!' } });
                            window.dispatchEvent(doneEvent);
                            this.updateCharCount();
                            this.translating = false;
                            return;
                        } else if (state.status === 'failed') {
                            const errEvent = new CustomEvent('show-toast', { detail: { type: 'error', message: state.error || 'PDF translation failed' } });
                            window.dispatchEvent(errEvent);
                            this.translating = false;
                            return;
                        }
                        setTimeout(tick, 2000);
                    };
                    setTimeout(tick, 1500);
                } else {
                    const response = await fetch('/api/mte/pdf', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    const contentType = response.headers.get('content-type') || '';
                    const data = contentType.includes('application/json') ? await response.json() : { success: false, error: 'Non-JSON response' };
                    if (data.data) {
                        if (data.data.extracted_text) {
                            this.sourceText = data.data.extracted_text;
                        }
                        if (data.data.extracted_text_length) {
                            let notice = `Extracted ${data.data.extracted_text_length} chars.`;
                            if (typeof data.data.truncated_to !== 'undefined') {
                                notice += ` Translated first ${data.data.truncated_to}.`;
                            } else if (typeof data.data.chunks !== 'undefined') {
                                const ch = data.data.chunks;
                                const tot = data.data.total_translated_chars || 0;
                                notice += ` Translated in ${ch} chunk(s), ~${tot} chars.`;
                            }
                            const event = new CustomEvent('show-toast', { detail: { type: 'info', message: notice } });
                            window.dispatchEvent(event);
                        }
                        if (data.data.translated_text) this.translatedText = data.data.translated_text;
                        if (data.data.quality_score) this.qualityScore = data.data.quality_score;
                    }
                    this.updateCharCount();
                    const successEvent = new CustomEvent('show-toast', {
                        detail: { type: data.success ? 'success' : 'error', message: data.success ? 'PDF translated successfully!' : (data.error || 'PDF translation failed') }
                    });
                    window.dispatchEvent(successEvent);
                }
            } catch (error) {
                const event = new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Failed to translate PDF' }
                });
                window.dispatchEvent(event);
            } finally {
                this.translating = false;
            }
        }
    }
}

// Languages and industries are loaded server-side
</script>

