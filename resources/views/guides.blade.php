@extends('layouts.app')

{{-- SEO Meta Tags --}}
@section('title', 'Tutorial Guides - CulturalTranslate')
@section('meta')
    <meta name="description" content="Explore our comprehensive tutorial guides and documentation for using the CulturalTranslate platform effectively. Learn how to get started, use advanced features, and troubleshoot common issues.">
    <meta name="keywords" content="CulturalTranslate guides, platform tutorials, how-to, documentation, user manual, help center">
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-900">
        {{-- Hero Section --}}
        <div class="py-16 sm:py-24 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                    <span class="block xl:inline">مركز</span>
                    <span class="block text-indigo-600 xl:inline">الإرشادات والدروس</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    تعلم كيفية استخدام منصة CulturalTranslate بكفاءة. ابدأ رحلتك مع أدلة خطوة بخطوة، وشروحات مفصلة للميزات، ونصائح احترافية.
                </p>
                <div class="mt-10 max-w-lg mx-auto">
                    <label for="search-guides" class="sr-only">البحث في الإرشادات</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="search-guides" name="search-guides" class="block w-full pl-5 pr-12 py-3 border border-gray-300 rounded-md placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-right" placeholder="ابحث عن دليل أو ميزة..." type="search">
                    </div>
                </div>
            </div>
        </div>

        {{-- Guides Grid Section --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="border-b border-gray-200 pb-5 mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">أحدث الإرشادات</h2>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Guide Card 1 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">البدء</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            كيفية إنشاء مشروعك الأول
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            دليل خطوة بخطوة لإعداد حسابك، وتكوين الإعدادات الأساسية، وبدء أول مهمة ترجمة.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Guide Card 2 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">الميزات المتقدمة</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            استخدام ذاكرة الترجمة بفعالية
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            تعمق في ميزات ذاكرة الترجمة، وكيفية تحميلها، وتطبيقها لتحسين الاتساق والسرعة.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Guide Card 3 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">استكشاف الأخطاء</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            حل مشكلات التنسيق الشائعة
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            نصائح وحيل لتصحيح أخطاء التنسيق، والتعامل مع الملفات المعقدة، وضمان جودة الإخراج.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Guide Card 4 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">الإعدادات</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            إدارة فريقك وأذونات الوصول
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            تعرف على كيفية إضافة أعضاء الفريق، وتعيين الأدوار، وإدارة أذونات الوصول إلى المشاريع المختلفة.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Guide Card 5 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">التكامل</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            ربط CulturalTranslate بواجهة برمجة التطبيقات (API)
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            شرح مفصل لتوثيق واجهة برمجة التطبيقات، ونقاط النهاية الرئيسية، وأمثلة على التعليمات البرمجية للتكامل.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Guide Card 6 --}}
                <a href="#" class="block group bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">الفواتير</p>
                        <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition duration-300">
                            فهم التسعير وإدارة الاشتراكات
                        </h3>
                        <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                            دليل شامل لكيفية عمل نظام التسعير، وكيفية ترقية أو تخفيض اشتراكك، وعرض سجل الفواتير.
                        </p>
                        <div class="mt-4">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                اقرأ الدليل <span aria-hidden="true">&larr;</span>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Call to Action / Footer Section --}}
            <div class="mt-20 pt-10 border-t border-gray-200 dark:border-gray-700 text-center">
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">لم تجد ما تبحث عنه؟</h3>
                <p class="mt-3 text-lg text-gray-500 dark:text-gray-400">
                    تواصل مع فريق الدعم لدينا للحصول على مساعدة شخصية.
                </p>
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        تواصل مع الدعم
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
