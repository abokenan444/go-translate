/**
 * Cultural Translate Gaming SDK
 * Version: 1.0.0
 * 
 * Real-time translation SDK for game developers
 * Supports voice and text translation with cultural adaptation
 */

class CulturalTranslateSDK {
    constructor(config) {
        this.apiKey = config.apiKey;
        this.gameId = config.gameId;
        this.baseUrl = config.baseUrl || 'https://culturaltranslate.com/api';
        this.wsUrl = config.wsUrl || 'wss://culturaltranslate.com:8080';
        
        this.session = null;
        this.ws = null;
        this.audioContext = null;
        this.mediaRecorder = null;
        this.isRecording = false;
        
        this.callbacks = {
            onTranslation: null,
            onError: null,
            onConnect: null,
            onDisconnect: null,
        };
        
        this.init();
    }
    
    /**
     * Initialize SDK
     */
    init() {
        console.log('[CulturalTranslate SDK] Initializing...');
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }
    
    /**
     * Create a new translation session
     */
    async createSession(options = {}) {
        try {
            const response = await fetch(`${this.baseUrl}/gaming/sessions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.apiKey}`,
                    'X-Game-ID': this.gameId,
                },
                body: JSON.stringify({
                    source_language: options.sourceLanguage || 'en',
                    target_language: options.targetLanguage || 'ar',
                    source_culture: options.sourceCulture || 'en-US',
                    target_culture: options.targetCulture || 'ar-SA',
                    type: 'gaming',
                    cultural_adaptation_level: options.culturalLevel || 'standard',
                }),
            });
            
            if (!response.ok) {
                throw new Error(`Failed to create session: ${response.statusText}`);
            }
            
            this.session = await response.json();
            console.log('[CulturalTranslate SDK] Session created:', this.session.public_id);
            
            // Connect to WebSocket
            await this.connectWebSocket();
            
            return this.session;
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Error creating session:', error);
            this.triggerCallback('onError', error);
            throw error;
        }
    }
    
    /**
     * Connect to WebSocket for real-time communication
     */
    async connectWebSocket() {
        return new Promise((resolve, reject) => {
            this.ws = new WebSocket(`${this.wsUrl}/app/${this.session.public_id}`);
            
            this.ws.onopen = () => {
                console.log('[CulturalTranslate SDK] WebSocket connected');
                this.triggerCallback('onConnect');
                resolve();
            };
            
            this.ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.handleWebSocketMessage(data);
            };
            
            this.ws.onerror = (error) => {
                console.error('[CulturalTranslate SDK] WebSocket error:', error);
                this.triggerCallback('onError', error);
                reject(error);
            };
            
            this.ws.onclose = () => {
                console.log('[CulturalTranslate SDK] WebSocket disconnected');
                this.triggerCallback('onDisconnect');
            };
        });
    }
    
    /**
     * Handle WebSocket messages
     */
    handleWebSocketMessage(data) {
        switch (data.event) {
            case 'translation':
                this.triggerCallback('onTranslation', data.payload);
                break;
            case 'error':
                this.triggerCallback('onError', data.payload);
                break;
            default:
                console.log('[CulturalTranslate SDK] Unknown event:', data.event);
        }
    }
    
    /**
     * Translate text
     */
    async translateText(text, options = {}) {
        try {
            const response = await fetch(`${this.baseUrl}/gaming/translate/text`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.apiKey}`,
                    'X-Game-ID': this.gameId,
                },
                body: JSON.stringify({
                    session_id: this.session?.public_id,
                    text: text,
                    source_language: options.sourceLanguage || 'en',
                    target_language: options.targetLanguage || 'ar',
                }),
            });
            
            if (!response.ok) {
                throw new Error(`Translation failed: ${response.statusText}`);
            }
            
            const result = await response.json();
            return result.translated_text;
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Translation error:', error);
            this.triggerCallback('onError', error);
            throw error;
        }
    }
    
    /**
     * Start voice recording
     */
    async startVoiceRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            
            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];
            
            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };
            
            this.mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                await this.translateVoice(audioBlob);
                this.audioChunks = [];
            };
            
            this.mediaRecorder.start();
            this.isRecording = true;
            
            console.log('[CulturalTranslate SDK] Voice recording started');
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Error starting recording:', error);
            this.triggerCallback('onError', error);
            throw error;
        }
    }
    
    /**
     * Stop voice recording
     */
    stopVoiceRecording() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.isRecording = false;
            console.log('[CulturalTranslate SDK] Voice recording stopped');
        }
    }
    
    /**
     * Translate voice audio
     */
    async translateVoice(audioBlob) {
        try {
            const formData = new FormData();
            formData.append('audio', audioBlob, 'voice.webm');
            formData.append('session_id', this.session?.public_id);
            
            const response = await fetch(`${this.baseUrl}/gaming/translate/voice`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'X-Game-ID': this.gameId,
                },
                body: formData,
            });
            
            if (!response.ok) {
                throw new Error(`Voice translation failed: ${response.statusText}`);
            }
            
            const result = await response.json();
            this.triggerCallback('onTranslation', result);
            
            return result;
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Voice translation error:', error);
            this.triggerCallback('onError', error);
            throw error;
        }
    }
    
    /**
     * Play translated audio
     */
    async playTranslatedAudio(audioUrl) {
        try {
            const audio = new Audio(audioUrl);
            await audio.play();
        } catch (error) {
            console.error('[CulturalTranslate SDK] Error playing audio:', error);
            this.triggerCallback('onError', error);
        }
    }
    
    /**
     * Set callback functions
     */
    on(event, callback) {
        if (this.callbacks.hasOwnProperty(`on${event.charAt(0).toUpperCase() + event.slice(1)}`)) {
            this.callbacks[`on${event.charAt(0).toUpperCase() + event.slice(1)}`] = callback;
        }
    }
    
    /**
     * Trigger callback
     */
    triggerCallback(callbackName, data) {
        if (this.callbacks[callbackName] && typeof this.callbacks[callbackName] === 'function') {
            this.callbacks[callbackName](data);
        }
    }
    
    /**
     * End session
     */
    async endSession() {
        try {
            if (this.ws) {
                this.ws.close();
            }
            
            if (this.session) {
                await fetch(`${this.baseUrl}/gaming/sessions/${this.session.public_id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${this.apiKey}`,
                        'X-Game-ID': this.gameId,
                    },
                });
            }
            
            this.session = null;
            console.log('[CulturalTranslate SDK] Session ended');
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Error ending session:', error);
            this.triggerCallback('onError', error);
        }
    }
    
    /**
     * Get session statistics
     */
    async getStats() {
        try {
            const response = await fetch(`${this.baseUrl}/gaming/sessions/${this.session.public_id}/stats`, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'X-Game-ID': this.gameId,
                },
            });
            
            if (!response.ok) {
                throw new Error(`Failed to get stats: ${response.statusText}`);
            }
            
            return await response.json();
            
        } catch (error) {
            console.error('[CulturalTranslate SDK] Error getting stats:', error);
            this.triggerCallback('onError', error);
            throw error;
        }
    }
}

// Export for different module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CulturalTranslateSDK;
} else if (typeof define === 'function' && define.amd) {
    define([], function() {
        return CulturalTranslateSDK;
    });
} else {
    window.CulturalTranslateSDK = CulturalTranslateSDK;
}
