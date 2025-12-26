<?php

/**
 * ترجمة تلقائية لجميع النصوص إلى 15 لغة
 * Auto-translate all texts to 15 languages
 */

echo "🌍 Starting comprehensive translation to 15 languages...\n\n";

$languages = [
    'en' => 'English',
    'ar' => 'العربية',
    'de' => 'Deutsch',
    'es' => 'Español',
    'fr' => 'Français',
    'hi' => 'हिंदी',
    'it' => 'Italiano',
    'ja' => '日本語',
    'ko' => '한국어',
    'nl' => 'Nederlands',
    'pl' => 'Polski',
    'pt' => 'Português',
    'ru' => 'Русский',
    'tr' => 'Türkçe',
    'zh' => '中文'
];

// القاموس الكامل بترجمات احترافية
$translations = [
    // Navigation
    'nav.home' => [
        'en' => 'Home', 'ar' => 'الرئيسية', 'de' => 'Startseite', 'es' => 'Inicio',
        'fr' => 'Accueil', 'hi' => 'होम', 'it' => 'Home', 'ja' => 'ホーム',
        'ko' => '홈', 'nl' => 'Home', 'pl' => 'Strona główna', 'pt' => 'Início',
        'ru' => 'Главная', 'tr' => 'Ana Sayfa', 'zh' => '首页'
    ],
    'nav.features' => [
        'en' => 'Features', 'ar' => 'الميزات', 'de' => 'Funktionen', 'es' => 'Características',
        'fr' => 'Fonctionnalités', 'hi' => 'सुविधाएँ', 'it' => 'Funzionalità', 'ja' => '機能',
        'ko' => '기능', 'nl' => 'Functies', 'pl' => 'Funkcje', 'pt' => 'Recursos',
        'ru' => 'Возможности', 'tr' => 'Özellikler', 'zh' => '功能'
    ],
    'nav.pricing' => [
        'en' => 'Pricing', 'ar' => 'الأسعار', 'de' => 'Preise', 'es' => 'Precios',
        'fr' => 'Tarifs', 'hi' => 'मूल्य निर्धारण', 'it' => 'Prezzi', 'ja' => '料金',
        'ko' => '가격', 'nl' => 'Prijzen', 'pl' => 'Cennik', 'pt' => 'Preços',
        'ru' => 'Цены', 'tr' => 'Fiyatlandırma', 'zh' => '价格'
    ],
    'nav.about' => [
        'en' => 'About', 'ar' => 'عن المنصة', 'de' => 'Über uns', 'es' => 'Acerca de',
        'fr' => 'À propos', 'hi' => 'हमारे बारे में', 'it' => 'Chi siamo', 'ja' => '会社概要',
        'ko' => '회사 소개', 'nl' => 'Over ons', 'pl' => 'O nas', 'pt' => 'Sobre',
        'ru' => 'О нас', 'tr' => 'Hakkımızda', 'zh' => '关于我们'
    ],
    'nav.contact' => [
        'en' => 'Contact', 'ar' => 'اتصل بنا', 'de' => 'Kontakt', 'es' => 'Contacto',
        'fr' => 'Contact', 'hi' => 'संपर्क करें', 'it' => 'Contatti', 'ja' => 'お問い合わせ',
        'ko' => '문의하기', 'nl' => 'Contact', 'pl' => 'Kontakt', 'pt' => 'Contato',
        'ru' => 'Контакты', 'tr' => 'İletişim', 'zh' => '联系我们'
    ],
    'nav.login' => [
        'en' => 'Log in', 'ar' => 'تسجيل الدخول', 'de' => 'Anmelden', 'es' => 'Iniciar sesión',
        'fr' => 'Se connecter', 'hi' => 'लॉग इन करें', 'it' => 'Accedi', 'ja' => 'ログイン',
        'ko' => '로그인', 'nl' => 'Inloggen', 'pl' => 'Zaloguj się', 'pt' => 'Entrar',
        'ru' => 'Войти', 'tr' => 'Giriş Yap', 'zh' => '登录'
    ],
    'nav.register' => [
        'en' => 'Register', 'ar' => 'تسجيل', 'de' => 'Registrieren', 'es' => 'Registrarse',
        'fr' => "S'inscrire", 'hi' => 'रजिस्टर करें', 'it' => 'Registrati', 'ja' => '登録',
        'ko' => '가입하기', 'nl' => 'Registreren', 'pl' => 'Zarejestruj się', 'pt' => 'Registrar',
        'ru' => 'Регистрация', 'tr' => 'Kayıt Ol', 'zh' => '注册'
    ],
    'nav.dashboard' => [
        'en' => 'Dashboard', 'ar' => 'لوحة التحكم', 'de' => 'Dashboard', 'es' => 'Panel',
        'fr' => 'Tableau de bord', 'hi' => 'डैशबोर्ड', 'it' => 'Dashboard', 'ja' => 'ダッシュボード',
        'ko' => '대시보드', 'nl' => 'Dashboard', 'pl' => 'Panel', 'pt' => 'Painel',
        'ru' => 'Панель управления', 'tr' => 'Kontrol Paneli', 'zh' => '仪表板'
    ],
    
    // Hero Section
    'hero.title' => [
        'en' => 'AI-Powered Cultural Translation Platform',
        'ar' => 'منصة الترجمة الثقافية المدعومة بالذكاء الاصطناعي',
        'de' => 'KI-gestützte kulturelle Übersetzungsplattform',
        'es' => 'Plataforma de Traducción Cultural con IA',
        'fr' => 'Plateforme de Traduction Culturelle IA',
        'hi' => 'AI-संचालित सांस्कृतिक अनुवाद प्लेटफॉर्म',
        'it' => 'Piattaforma di Traduzione Culturale AI',
        'ja' => 'AI搭載文化翻訳プラットフォーム',
        'ko' => 'AI 기반 문화 번역 플랫폼',
        'nl' => 'AI-aangedreven Cultureel Vertaalplatform',
        'pl' => 'Platforma Tłumaczeń Kulturowych AI',
        'pt' => 'Plataforma de Tradução Cultural com IA',
        'ru' => 'Платформа культурного перевода с ИИ',
        'tr' => 'YZ Destekli Kültürel Çeviri Platformu',
        'zh' => 'AI驱动的文化翻译平台'
    ],
    'hero.subtitle' => [
        'en' => 'Preserve Context & Meaning Across Languages',
        'ar' => 'احتفظ بالسياق والمعنى عبر اللغات',
        'de' => 'Kontext und Bedeutung über Sprachen hinweg bewahren',
        'es' => 'Preserve el Contexto y el Significado entre Idiomas',
        'fr' => 'Préservez le Contexte et la Signification entre les Langues',
        'hi' => 'भाषाओं में संदर्भ और अर्थ संरक्षित करें',
        'it' => 'Preserva Contesto e Significato tra le Lingue',
        'ja' => '言語間でコンテキストと意味を保持',
        'ko' => '언어 간 문맥과 의미 보존',
        'nl' => 'Behoud Context en Betekenis over Talen heen',
        'pl' => 'Zachowaj Kontekst i Znaczenie między Językami',
        'pt' => 'Preserve Contexto e Significado entre Idiomas',
        'ru' => 'Сохраняйте контекст и смысл между языками',
        'tr' => 'Diller Arası Bağlam ve Anlam Koruma',
        'zh' => '跨语言保留上下文和含义'
    ],
    'hero.get_started' => [
        'en' => 'Get Started', 'ar' => 'ابدأ الآن', 'de' => 'Jetzt starten', 'es' => 'Comenzar',
        'fr' => 'Commencer', 'hi' => 'शुरू करें', 'it' => 'Inizia', 'ja' => '始める',
        'ko' => '시작하기', 'nl' => 'Begin', 'pl' => 'Rozpocznij', 'pt' => 'Começar',
        'ru' => 'Начать', 'tr' => 'Başlayın', 'zh' => '开始使用'
    ],
    'hero.learn_more' => [
        'en' => 'Learn More', 'ar' => 'اعرف المزيد', 'de' => 'Mehr erfahren', 'es' => 'Saber más',
        'fr' => 'En savoir plus', 'hi' => 'और जानें', 'it' => 'Scopri di più', 'ja' => '詳細を見る',
        'ko' => '자세히 보기', 'nl' => 'Meer informatie', 'pl' => 'Dowiedz się więcej', 'pt' => 'Saiba mais',
        'ru' => 'Узнать больше', 'tr' => 'Daha Fazla Bilgi', 'zh' => '了解更多'
    ],
    
    // Features
    'features.title' => [
        'en' => 'Powerful Features',
        'ar' => 'ميزات قوية',
        'de' => 'Leistungsstarke Funktionen',
        'es' => 'Características Poderosas',
        'fr' => 'Fonctionnalités Puissantes',
        'hi' => 'शक्तिशाली सुविधाएँ',
        'it' => 'Funzionalità Potenti',
        'ja' => '強力な機能',
        'ko' => '강력한 기능',
        'nl' => 'Krachtige Functies',
        'pl' => 'Potężne Funkcje',
        'pt' => 'Recursos Poderosos',
        'ru' => 'Мощные функции',
        'tr' => 'Güçlü Özellikler',
        'zh' => '强大功能'
    ],
    'features.ai_powered' => [
        'en' => 'AI-Powered Translation',
        'ar' => 'ترجمة مدعومة بالذكاء الاصطناعي',
        'de' => 'KI-gestützte Übersetzung',
        'es' => 'Traducción con IA',
        'fr' => 'Traduction IA',
        'hi' => 'AI-संचालित अनुवाद',
        'it' => 'Traduzione AI',
        'ja' => 'AI翻訳',
        'ko' => 'AI 번역',
        'nl' => 'AI-vertaling',
        'pl' => 'Tłumaczenie AI',
        'pt' => 'Tradução com IA',
        'ru' => 'Перевод с ИИ',
        'tr' => 'YZ Çeviri',
        'zh' => 'AI翻译'
    ],
    
    // Pricing
    'pricing.title' => [
        'en' => 'Simple, Transparent Pricing',
        'ar' => 'أسعار بسيطة وشفافة',
        'de' => 'Einfache, transparente Preise',
        'es' => 'Precios Simples y Transparentes',
        'fr' => 'Tarifs Simples et Transparents',
        'hi' => 'सरल, पारदर्शी मूल्य निर्धारण',
        'it' => 'Prezzi Semplici e Trasparenti',
        'ja' => 'シンプルで透明な料金',
        'ko' => '간단하고 투명한 가격',
        'nl' => 'Eenvoudige, Transparante Prijzen',
        'pl' => 'Proste, Przejrzyste Ceny',
        'pt' => 'Preços Simples e Transparentes',
        'ru' => 'Простые, прозрачные цены',
        'tr' => 'Basit, Şeffaf Fiyatlandırma',
        'zh' => '简单透明的定价'
    ],
    'pricing.per_month' => [
        'en' => '/month', 'ar' => '/شهرياً', 'de' => '/Monat', 'es' => '/mes',
        'fr' => '/mois', 'hi' => '/महीना', 'it' => '/mese', 'ja' => '/月',
        'ko' => '/월', 'nl' => '/maand', 'pl' => '/miesiąc', 'pt' => '/mês',
        'ru' => '/месяц', 'tr' => '/ay', 'zh' => '/月'
    ],
    
    // Forms
    'forms.save' => [
        'en' => 'Save', 'ar' => 'حفظ', 'de' => 'Speichern', 'es' => 'Guardar',
        'fr' => 'Enregistrer', 'hi' => 'सहेजें', 'it' => 'Salva', 'ja' => '保存',
        'ko' => '저장', 'nl' => 'Opslaan', 'pl' => 'Zapisz', 'pt' => 'Salvar',
        'ru' => 'Сохранить', 'tr' => 'Kaydet', 'zh' => '保存'
    ],
    'forms.cancel' => [
        'en' => 'Cancel', 'ar' => 'إلغاء', 'de' => 'Abbrechen', 'es' => 'Cancelar',
        'fr' => 'Annuler', 'hi' => 'रद्द करें', 'it' => 'Annulla', 'ja' => 'キャンセル',
        'ko' => '취소', 'nl' => 'Annuleren', 'pl' => 'Anuluj', 'pt' => 'Cancelar',
        'ru' => 'Отмена', 'tr' => 'İptal', 'zh' => '取消'
    ],
    'forms.delete' => [
        'en' => 'Delete', 'ar' => 'حذف', 'de' => 'Löschen', 'es' => 'Eliminar',
        'fr' => 'Supprimer', 'hi' => 'हटाएं', 'it' => 'Elimina', 'ja' => '削除',
        'ko' => '삭제', 'nl' => 'Verwijderen', 'pl' => 'Usuń', 'pt' => 'Excluir',
        'ru' => 'Удалить', 'tr' => 'Sil', 'zh' => '删除'
    ],
    'forms.search' => [
        'en' => 'Search', 'ar' => 'بحث', 'de' => 'Suchen', 'es' => 'Buscar',
        'fr' => 'Rechercher', 'hi' => 'खोजें', 'it' => 'Cerca', 'ja' => '検索',
        'ko' => '검색', 'nl' => 'Zoeken', 'pl' => 'Szukaj', 'pt' => 'Pesquisar',
        'ru' => 'Поиск', 'tr' => 'Ara', 'zh' => '搜索'
    ],
    
    // Messages
    'messages.success' => [
        'en' => 'Success!', 'ar' => 'نجح!', 'de' => 'Erfolg!', 'es' => '¡Éxito!',
        'fr' => 'Succès!', 'hi' => 'सफलता!', 'it' => 'Successo!', 'ja' => '成功！',
        'ko' => '성공!', 'nl' => 'Succes!', 'pl' => 'Sukces!', 'pt' => 'Sucesso!',
        'ru' => 'Успех!', 'tr' => 'Başarılı!', 'zh' => '成功！'
    ],
    'messages.error' => [
        'en' => 'Error!', 'ar' => 'خطأ!', 'de' => 'Fehler!', 'es' => '¡Error!',
        'fr' => 'Erreur!', 'hi' => 'त्रुटि!', 'it' => 'Errore!', 'ja' => 'エラー！',
        'ko' => '오류!', 'nl' => 'Fout!', 'pl' => 'Błąd!', 'pt' => 'Erro!',
        'ru' => 'Ошибка!', 'tr' => 'Hata!', 'zh' => '错误！'
    ],
    'messages.loading' => [
        'en' => 'Loading...', 'ar' => 'جار التحميل...', 'de' => 'Laden...', 'es' => 'Cargando...',
        'fr' => 'Chargement...', 'hi' => 'लोड हो रहा है...', 'it' => 'Caricamento...', 'ja' => '読み込み中...',
        'ko' => '로딩 중...', 'nl' => 'Laden...', 'pl' => 'Ładowanie...', 'pt' => 'Carregando...',
        'ru' => 'Загрузка...', 'tr' => 'Yükleniyor...', 'zh' => '加载中...'
    ],
];

