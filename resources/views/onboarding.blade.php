@extends('layouts.app')

@section('title', 'Welcome to Cultural Translate')

@section('content')
<div class="onboarding-container" x-data="onboarding()">
    <!-- Progress Bar -->
    <div class="onboarding-progress">
        <div class="progress-bar">
            <div class="progress-fill" :style="`width: ${progress}%`"></div>
        </div>
        <div class="progress-text">Step <span x-text="currentStep"></span> of 5</div>
    </div>

    <!-- Step 1: Welcome -->
    <div class="onboarding-step" x-show="currentStep === 1" x-transition>
        <div class="step-content">
            <h1>🌍 Welcome to Cultural Translate</h1>
            <p class="lead">AI-powered translation with cultural context and tone adaptation</p>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="icon">🎯</div>
                    <h3>Cultural Context</h3>
                    <p>Understands idioms and cultural nuances</p>
                </div>
                <div class="feature">
                    <div class="icon">🎨</div>
                    <h3>Tone Adaptation</h3>
                    <p>Formal, casual, technical, marketing</p>
                </div>
                <div class="feature">
                    <div class="icon">🚀</div>
                    <h3>14 Languages</h3>
                    <p>Arabic, Spanish, French, and more</p>
                </div>
                <div class="feature">
                    <div class="icon">⚡</div>
                    <h3>Lightning Fast</h3>
                    <p>Redis caching for instant results</p>
                </div>
            </div>

            <button @click="nextStep()" class="btn-primary">Get Started →</button>
        </div>
    </div>

    <!-- Step 2: Create Sandbox -->
    <div class="onboarding-step" x-show="currentStep === 2" x-transition>
        <div class="step-content">
            <h2>🏗️ Create Your Sandbox</h2>
            <p>Try our platform in a safe sandbox environment</p>

            <form @submit.prevent="createSandbox()">
                <div class="form-group">
                    <label>Project Name</label>
                    <input type="text" x-model="sandbox.name" placeholder="My E-commerce Store" required>
                </div>

                <div class="form-group">
                    <label>Industry</label>
                    <select x-model="sandbox.industry" required>
                        <option value="">Select Industry</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="saas">SaaS/Technology</option>
                        <option value="education">Education</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="finance">Finance</option>
                        <option value="media">Media/Publishing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Target Languages</label>
                    <div class="language-checkboxes">
                        <template x-for="lang in languages" :key="lang.code">
                            <label class="checkbox-label">
                                <input type="checkbox" :value="lang.code" x-model="sandbox.languages">
                                <span x-text="`${lang.flag} ${lang.name}`"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" @click="prevStep()" class="btn-secondary">← Back</button>
                    <button type="submit" class="btn-primary" :disabled="loading">
                        <span x-show="!loading">Create Sandbox →</span>
                        <span x-show="loading">Creating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Step 3: Sample Translation -->
    <div class="onboarding-step" x-show="currentStep === 3" x-transition>
        <div class="step-content">
            <h2>✨ Try Your First Translation</h2>
            <p>See cultural translation in action</p>

            <div class="translation-demo">
                <div class="form-group">
                    <label>Original Text</label>
                    <textarea x-model="demo.text" rows="4" placeholder="Enter text to translate..."></textarea>
                </div>

                <div class="translation-options">
                    <div class="form-group">
                        <label>Target Language</label>
                        <select x-model="demo.language">
                            <option value="ar">🇸🇦 Arabic</option>
                            <option value="es">🇪🇸 Spanish</option>
                            <option value="fr">🇫🇷 French</option>
                            <option value="de">🇩🇪 German</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tone</label>
                        <select x-model="demo.tone">
                            <option value="formal">Formal</option>
                            <option value="casual">Casual</option>
                            <option value="technical">Technical</option>
                            <option value="marketing">Marketing</option>
                        </select>
                    </div>
                </div>

                <button @click="testTranslation()" class="btn-primary" :disabled="!demo.text || loading">
                    <span x-show="!loading">Translate</span>
                    <span x-show="loading">Translating...</span>
                </button>

                <div x-show="demo.result" class="translation-result" x-transition>
                    <h4>Translation Result:</h4>
                    <div class="result-box" x-text="demo.result"></div>
                    <div class="result-meta">
                        <span>Language: <strong x-text="demo.language"></strong></span>
                        <span>Tone: <strong x-text="demo.tone"></strong></span>
                        <span x-show="demo.cached">⚡ Cached (5ms)</span>
                        <span x-show="!demo.cached">Processing Time: <strong x-text="demo.time"></strong>ms</span>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button @click="prevStep()" class="btn-secondary">← Back</button>
                <button @click="nextStep()" class="btn-primary">Continue →</button>
            </div>
        </div>
    </div>

    <!-- Step 4: Get API Key -->
    <div class="onboarding-step" x-show="currentStep === 4" x-transition>
        <div class="step-content">
            <h2>🔑 Your API Credentials</h2>
            <p>Use these credentials to integrate with your applications</p>

            <div class="credentials-box">
                <div class="credential-item">
                    <label>API URL</label>
                    <div class="credential-value">
                        <code x-text="credentials.url"></code>
                        <button @click="copy(credentials.url)" class="btn-copy">📋 Copy</button>
                    </div>
                </div>

                <div class="credential-item">
                    <label>API Key</label>
                    <div class="credential-value">
                        <code x-text="credentials.key"></code>
                        <button @click="copy(credentials.key)" class="btn-copy">📋 Copy</button>
                    </div>
                </div>

                <div class="credential-item">
                    <label>Sandbox Instance</label>
                    <div class="credential-value">
                        <code x-text="credentials.subdomain"></code>
                        <button @click="copy(credentials.subdomain)" class="btn-copy">📋 Copy</button>
                    </div>
                </div>
            </div>

            <div class="quick-start">
                <h3>Quick Start Examples</h3>
                
                <div class="code-tabs" x-data="{ tab: 'curl' }">
                    <div class="tabs">
                        <button @click="tab = 'curl'" :class="{ active: tab === 'curl' }">cURL</button>
                        <button @click="tab = 'js'" :class="{ active: tab === 'js' }">JavaScript</button>
                        <button @click="tab = 'python'" :class="{ active: tab === 'python' }">Python</button>
                        <button @click="tab = 'php'" :class="{ active: tab === 'php' }">PHP</button>
                    </div>

                    <div x-show="tab === 'curl'" class="code-block">
                        <pre><code>curl -X POST {{ credentials.url }}/translate \
  -H "Accept: application/json" \
  -H "X-API-Key: {{ credentials.key }}" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello World",
    "target_language": "ar",
    "tone": "formal"
  }'</code></pre>
                    </div>

                    <div x-show="tab === 'js'" class="code-block">
                        <pre><code>import CulturalTranslate from '@culturaltranslate/sdk';

