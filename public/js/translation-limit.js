// Translation Limit System for Guest Users
// Limits: 2 translations per day, 400 characters max per translation, for 3 days only

function checkTranslationLimit() {
    const now = new Date();
    const limitData = JSON.parse(localStorage.getItem('guestTranslationLimit') || '{}');
    
    // Initialize if first time
    if (!limitData.firstUseDate) {
        limitData.firstUseDate = now.toISOString();
        limitData.dailyCount = 0;
        limitData.lastResetDate = now.toDateString();
        localStorage.setItem('guestTranslationLimit', JSON.stringify(limitData));
    }
    
    // Check if 3 days have passed since first use
    const firstUse = new Date(limitData.firstUseDate);
    const daysSinceFirstUse = Math.floor((now - firstUse) / (1000 * 60 * 60 * 24));
    
    if (daysSinceFirstUse >= 3) {
        return {
            allowed: false,
            reason: 'trial_expired',
            message: 'Your 3-day free trial has expired. Please sign up to continue using our translation services.'
        };
    }
    
    // Reset daily count if it's a new day
    if (limitData.lastResetDate !== now.toDateString()) {
        limitData.dailyCount = 0;
        limitData.lastResetDate = now.toDateString();
        localStorage.setItem('guestTranslationLimit', JSON.stringify(limitData));
    }
    
    // Check daily limit (2 translations per day)
    if (limitData.dailyCount >= 2) {
        return {
            allowed: false,
            reason: 'daily_limit_reached',
            message: 'You have reached your daily limit of 2 translations. Please sign up for unlimited translations or try again tomorrow.'
        };
    }
    
    return {
        allowed: true,
        remainingToday: 2 - limitData.dailyCount,
        daysRemaining: 3 - daysSinceFirstUse
    };
}

function incrementTranslationCount() {
    const limitData = JSON.parse(localStorage.getItem('guestTranslationLimit') || '{}');
    limitData.dailyCount = (limitData.dailyCount || 0) + 1;
    localStorage.setItem('guestTranslationLimit', JSON.stringify(limitData));
}

async function translateDemo() {
    const sourceText = document.getElementById('demoSourceText').value.trim();
    const sourceLang = document.getElementById('demoSourceLang').value;
    const targetLang = document.getElementById('demoTargetLang').value;
    
    // Check if text is empty
    if (!sourceText) {
        showDemoError("Please enter text to translate");
        return;
    }
    
    // Check character limit (400 characters max)
    if (sourceText.length > 400) {
        showDemoError('Text is too long (' + sourceText.length + ' characters). Maximum 400 characters allowed for guest users. Please sign up for unlimited character limit.');
        return;
    }
    
    // Check if same language
    if (sourceLang === targetLang) {
        showDemoError("Source and target languages cannot be the same");
        return;
    }
    
    // Check translation limit for guest users
    const limitCheck = checkTranslationLimit();
    if (!limitCheck.allowed) {
        const errorDiv = document.getElementById('demoError');
        errorDiv.innerHTML = '<div class="text-center"><p class="mb-4">' + limitCheck.message + '</p><a href="/register" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition">Sign Up Now - It\'s Free!</a></div>';
        errorDiv.classList.remove('hidden');
        return;
    }
    
    const btn = document.getElementById('demoTranslateBtn');
    const btnText = document.getElementById('demoBtnText');
    const btnLoader = document.getElementById('demoBtnLoader');
    const translatedDiv = document.getElementById('demoTranslatedText');
    
    btn.disabled = true;
    btnText.textContent = "Translating...";
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
            translatedDiv.innerHTML = '<div class="text-gray-800">' + data.translated_text + '</div>';
            
            // Increment translation count after successful translation
            incrementTranslationCount();
            
            // Show remaining translations info
            const updatedLimit = checkTranslationLimit();
            if (updatedLimit.allowed) {
                const infoDiv = document.createElement('div');
                infoDiv.className = 'mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-sm text-center';
                infoDiv.innerHTML = 'ℹ️ You have <strong>' + updatedLimit.remainingToday + '</strong> translation(s) remaining today. Trial expires in <strong>' + updatedLimit.daysRemaining + '</strong> day(s). <a href="/register" class="underline font-semibold">Sign up</a> for unlimited access!';
                translatedDiv.appendChild(infoDiv);
            }
        } else {
            showDemoError(data.message || 'Translation failed');
        }
    } catch (error) {
        showDemoError('An error occurred. Please try again.');
    } finally {
        btn.disabled = false;
        btnText.textContent = "Translate Now";
        btnLoader.classList.add('hidden');
        btnText.classList.remove('hidden');
    }
}

function showDemoError(message) {
    const errorDiv = document.getElementById('demoError');
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
}
