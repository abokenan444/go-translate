@extends('layouts.app')

@section('title', 'فرص العمل - CulturalTranslate')

@section('meta_tags')
    <meta name="description" content="انضم إلى فريق CulturalTranslate وساهم في سد الفجوات الثقافية واللغوية. اكتشف الوظائف الشاغرة لدينا.">
    <meta name="keywords" content="وظائف, CulturalTranslate, فرص عمل, ترجمة, لغات, ثقافة, تقنية">
    <meta property="og:title" content="فرص العمل - CulturalTranslate">
    <meta property="og:description" content="انضم إلى فريق CulturalTranslate وساهم في سد الفجوات الثقافية واللغوية. اكتشف الوظائف الشاغرة لدينا.">
    <meta property="og:url" content="{{ url('/careers') }}">
@endsection

@section('content')
    <div class="bg-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block xl:inline">انضم إلى فريق</span>
                    <span class="block text-indigo-600 xl:inline">CulturalTranslate</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    نُغير طريقة تواصل العالم. كن جزءًا من مهمتنا لسد الفجوات الثقافية واللغوية.
                </p>
            </div>

            <!-- Our Mission/Culture Section -->
            <div class="bg-gray-50 rounded-lg shadow-xl overflow-hidden mb-16 p-10">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-6 border-b pb-4 border-indigo-100">
                    ثقافتنا وقيمنا
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-4">
                        <div class="text-indigo-600 mb-3">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">التأثير العالمي</h3>
                        <p class="text-gray-600">نعمل على ربط الثقافات والأفراد حول العالم من خلال عملنا.</p>
                    </div>
                    <div class="p-4">
                        <div class="text-indigo-600 mb-3">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">الابتكار المستمر</h3>
                        <p class="text-gray-600">نتبنى أحدث التقنيات والمنهجيات لتقديم أفضل الحلول.</p>
                    </div>
                    <div class="p-4">
                        <div class="text-indigo-600 mb-3">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2a3 3 0 015.356-1.857M7 20h4m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">التعاون والشمول</h3>
                        <p class="text-gray-600">نؤمن بأن أفضل النتائج تأتي من فريق متنوع ومتعاون.</p>
                    </div>
                </div>
            </div>

            <!-- Open Positions Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-8 text-center">
                    الوظائف الشاغرة حاليًا
                </h2>
                <div class="space-y-6">
                    <!-- Job Card 1 -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300 flex justify-between items-center flex-wrap">
                        <div>
                            <h3 class="text-xl font-semibold text-indigo-600">مُطور واجهات أمامية (Front-end Developer)</h3>
                            <p class="text-gray-500 mt-1">الرياض، المملكة العربية السعودية - دوام كامل</p>
                        </div>
                        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            عرض التفاصيل والتقديم
                            <svg class="w-4 h-4 mr-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <!-- Job Card 2 -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300 flex justify-between items-center flex-wrap">
                        <div>
                            <h3 class="text-xl font-semibold text-indigo-600">خبير تسويق رقمي (Digital Marketing Specialist)</h3>
                            <p class="text-gray-500 mt-1">عن بعد - دوام كامل</p>
                        </div>
                        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            عرض التفاصيل والتقديم
                            <svg class="w-4 h-4 mr-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <!-- Job Card 3 -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300 flex justify-between items-center flex-wrap">
                        <div>
                            <h3 class="text-xl font-semibold text-indigo-600">مُترجم لغة عربية (Arabic Translator)</h3>
                            <p class="text-gray-500 mt-1">عن بعد - عقد مستقل</p>
                        </div>
                        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            عرض التفاصيل والتقديم
                            <svg class="w-4 h-4 mr-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <!-- No Openings Placeholder -->
                    {{-- @if (count($openPositions) === 0) --}}
                    <div class="text-center p-10 bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <h3 class="text-2xl font-semibold text-gray-900">لا توجد وظائف شاغرة حاليًا</h3>
                        <p class="mt-2 text-gray-500">نحن نعمل دائمًا على توسيع فريقنا. يُرجى التحقق مرة أخرى لاحقًا أو إرسال سيرتك الذاتية إلينا.</p>
                        <a href="mailto:careers@culturaltranslate.com" class="mt-4 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            أرسل سيرتك الذاتية
                        </a>
                    </div>
                    {{-- @endif --}}
                </div>
            </div>

            <!-- Benefits/CTA Section -->
            <div class="bg-indigo-600 rounded-lg shadow-xl p-10 text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    ماذا نقدم؟
                </h2>
                <p class="mt-4 text-xl text-indigo-200">
                    بيئة عمل محفزة، فرص نمو لا محدودة، ومساهمة حقيقية في مستقبل التواصل العالمي.
                </p>
                <div class="mt-8">
                    <a href="mailto:careers@culturaltranslate.com" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition duration-150">
                        تحدث معنا اليوم
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
