/**
 * إصلاح نظام الترجمة Frontend
 * 
 * المشكلة: نظام الترجمة الحالي لا يرسل الطلبات بشكل صحيح
 * الحل: إعادة كتابة JavaScript بالكامل مع معالجة الأخطاء
 */

// ========================================
// 1. تهيئة المتغيرات
// ========================================

const translationForm = {
    sourceText: null,
    targetText: null,
    sourceLang: null,
    targetLang: null,
    translateBtn: null,
    loadingIndicator: null,
    errorMessage: null,
    
    init() {
        // الحصول على العناصر من DOM
        this.sourceText = document.getElementById('sourceText');
        this.targetText = document.getElementById('targetText');
        this.sourceLang = document.getElementById('sourceLang');
        this.targetLang = document.getElementById('targetLang');
        this.translateBtn = document.getElementById('translateBtn');
        this.loadingIndicator = document.getElementById('loadingIndicator');
        this.errorMessage = document.getElementById('errorMessage');
        
        // ربط الأحداث
        this.bindEvents();
        
        console.log('Translation system initialized ✅');
    },
    
    bindEvents() {
        // عند الضغط على زر الترجمة
        if (this.translateBtn) {
            this.translateBtn.addEventListener('click', () => this.handleTranslate());
        }
        
        // عند تغيير اللغة المصدر
        if (this.sourceLang) {
            this.sourceLang.addEventListener('change', () => this.validateLanguages());
        }
        
        // عند تغيير اللغة الهدف
        if (this.targetLang) {
            this.targetLang.addEventListener('change', () => this.validateLanguages());
        }
        
        // عند الكتابة في مربع النص
        if (this.sourceText) {
            this.sourceText.addEventListener('input', () => this.updateCharCount());
        }
    },
    
    // ========================================
    // 2. التحقق من صحة البيانات
    // ========================================
    
    validateLanguages() {
        const source = this.sourceLang?.value;
        const target = this.targetLang?.value;
        
        // التحقق من اختيار اللغات
        if (!source || !target) {
            this.showError('يرجى اختيار اللغة المصدر واللغة الهدف');
            return false;
        }
        
        // التحقق من عدم تطابق اللغات
        if (source === target) {
            this.showError('اللغة المصدر واللغة الهدف يجب أن تكونا مختلفتين');
            return false;
        }
        
        this.hideError();
        return true;
    },
    
    validateText() {
        const text = this.sourceText?.value?.trim();
        
        if (!text) {
            this.showError('يرجى إدخال النص المراد ترجمته');
            return false;
        }
        
        if (text.length < 2) {
            this.showError('النص قصير جداً. يرجى إدخال نص أطول');
            return false;
        }
        
        if (text.length > 5000) {
            this.showError('النص طويل جداً. الحد الأقصى 5000 حرف');
            return false;
        }
        
        return true;
    },
    
    // ========================================
    // 3. معالجة الترجمة
    // ========================================
    
    async handleTranslate() {
        try {
            // التحقق من صحة البيانات
            if (!this.validateLanguages() || !this.validateText()) {
                return;
            }
            
            // عرض مؤشر التحميل
            this.showLoading();
            
            // جمع البيانات
            const data = {
                text: this.sourceText.value.trim(),
                source_lang: this.sourceLang.value,
                target_lang: this.targetLang.value,
                _token: this.getCsrfToken()
            };
            
            console.log('Sending translation request:', data);
            
            // إرسال الطلب
            const response = await fetch('/api/translate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(data)
            });
            
            // معالجة الاستجابة
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            console.log('Translation response:', result);
            
            // عرض النتيجة
            if (result.success && result.translation) {
                this.displayTranslation(result.translation);
                this.saveToHistory(data, result.translation);
            } else {
                throw new Error(result.message || 'فشلت عملية الترجمة');
            }
            
        } catch (error) {
            console.error('Translation error:', error);
            this.showError('حدث خطأ أثناء الترجمة: ' + error.message);
        } finally {
            this.hideLoading();
        }
    },
    
    // ========================================
    // 4. عرض النتائج
    // ========================================
    
    displayTranslation(translation) {
        if (this.targetText) {
            this.targetText.value = translation;
            
            // تأثير بصري
            this.targetText.style.backgroundColor = '#e8f5e9';
            setTimeout(() => {
                this.targetText.style.backgroundColor = '';
            }, 1000);
        }
        
        this.showSuccess('تمت الترجمة بنجاح! ✅');
    },
    
    // ========================================
    // 5. إدارة الحالة (UI State)
    // ========================================
    
    showLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'block';
        }
        
        if (this.translateBtn) {
            this.translateBtn.disabled = true;
            this.translateBtn.textContent = 'جاري الترجمة...';
        }
        
        this.hideError();
    },
    
    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'none';
        }
        
        if (this.translateBtn) {
            this.translateBtn.disabled = false;
            this.translateBtn.textContent = 'ترجمة الآن';
        }
    },
    
    showError(message) {
        if (this.errorMessage) {
            this.errorMessage.textContent = message;
            this.errorMessage.style.display = 'block';
            this.errorMessage.style.backgroundColor = '#ffebee';
            this.errorMessage.style.color = '#c62828';
            this.errorMessage.style.padding = '15px';
            this.errorMessage.style.borderRadius = '8px';
            this.errorMessage.style.marginTop = '15px';
        } else {
            alert(message);
        }
    },
    
    hideError() {
        if (this.errorMessage) {
            this.errorMessage.style.display = 'none';
        }
    },
    
    showSuccess(message) {
        if (this.errorMessage) {
            this.errorMessage.textContent = message;
            this.errorMessage.style.display = 'block';
            this.errorMessage.style.backgroundColor = '#e8f5e9';
            this.errorMessage.style.color = '#2e7d32';
            this.errorMessage.style.padding = '15px';
            this.errorMessage.style.borderRadius = '8px';
            this.errorMessage.style.marginTop = '15px';
            
            setTimeout(() => this.hideError(), 3000);
        }
    },
    
    // ========================================
    // 6. وظائف مساعدة
    // ========================================
    
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) {
            console.warn('CSRF token not found!');
        }
        return token || '';
    },
    
    updateCharCount() {
        const text = this.sourceText?.value || '';
        const charCount = document.getElementById('charCount');
        
        if (charCount) {
            charCount.textContent = `${text.length} / 5000 حرف`;
            
            if (text.length > 5000) {
                charCount.style.color = '#c62828';
            } else {
                charCount.style.color = '#666';
            }
        }
    },
    
    saveToHistory(request, translation) {
        try {
            const history = JSON.parse(localStorage.getItem('translationHistory') || '[]');
            
            history.unshift({
                id: Date.now(),
                source_text: request.text,
                translated_text: translation,
                source_lang: request.source_lang,
                target_lang: request.target_lang,
                timestamp: new Date().toISOString()
            });
            
            // الاحتفاظ بآخر 50 ترجمة فقط
            if (history.length > 50) {
                history.pop();
            }
            
            localStorage.setItem('translationHistory', JSON.stringify(history));
            
            // تحديث قائمة السجل إذا كانت موجودة
            this.updateHistoryList();
            
        } catch (error) {
            console.error('Error saving to history:', error);
        }
    },
    
    updateHistoryList() {
        const historyList = document.getElementById('historyList');
        if (!historyList) return;
        
        try {
            const history = JSON.parse(localStorage.getItem('translationHistory') || '[]');
            
            if (history.length === 0) {
                historyList.innerHTML = '<p style="text-align: center; color: #999;">لا يوجد سجل ترجمة</p>';
                return;
            }
            
            historyList.innerHTML = history.slice(0, 10).map(item => `
                <div class="history-item" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px; cursor: pointer;" 
                     onclick="translationForm.loadFromHistory(${item.id})">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="font-weight: bold;">${this.getLanguageName(item.source_lang)} → ${this.getLanguageName(item.target_lang)}</span>
                        <span style="color: #666; font-size: 12px;">${this.formatDate(item.timestamp)}</span>
                    </div>
                    <div style="color: #666; font-size: 14px;">
                        ${item.source_text.substring(0, 100)}${item.source_text.length > 100 ? '...' : ''}
                    </div>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Error updating history list:', error);
        }
    },
    
    loadFromHistory(id) {
        try {
            const history = JSON.parse(localStorage.getItem('translationHistory') || '[]');
            const item = history.find(h => h.id === id);
            
            if (item) {
                this.sourceText.value = item.source_text;
                this.targetText.value = item.translated_text;
                this.sourceLang.value = item.source_lang;
                this.targetLang.value = item.target_lang;
                
                this.updateCharCount();
            }
        } catch (error) {
            console.error('Error loading from history:', error);
        }
    },
    
    getLanguageName(code) {
        const languages = {
            'ar': 'العربية',
            'en': 'English',
            'es': 'Español',
            'fr': 'Français',
            'de': 'Deutsch',
            'it': 'Italiano',
            'pt': 'Português',
            'ru': 'Русский',
            'zh': '中文',
            'ja': '日本語'
        };
        return languages[code] || code;
    },
    
    formatDate(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'الآن';
        if (minutes < 60) return `منذ ${minutes} دقيقة`;
        if (hours < 24) return `منذ ${hours} ساعة`;
        if (days < 7) return `منذ ${days} يوم`;
        
        return date.toLocaleDateString('ar-SA');
    }
};

// ========================================
// 7. تهيئة النظام عند تحميل الصفحة
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    translationForm.init();
    translationForm.updateHistoryList();
    
    console.log('Translation system ready! 🚀');
});

// ========================================
// 8. تصدير للاستخدام العام
// ========================================

window.translationForm = translationForm;