const ct = new CulturalTranslate('{{ credentials.key }}');

const result = await ct.translate('Hello World', {
  language: 'ar',
  tone: 'formal'
});

console.log(result.translated_text);</code></pre>
                    </div>

                    <div x-show="tab === 'python'" class="code-block">
                        <pre><code>from cultural_translate import CulturalTranslate

ct = CulturalTranslate('{{ credentials.key }}')

result = ct.translate(
    'Hello World',
    language='ar',
    tone='formal'
)

print(result['translated_text'])</code></pre>
                    </div>

                    <div x-show="tab === 'php'" class="code-block">
                        <pre><code>$api = new CulturalTranslate\Client('{{ credentials.key }}');

$result = $api->translate('Hello World', [
    'language' => 'ar',
    'tone' => 'formal'
]);

echo $result['translated_text'];</code></pre>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button @click="prevStep()" class="btn-secondary">← Back</button>
                <button @click="nextStep()" class="btn-primary">Continue →</button>
            </div>
        </div>
    </div>

    <!-- Step 5: Choose Integration -->
    <div class="onboarding-step" x-show="currentStep === 5" x-transition>
        <div class="step-content">
            <h2>🔌 Choose Your Integration</h2>
            <p>How would you like to use Cultural Translate?</p>

            <div class="integration-grid">
                <a href="/api-playground" class="integration-card">
                    <div class="icon">🎮</div>
                    <h3>API Playground</h3>
                    <p>Interactive API testing interface</p>
                    <span class="badge">Recommended</span>
                </a>

                <a href="/docs/wordpress" class="integration-card">
                    <div class="icon">📦</div>
                    <h3>WordPress Plugin</h3>
                    <p>Auto-translate your WordPress content</p>
                    <span class="badge">43% Market</span>
                </a>

                <a href="/docs/shopify" class="integration-card">
                    <div class="icon">🛒</div>
                    <h3>Shopify App</h3>
                    <p>Translate your Shopify store</p>
                    <span class="badge">20% eCommerce</span>
                </a>

                <a href="/docs/cli" class="integration-card">
                    <div class="icon">⚙️</div>
                    <h3>CLI Tool</h3>
                    <p>Command-line translation tool</p>
                    <span class="badge">Developer</span>
                </a>

                <a href="/docs/sdk/javascript" class="integration-card">
                    <div class="icon">💻</div>
                    <h3>JavaScript SDK</h3>
                    <p>Browser & Node.js library</p>
                </a>

                <a href="/docs/sdk/python" class="integration-card">
                    <div class="icon">🐍</div>
                    <h3>Python SDK</h3>
                    <p>Official Python library</p>
                </a>
            </div>

            <div class="completion-message">
                <h3>🎉 You're All Set!</h3>
                <p>Your sandbox is ready. Start translating with cultural context.</p>
                <a href="/dashboard" class="btn-primary btn-large">Go to Dashboard →</a>
            </div>
        </div>
    </div>
