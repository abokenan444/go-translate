/**
 * API Client for CulturalTranslate Platform
 * Handles all API communications with backend
 */
class APIClient {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
        // Try to get token from meta tag first (for Dashboard), then fallback to localStorage
        const metaToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
        this.token = metaToken || localStorage.getItem('auth_token');
    }

    /**
     * Set authentication token
     */
    setToken(token) {
        this.token = token;
        localStorage.setItem('auth_token', token);
    }

    /**
     * Clear authentication token
     */
    clearToken() {
        this.token = null;
        localStorage.removeItem('auth_token');
    }

    /**
     * Get headers for API requests
     */
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        // If we have a Bearer token (Sanctum), use it and skip CSRF
        if (this.token && this.token !== 'null' && this.token !== 'undefined') {
            headers['Authorization'] = `Bearer ${this.token}`;
        } else {
            // Otherwise, use CSRF token for session authentication
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            } else {
                console.warn('CSRF token not found in meta tag');
            }
        }

        return headers;
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            ...options,
            headers: {
                ...this.getHeaders(),
                ...options.headers,
            },
        };

        // Only include credentials if we're NOT using Bearer token
        // Bearer token doesn't need cookies, and including credentials may trigger CSRF check
        if (!this.token || this.token === 'null' || this.token === 'undefined') {
            config.credentials = 'include';
        }

        console.log('API Request:', {
            url,
            method: config.method || 'GET',
            headers: config.headers,
            hasCSRF: !!config.headers['X-CSRF-TOKEN']
        });

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Authentication APIs
    async register(name, email, password) {
        return this.request('/register', {
            method: 'POST',
            body: JSON.stringify({ name, email, password }),
        });
    }

    async login(email, password) {
        const response = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify({ email, password }),
        });
        
        if (response.token) {
            this.setToken(response.token);
        }
        
        return response;
    }

    async logout() {
        const response = await this.request('/logout', { method: 'POST' });
        this.clearToken();
        return response;
    }

    async getProfile() {
        return this.request('/dashboard/user');
    }

    async updateProfile(data) {
        return this.request('/profile', {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    }

    // Dashboard APIs
    async getDashboardUser() {
        return this.request('/dashboard/user');
    }

    async getDashboardStats() {
        return this.request('/dashboard/stats');
    }

    async getDashboardUsage() {
        return this.request('/dashboard/usage');
    }

    async getDashboardLanguages() {
        return this.request('/dashboard/languages');
    }

    async getDashboardHistory() {
        return this.request('/dashboard/history');
    }

    async getDashboardProjects() {
        return this.request('/dashboard/projects');
    }

    async getDashboardSubscription() {
        return this.request('/dashboard/subscription');
    }

    async getInvoices() {
        return this.request('/invoices');
    }

    async changePlan(planId) {
        return this.request('/subscription/change', {
            method: 'POST',
            body: JSON.stringify({ plan_id: planId }),
        });
    }

    // Translation APIs
    async translate(text, sourceLanguage, targetLanguage, options = {}) {
        return this.request('/dashboard/translate', {
            method: 'POST',
            body: JSON.stringify({
                text,
                source_language: sourceLanguage,
                target_language: targetLanguage,
                ...options,
            }),
        });
    }

    async batchTranslate(texts, sourceLanguage, targetLanguage, options = {}) {
        return this.request('/translate/batch', {
            method: 'POST',
            body: JSON.stringify({
                texts,
                source_language: sourceLanguage,
                target_language: targetLanguage,
                ...options,
            }),
        });
    }

    async getTranslations(page = 1, perPage = 10) {
        return this.request(`/translations?page=${page}&per_page=${perPage}`);
    }

    async getTranslation(id) {
        return this.request(`/translations/${id}`);
    }

    async deleteTranslation(id) {
        return this.request(`/translations/${id}`, { method: 'DELETE' });
    }

    // Voice Translation APIs
    async voiceTranslate(audioBlob, sourceLanguage, targetLanguage) {
        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('source_language', sourceLanguage);
        formData.append('target_language', targetLanguage);

        return fetch(`${this.baseURL}/voice/translate`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData,
        }).then(res => res.json());
    }

    async textToSpeech(text, language, voice = 'alloy') {
        return this.request('/voice/text-to-speech', {
            method: 'POST',
            body: JSON.stringify({ text, language, voice }),
        });
    }

    // Visual Translation APIs
    async translateImage(imageFile, sourceLanguage, targetLanguage) {
        const formData = new FormData();
        formData.append('image', imageFile);
        formData.append('source_language', sourceLanguage);
        formData.append('target_language', targetLanguage);

        return fetch(`${this.baseURL}/visual/image`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData,
        }).then(res => res.json());
    }

    // Subscription APIs
    async getPlans() {
        return this.request('/plans');
    }

    async subscribe(planId, paymentMethod = null) {
        const response = await this.request('/pricing/subscribe', {
            method: 'POST',
            body: JSON.stringify({ plan_id: planId, payment_method: paymentMethod }),
        });
        
        // If response contains redirect_url, redirect to Stripe Checkout
        if (response.success && response.redirect_url) {
            window.location.href = response.redirect_url;
            return response;
        }
        
        return response;
    }

    async getSubscription() {
        return this.request('/dashboard/subscription');
    }

    async getUsage() {
        return this.request('/dashboard/usage');
    }

    async cancelSubscription() {
        return this.request('/cancel', { method: 'POST' });
    }

    // Analytics APIs
    async getDashboard() {
        return this.request('/dashboard/stats');
    }

    async getInsights() {
        return this.request('/analytics/insights');
    }

    async getUsageStats() {
        return this.request('/analytics/usage');
    }

    // Collaboration APIs
    async createProject(name, description) {
        return this.request('/dashboard/projects', {
            method: 'POST',
            body: JSON.stringify({ name, description }),
        });
    }

    async getProjects() {
        return this.request('/dashboard/projects');
    }

    async getTranslations(page = 1, perPage = 10) {
        return this.request('/dashboard/history');
    }

    async inviteToProject(projectId, email, role) {
        return this.request(`/dashboard/projects/${projectId}/invite`, {
            method: 'POST',
            body: JSON.stringify({ email, role }),
        });
    }

    async deleteTranslation(id) {
        return this.request(`/translations/${id}`, { method: 'DELETE' });
    }

    async deleteProject(id) {
        return this.request(`/dashboard/projects/${id}`, { method: 'DELETE' });
    }
}

// Create global instance
window.apiClient = new APIClient();
