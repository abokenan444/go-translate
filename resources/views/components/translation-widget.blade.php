<!-- Translation Widget for Homepage -->
<div class="translation-widget" x-data="translationWidget()">
    <div class="widget-header">
        <h3>Try Translation</h3>
        <div class="usage-indicator" x-show="!isAuthenticated">
            <span x-text="usageText"></span>
        </div>
    </div>

    <div class="translation-form">
        <!-- Source -->
        <div class="input-section">
            <select x-model="sourceLang" class="lang-select">
                <option value="auto">Auto-detect</option>
                <option value="en">English</option>
                <option value="ar">Arabic</option>
                <option value="fr">French</option>
                <option value="de">German</option>
                <option value="es">Spanish</option>
                <option value="zh">Chinese</option>
                <option value="ja">Japanese</option>
                <option value="ko">Korean</option>
                <option value="pt">Portuguese</option>
                <option value="ru">Russian</option>
            </select>
            <textarea 
                x-model="sourceText" 
                placeholder="Enter text to translate..."
                rows="4"
                maxlength="5000"
            ></textarea>
        </div>

        <!-- Target -->
        <div class="input-section">
            <select x-model="targetLang" class="lang-select">
                <option value="ar">Arabic</option>
                <option value="en">English</option>
                <option value="fr">French</option>
                <option value="de">German</option>
                <option value="es">Spanish</option>
                <option value="zh">Chinese</option>
                <option value="ja">Japanese</option>
                <option value="ko">Korean</option>
                <option value="pt">Portuguese</option>
                <option value="ru">Russian</option>
            </select>
            <textarea 
                x-model="translatedText" 
                placeholder="Translation will appear here..."
                rows="4"
                readonly
            ></textarea>
        </div>
    </div>

    <!-- Actions -->
    <div class="widget-actions">
        <button 
            @click="translate()" 
            :disabled="loading || !sourceText"
            class="btn-translate"
        >
            <span x-show="!loading">Translate</span>
            <span x-show="loading">Translating...</span>
        </button>
        
        <div class="info-badges">
            <span x-show="fromCache" class="badge badge-success">⚡ Cached</span>
            <span x-show="responseTime" x-text="responseTime + 'ms'" class="badge badge-info"></span>
        </div>
    </div>

    <!-- Limit Warning -->
    <div x-show="showLimitWarning" class="alert alert-warning">
        <p x-text="limitMessage"></p>
        <a :href="registerUrl" class="btn-register">Sign Up Free</a>
    </div>

    <!-- Error -->
    <div x-show="error" class="alert alert-danger" x-text="error"></div>
</div>

<script>
function translationWidget() {
    return {
        sourceLang: 'auto',
        targetLang: 'ar',
        sourceText: '',
        translatedText: '',
        loading: false,
        error: null,
        fromCache: false,
        responseTime: null,
        isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
        used: 0,
        remaining: 2,
        limit: 2,
        showLimitWarning: false,
        limitMessage: '',
        registerUrl: '{{ route("register") }}',

        init() {
            this.checkUsage();
            this.generateFingerprint();
        },

        get usageText() {
            if (this.isAuthenticated) return '∞ Unlimited';
            return `${this.remaining}/${this.limit} free translations`;
        },

        async generateFingerprint() {
            // Simple browser fingerprint
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.fillText('fingerprint', 2, 2);
            const fingerprint = canvas.toDataURL().slice(-50);
            this.fingerprint = btoa(fingerprint).slice(0, 32);
        },

        async checkUsage() {
            try {
                const response = await fetch('/api/public/translation/usage', {
                    headers: {
                        'X-Fingerprint': this.fingerprint
                    }
                });
                const result = await response.json();
                if (result.success && result.data) {
                    this.isAuthenticated = result.data.is_authenticated || false;
                    this.used = result.data.used || 0;
                    this.remaining = result.data.remaining || 0;
                    this.limit = result.data.limit || 2;
                }
            } catch (e) {
                console.error('Usage check failed:', e);
            }
        },

        async translate() {
            if (!this.sourceText.trim()) return;

            this.loading = true;
            this.error = null;
            this.fromCache = false;
            this.responseTime = null;
            this.showLimitWarning = false;

            try {
                const response = await fetch('/api/public/translation/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Fingerprint': this.fingerprint
                    },
                    body: JSON.stringify({
                        source_text: this.sourceText,
                        source_lang: this.sourceLang,
                        target_lang: this.targetLang,
                        content_type: 'general'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.translatedText = result.data.translated_text;
                    this.fromCache = result.data.from_cache;
                    this.responseTime = Math.round(result.data.response_time_ms);
                    
                    if (result.usage) {
                        this.used = result.usage.used;
                        this.remaining = result.usage.remaining;
                    }
                } else {
                    if (result.requires_registration) {
                        this.showLimitWarning = true;
                        this.limitMessage = result.message;
                    } else {
                        this.error = result.message;
                    }
                }
            } catch (e) {
                this.error = 'Connection failed';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>
.translation-widget {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto;
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.widget-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.usage-indicator {
    font-size: 14px;
    color: #666;
    background: #f0f0f0;
    padding: 6px 12px;
    border-radius: 6px;
}

.translation-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.input-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.lang-select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

textarea {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    resize: vertical;
    font-family: inherit;
}

.widget-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.btn-translate {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 12px 32px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-translate:hover:not(:disabled) {
    background: #45a049;
}

.btn-translate:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.info-badges {
    display: flex;
    gap: 8px;
}

.badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-info {
    background: #d1ecf1;
    color: #0c5460;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-top: 16px;
}

.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.btn-register {
    display: inline-block;
    margin-top: 8px;
    padding: 8px 20px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}

.btn-register:hover {
    background: #0056b3;
}

@media (max-width: 768px) {
    .translation-form {
        grid-template-columns: 1fr;
    }
}
</style>