</div>

<style>
.onboarding-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
    min-height: 100vh;
}

.onboarding-progress {
    margin-bottom: 40px;
}

.progress-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

.onboarding-step {
    animation: fadeIn 0.3s ease;
}

.step-content {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.step-content h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #111827;
}

.step-content h2 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #111827;
}

.lead {
    font-size: 18px;
    color: #6b7280;
    margin-bottom: 40px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.feature {
    text-align: center;
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.feature .icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.feature h3 {
    font-size: 18px;
    margin-bottom: 8px;
    color: #111827;
}

.feature p {
    font-size: 14px;
    color: #6b7280;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 16px;
}

.language-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    padding: 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    cursor: pointer;
}

.checkbox-label input {
    width: auto;
    margin-right: 8px;
}

.translation-demo {
    background: #f9fafb;
    padding: 24px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.translation-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.translation-result {
    margin-top: 24px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    border-left: 4px solid #10b981;
}

.result-box {
    background: #f3f4f6;
    padding: 16px;
    border-radius: 6px;
    margin: 12px 0;
    font-family: monospace;
}

.result-meta {
    display: flex;
    gap: 16px;
    font-size: 14px;
    color: #6b7280;
}

.credentials-box {
    background: #f9fafb;
    padding: 24px;
    border-radius: 8px;
    margin-bottom: 32px;
}

.credential-item {
    margin-bottom: 20px;
}

.credential-item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.credential-value {
    display: flex;
    gap: 12px;
    align-items: center;
}

.credential-value code {
    flex: 1;
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-family: monospace;
    font-size: 14px;
}

.btn-copy {
    padding: 8px 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.code-tabs .tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.code-tabs .tabs button {
    padding: 8px 16px;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 6px 6px 0 0;
    cursor: pointer;
}

.code-tabs .tabs button.active {
    background: #111827;
    color: white;
    border-color: #111827;
}

.code-block {
    background: #111827;
    color: #e5e7eb;
    padding: 20px;
    border-radius: 8px;
    overflow-x: auto;
}

.code-block pre {
    margin: 0;
}

.integration-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.integration-card {
    padding: 24px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    position: relative;
}

.integration-card:hover {
    border-color: #3b82f6;
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.integration-card .icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.integration-card h3 {
    font-size: 18px;
    margin-bottom: 8px;
}

.integration-card .badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #10b981;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.completion-message {
    text-align: center;
    padding: 40px;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border-radius: 12px;
}

.button-group {
    display: flex;
    gap: 12px;
    justify-content: space-between;
    margin-top: 32px;
}

.btn-primary, .btn-secondary {
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-large {
    padding: 16px 48px;
    font-size: 18px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function onboarding() {
    return {
        currentStep: 1,
        loading: false,
        progress: 20,
        
        sandbox: {
            name: '',
            industry: '',
            languages: []
        },
        
        demo: {
            text: 'Welcome to our platform! We help you grow your business globally.',
            language: 'ar',
            tone: 'marketing',
            result: '',
            cached: false,
            time: 0
        },
        
        credentials: {
            url: 'https://culturaltranslate.com/api/sandbox/v1',
            key: 'd232f267-0044-4bec-976b-502745745ffe',
            subdomain: 'demo-{{ Str::random(6) }}'
        },
        
        languages: [
            { code: 'ar', name: 'Arabic', flag: '🇸🇦' },
            { code: 'es', name: 'Spanish', flag: '🇪🇸' },
            { code: 'fr', name: 'French', flag: '🇫🇷' },
            { code: 'de', name: 'German', flag: '🇩🇪' },
            { code: 'it', name: 'Italian', flag: '🇮🇹' },
            { code: 'ja', name: 'Japanese', flag: '🇯🇵' }
        ],
        
        nextStep() {
            if (this.currentStep < 5) {
                this.currentStep++;
                this.progress = (this.currentStep / 5) * 100;
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.progress = (this.currentStep / 5) * 100;
            }
        },
        
        async createSandbox() {
            this.loading = true;
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            this.loading = false;
            this.nextStep();
        },
        
        async testTranslation() {
            this.loading = true;
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            this.demo.result = `[${this.demo.tone}] [${this.demo.language}] ${this.demo.text}`;
            this.demo.cached = false;
            this.demo.time = Math.floor(Math.random() * 100) + 50;
            this.loading = false;
        },
        
        copy(text) {
            navigator.clipboard.writeText(text);
            alert('Copied to clipboard!');
        }
    }
}
</script>
@endsection
