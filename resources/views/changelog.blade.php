@extends('layouts.app')

@section('title', 'سجل التغييرات - CulturalTranslate')

@section('meta_description', 'تتبع أحدث الميزات والتحسينات والإصلاحات في منصة CulturalTranslate.')

@section('content')
    <!--
    ======================================================================
    Changelog Page - سجل التغييرات
    Design: Clean, Professional Timeline with Tailwind CSS
    ======================================================================
    -->
    <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl">
                سجل التغييرات
            </h1>
            <p class="mt-4 text-xl text-gray-500">
                تتبع أحدث الميزات والتحسينات والإصلاحات في منصة CulturalTranslate.
            </p>
        </header>

        <!-- Timeline Container -->
        <div class="relative max-w-3xl mx-auto">
            <!-- Vertical Line -->
            <div class="absolute left-3 top-0 bottom-0 w-0.5 bg-gray-200 md:left-1/2 md:transform md:-translate-x-1/2"></div>

            <!-- Version Entry 2.1.0 (Latest) -->
            <div class="mb-8 flex justify-between items-center w-full right-timeline">
                <div class="order-1 w-5/12 hidden md:block"></div>
                <div class="z-10 flex items-center order-1 bg-indigo-600 shadow-xl w-8 h-8 rounded-full ring-4 ring-white">
                    <h1 class="mx-auto font-semibold text-lg text-white">🎉</h1>
                </div>
                <div class="order-1 bg-white rounded-xl shadow-lg w-full md:w-5/12 px-6 py-4 border border-gray-100">
                    <p class="mb-3 text-xs text-gray-500">2025-12-10</p>
                    <h3 class="mb-3 font-bold text-xl text-indigo-600">الإصدار 2.1.0 - إطلاق الميزات الجديدة</h3>
                    <ul class="list-none space-y-3 text-gray-700 text-sm">
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">ميزة جديدة:</strong> إضافة دعم للغة "الماندرين الصينية" في الترجمة السياقية.
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">تحسين:</strong> تحسين سرعة تحميل الواجهة الأمامية بنسبة 30% عبر ضغط الأصول.
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-red-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">إصلاح خطأ:</strong> حل مشكلة عدم عرض النتائج بشكل صحيح على متصفح Safari.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Version Entry 2.0.0 -->
            <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                <div class="order-1 w-5/12 hidden md:block"></div>
                <div class="z-10 flex items-center order-1 bg-blue-500 shadow-xl w-8 h-8 rounded-full ring-4 ring-white">
                    <h1 class="mx-auto font-semibold text-lg text-white">🚀</h1>
                </div>
                <div class="order-1 bg-white rounded-xl shadow-lg w-full md:w-5/12 px-6 py-4 border border-gray-100">
                    <p class="mb-3 text-xs text-gray-500">2025-11-15</p>
                    <h3 class="mb-3 font-bold text-xl text-blue-600">الإصدار 2.0.0 - إعادة تصميم شاملة</h3>
                    <ul class="list-none space-y-3 text-gray-700 text-sm">
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">ميزة جديدة:</strong> إطلاق واجهة مستخدم جديدة بالكامل مع التركيز على تجربة المستخدم.
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">تحسين:</strong> دمج محرك ترجمة سياقي جديد لنتائج أكثر دقة.
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-red-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">إصلاح خطأ:</strong> إصلاح مشكلة في تسجيل الدخول عبر خدمات الطرف الثالث.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Version Entry 1.5.0 -->
            <div class="mb-8 flex justify-between items-center w-full right-timeline">
                <div class="order-1 w-5/12 hidden md:block"></div>
                <div class="z-10 flex items-center order-1 bg-purple-500 shadow-xl w-8 h-8 rounded-full ring-4 ring-white">
                    <h1 class="mx-auto font-semibold text-lg text-white">✨</h1>
                </div>
                <div class="order-1 bg-white rounded-xl shadow-lg w-full md:w-5/12 px-6 py-4 border border-gray-100">
                    <p class="mb-3 text-xs text-gray-500">2025-10-01</p>
                    <h3 class="mb-3 font-bold text-xl text-purple-600">الإصدار 1.5.0 - تحسينات الأداء</h3>
                    <ul class="list-none space-y-3 text-gray-700 text-sm">
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">تحسين:</strong> تقليل زمن استجابة واجهة برمجة التطبيقات (API) بنسبة 50%.
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-red-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">إصلاح خطأ:</strong> معالجة تسرب الذاكرة في خدمة الترجمة الخلفية.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Version Entry 1.0.0 (Initial Launch) -->
            <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                <div class="order-1 w-5/12 hidden md:block"></div>
                <div class="z-10 flex items-center order-1 bg-gray-500 shadow-xl w-8 h-8 rounded-full ring-4 ring-white">
                    <h1 class="mx-auto font-semibold text-lg text-white">⭐</h1>
                </div>
                <div class="order-1 bg-white rounded-xl shadow-lg w-full md:w-5/12 px-6 py-4 border border-gray-100">
                    <p class="mb-3 text-xs text-gray-500">2025-09-01</p>
                    <h3 class="mb-3 font-bold text-xl text-gray-600">الإصدار 1.0.0 - الإطلاق الأولي</h3>
                    <ul class="list-none space-y-3 text-gray-700 text-sm">
                        <li class="flex items-start">
                            <span class="h-2 w-2 mt-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                            <span class="flex-1">
                                <strong class="font-semibold">ميزة جديدة:</strong> إطلاق منصة CulturalTranslate مع دعم للغات العربية والإنجليزية والفرنسية.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- End of Timeline Marker -->
            <div class="flex justify-center items-center w-full mt-12">
                <div class="z-10 flex items-center bg-gray-300 shadow-xl w-8 h-8 rounded-full ring-4 ring-white">
                    <h1 class="mx-auto font-semibold text-lg text-white">🏁</h1>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Custom scripts for the changelog page can be added here -->
@endpush
