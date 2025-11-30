<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cultural Translate - الترجمة الثقافية الذكية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        body { background: linear-gradient(135deg, #0D0D0D 0%, #1a1a2e 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); transition: all 0.3s ease; }
        .glass-card:hover { background: rgba(255, 255, 255, 0.05); border-color: rgba(108, 99, 255, 0.3); transform: translateY(-5px); }
        .btn-premium { background: linear-gradient(135deg, #6C63FF 0%, #5A52D5 100%); box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3); transition: all 0.3s ease; }
        .btn-premium:hover { background: linear-gradient(135deg, #5A52D5 0%, #4840B0 100%); box-shadow: 0 15px 40px rgba(108, 99, 255, 0.5); transform: translateY(-2px); }
        .gradient-text { background: linear-gradient(135deg, #6C63FF 0%, #5A52D5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .floating-shapes { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: 0; pointer-events: none; }
        .shape { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.1; animation: float 25s infinite ease-in-out; }
        .shape-1 { width: 500px; height: 500px; background: #6C63FF; top: -10%; left: -10%; }
        .shape-2 { width: 400px; height: 400px; background: #5A52D5; bottom: -10%; right: -10%; animation-delay: 8s; }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(30px, -30px); } }
        nav { backdrop-filter: blur(20px); background: rgba(13, 13, 13, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="text-white">
    <div class="floating-shapes"><div class="shape shape-1"></div><div class="shape shape-2"></div></div>
    
    <!-- Nav -->
    <nav class="fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    </div>
                    <span class="text-2xl font-bold gradient-text">Cultural Translate</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-4 py-2">تسجيل الدخول</a>
                    <a href="{{ route('trial') }}" class="btn-premium px-6 py-2.5 rounded-xl font-semibold">ابدأ مجاناً</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 fade-in-up">
                الترجمة الثقافية <span class="gradient-text">الذكية</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-400 mb-10 max-w-3xl mx-auto fade-in-up" style="animation-delay: 0.2s;">
                ترجمة دقيقة تحترم السياق الثقافي والعاطفي - ليست مجرد ترجمة حرفية
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('trial') }}" class="btn-premium px-8 py-4 rounded-xl text-lg font-semibold">ابدأ تجربتك المجانية</a>
                <a href="#demo" class="glass-card px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/10">شاهد العرض التوضيحي</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="relative py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center"><div class="text-4xl font-bold gradient-text mb-2">13+</div><div class="text-gray-400">لغة مدعومة</div></div>
                <div class="text-center"><div class="text-4xl font-bold gradient-text mb-2">10K+</div><div class="text-gray-400">كلمة مجانية</div></div>
                <div class="text-center"><div class="text-4xl font-bold gradient-text mb-2">7</div><div class="text-gray-400">أنماط ترجمة</div></div>
                <div class="text-center"><div class="text-4xl font-bold gradient-text mb-2">99%</div><div class="text-gray-400">دقة الترجمة</div></div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="relative py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">لماذا <span class="gradient-text">Cultural Translate</span>؟</h2>
                <p class="text-xl text-gray-400">ترجمة ذكية تفهم الثقافة والسياق</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">ترجمة فورية</h3>
                    <p class="text-gray-400">احصل على ترجمة دقيقة في ثوانٍ معدودة باستخدام الذكاء الاصطناعي المتقدم</p>
                </div>
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">سياق ثقافي</h3>
                    <p class="text-gray-400">نفهم الفروقات الثقافية ونترجم بما يناسب الجمهور المستهدف</p>
                </div>
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">آمن ومحمي</h3>
                    <p class="text-gray-400">بياناتك محمية بالكامل مع تشفير من الدرجة العسكرية</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="relative py-20 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card p-12 rounded-3xl text-center">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">جاهز للبدء؟</h2>
                <p class="text-xl text-gray-400 mb-8">احصل على 14 يوم تجربة مجانية + 10,000 كلمة ترجمة</p>
                <a href="{{ route('trial') }}" class="btn-premium px-10 py-4 rounded-xl text-lg font-semibold inline-block">ابدأ الآن مجاناً</a>
                <p class="text-sm text-gray-500 mt-4">لا حاجة لبطاقة ائتمان</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative py-12 px-4 border-t border-white/10">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg"></div>
                        <span class="font-bold gradient-text">Cultural Translate</span>
                    </div>
                    <p class="text-gray-400 text-sm">الترجمة الثقافية الذكية</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">المنتج</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <a href="#" class="block hover:text-white">المميزات</a>
                        <a href="#" class="block hover:text-white">الأسعار</a>
                        <a href="#" class="block hover:text-white">API</a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">الشركة</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <a href="#" class="block hover:text-white">من نحن</a>
                        <a href="#" class="block hover:text-white">اتصل بنا</a>
                        <a href="#" class="block hover:text-white">المدونة</a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">قانوني</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <a href="#" class="block hover:text-white">الشروط</a>
                        <a href="#" class="block hover:text-white">الخصوصية</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2024 Cultural Translate. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
</body>
</html>
