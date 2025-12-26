{{-- Language Switcher Component with All Languages --}}
<div class="language-switcher" style="position: relative; display: inline-block;">
    <button id="languageSwitcherBtn" style="
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: all 0.2s;
    " onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e5e7eb'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        </svg>
        <span id="currentLanguage">{{ strtoupper(app()->getLocale()) }}</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>
    
    <div id="languageDropdown" style="
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 8px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
    ">
        @php
        $languages = [
            ['code' => 'en', 'name' => 'English', 'flag' => '🇬🇧'],
            ['code' => 'ar', 'name' => 'العربية', 'flag' => '🇸🇦'],
            ['code' => 'es', 'name' => 'Español', 'flag' => '🇪🇸'],
            ['code' => 'fr', 'name' => 'Français', 'flag' => '🇫🇷'],
            ['code' => 'de', 'name' => 'Deutsch', 'flag' => '🇩🇪'],
            ['code' => 'it', 'name' => 'Italiano', 'flag' => '🇮🇹'],
            ['code' => 'pt', 'name' => 'Português', 'flag' => '🇵🇹'],
            ['code' => 'ru', 'name' => 'Русский', 'flag' => '🇷🇺'],
            ['code' => 'zh', 'name' => '中文', 'flag' => '🇨🇳'],
            ['code' => 'ja', 'name' => '日本語', 'flag' => '🇯🇵'],
            ['code' => 'ko', 'name' => '한국어', 'flag' => '🇰🇷'],
            ['code' => 'hi', 'name' => 'हिन्दी', 'flag' => '🇮🇳'],
            ['code' => 'tr', 'name' => 'Türkçe', 'flag' => '🇹🇷'],
            ['code' => 'nl', 'name' => 'Nederlands', 'flag' => '🇳🇱'],
            ['code' => 'pl', 'name' => 'Polski', 'flag' => '🇵🇱'],
        ];
        $currentLocale = app()->getLocale();
        @endphp
        
        @foreach($languages as $index => $language)
        <a href="{{ route('language.switch', $language['code']) }}" style="
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: #374151;
            transition: background 0.2s;
            {{ $index < count($languages) - 1 ? 'border-bottom: 1px solid #f3f4f6;' : '' }}
        " onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
            <span style="font-size: 24px;">{{ $language['flag'] }}</span>
            <div>
                <div style="font-weight: 500;">{{ $language['name'] }}</div>
                <div style="font-size: 12px; color: #6b7280;">{{ strtoupper($language['code']) }}</div>
            </div>
            @if($currentLocale == $language['code'])
            <svg style="margin-left: auto;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            @endif
        </a>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('languageSwitcherBtn');
    const dropdown = document.getElementById('languageDropdown');
    const currentLanguageSpan = document.getElementById('currentLanguage');
    
    if (btn && dropdown) {
        // Toggle dropdown on button click
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = dropdown.style.display === 'block';
            dropdown.style.display = isVisible ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
        
        // Allow clicks on language links to work (don't stop propagation)
        dropdown.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Let the link work naturally
                console.log('Switching to language:', this.getAttribute('href'));
            });
        });
        
        // Update current language display from session/cookie
        const updateCurrentLanguage = function() {
            // Get locale from cookie
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'locale') {
                    try {
                        // Decode the encrypted cookie value
                        const decoded = decodeURIComponent(value);
                        // Try to extract locale code (simple pattern matching)
                        const match = decoded.match(/[a-z]{2}/i);
                        if (match && currentLanguageSpan) {
                            currentLanguageSpan.textContent = match[0].toUpperCase();
                        }
                    } catch(e) {
                        console.log('Could not decode locale cookie');
                    }
                }
            }
        };
        
        updateCurrentLanguage();
        
        // Listen for page visibility changes (when user returns to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateCurrentLanguage();
            }
        });
    }
});
</script>
