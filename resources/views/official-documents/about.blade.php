<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدمة ترجمة الوثائق الرسمية المعتمدة - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <div class="inline-block bg-blue-800/50 rounded-full px-6 py-2 mb-6">
                <span class="text-sm font-semibold">خدمة جديدة</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                ترجمة الوثائق الرسمية المعتمدة
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8">
                ترجمة احترافية للوثائق الرسمية مع الحفاظ على التخطيط الأصلي والأختام، معتمدة للسفارات والجهات الحكومية
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                @auth
                    <a href="{{ route('official.documents.upload.form') }}" 
                       class="bg-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        ارفع وثيقتك الآن
                    </a>
                    <a href="{{ route('official.documents.index') }}" 
                       class="bg-blue-800 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-900 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-folder-open"></i>
                        وثائقي
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="bg-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        سجل مجاناً
                    </a>
                    <a href="{{ route('login') }}" 
                       class="bg-blue-800 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-900 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        تسجيل الدخول
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">لماذا تختار خدمتنا؟</h2>
                <p class="text-xl text-gray-600">ترجمة احترافية معتمدة بأعلى معايير الجودة</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="bg-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-pdf text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">الحفاظ على التخطيط</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نحافظ 100% على تخطيط الوثيقة الأصلي بما في ذلك الأختام، التوقيعات، والعلامات المائية
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="bg-green-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">ختم رسمي معتمد</h3>
                    <p class="text-gray-700 leading-relaxed">
                        كل وثيقة تحصل على ختم Cultural Translate الرسمي مع رمز QR للتحقق ورقم معرف فريد
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="bg-purple-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">مقبولة دولياً</h3>
                    <p class="text-gray-700 leading-relaxed">
                        ترجماتنا معتمدة للسفارات، القنصليات، المحاكم، والجهات الحكومية حول العالم
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">كيف تعمل الخدمة؟</h2>
                <p class="text-xl text-gray-600">أربع خطوات بسيطة للحصول على ترجمة معتمدة</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">ارفع الوثيقة</h3>
                    <p class="text-gray-600">ارفع ملف PDF للوثيقة الرسمية (شهادة ميلاد، جواز سفر، عقد، إلخ)</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">الترجمة الذكية</h3>
                    <p class="text-gray-600">نظام الذكاء الاصطناعي يترجم النصوص مع الحفاظ على التخطيط الدقيق</p>
                </div>

                <div class="text-center">
                    <div class="bg-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">الختم والاعتماد</h3>
                    <p class="text-gray-600">إضافة ختم Cultural Translate الرسمي مع رقم تعريف فريد ورمز QR</p>
                </div>

                <div class="text-center">
                    <div class="bg-gold-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg" style="background-color: #daa520;">4</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">التحميل</h3>
                    <p class="text-gray-600">احصل على وثيقتك المترجمة والمعتمدة جاهزة للتقديم</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Supported Documents -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">الوثائق المدعومة</h2>
                <p class="text-xl text-gray-600">نترجم جميع أنواع الوثائق الرسمية</p>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-baby text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">شهادة ميلاد</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-ring text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">شهادة زواج</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-passport text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">جواز السفر</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-id-card text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">بطاقة الهوية</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-graduation-cap text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">الشهادات الدراسية</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-briefcase text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">عقود العمل</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-gavel text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">المستندات القانونية</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-file-invoice text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">الرخص التجارية</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-car text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">رخصة القيادة</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-user-shield text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">شهادة حسن سير وسلوك</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-university text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">كشوف الدرجات</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                    <i class="fas fa-ellipsis-h text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold">وثائق أخرى</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Languages -->
    <section class="py-16 bg-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">اللغات المدعومة</h2>
                <p class="text-xl text-gray-600">نترجم من وإلى 12 لغة عالمية</p>
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇸🇦 العربية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇬🇧 الإنجليزية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇳🇱 الهولندية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇫🇷 الفرنسية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇩🇪 الألمانية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇪🇸 الإسبانية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇮🇹 الإيطالية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇵🇹 البرتغالية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇷🇺 الروسية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇨🇳 الصينية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇯🇵 اليابانية</span>
                <span class="bg-white px-6 py-3 rounded-full font-semibold text-gray-700 shadow-md">🇰🇷 الكورية</span>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">جاهز للبدء؟</h2>
            <p class="text-xl text-blue-100 mb-8">
                احصل على ترجمة معتمدة لوثائقك الرسمية في دقائق
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                @auth
                    <a href="{{ route('official.documents.upload.form') }}" 
                       class="bg-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        ابدأ الترجمة الآن
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="bg-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        إنشاء حساب مجاني
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <p class="text-gray-400">
                    © {{ date('Y') }} Cultural Translate. جميع الحقوق محفوظة.
                </p>
                <div class="flex gap-6">
                    <a href="/" class="text-gray-400 hover:text-white transition-colors">الرئيسية</a>
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">التسجيل</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