echo "📖 Creating comprehensive translation files...\n\n";

// إنشاء ملفات الترجمة لكل لغة
foreach ($languages as $langCode => $langName) {
    $langDir = __DIR__ . "/lang/{$langCode}";
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }
    
    // تجميع الترجمات حسب الفئة
    $categorized = [];
    foreach ($translations as $key => $values) {
        [$category, $item] = explode('.', $key, 2);
        if (!isset($categorized[$category])) {
            $categorized[$category] = [];
        }
        $categorized[$category][$item] = $values[$langCode] ?? $values['en'];
    }
    
    // حفظ في messages.php
    $phpContent = "<?php\n\nreturn [\n";
    foreach ($categorized as $category => $items) {
        $phpContent .= "\n    // " . ucfirst($category) . "\n";
        foreach ($items as $key => $value) {
            $escapedValue = addslashes($value);
            $phpContent .= "    '{$category}.{$key}' => '{$escapedValue}',\n";
        }
    }
    $phpContent .= "];\n";
    
    file_put_contents("{$langDir}/messages.php", $phpContent);
    
    echo "✅ {$langCode} - {$langName} ({" . count($translations) . " translations)\n";
}

echo "\n🎉 Translation complete!\n";
echo "Total: " . count($translations) . " texts translated to " . count($languages) . " languages\n";
echo "Files created in lang/ directory\n";
