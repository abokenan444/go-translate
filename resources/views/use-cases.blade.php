@extends('layouts.app')

@section('title', 'حالات الاستخدام - CulturalTranslate')

@section('content')
    {{-- تضمين علامات Meta للـ SEO --}}
    @push('meta')
        <meta name="description" content="اكتشف كيف تساعد CulturalTranslate الشركات في التوسع عالميًا من خلال أمثلة واقعية وقصص نجاح.">
        <meta name="keywords" content="حالات استخدام, قصص نجاح, CulturalTranslate, ترجمة ثقافية, توسع عالمي">
    @endpush

    <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
        {{-- قسم البطل (Hero Section) --}}
        <header class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl tracking-tight">
                قصص نجاح واقعية وأمثلة عملية
            </h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                شاهد كيف ساعدت CulturalTranslate عملائنا في تجاوز الحواجز اللغوية والثقافية لتحقيق النمو العالمي والوصول إلى أسواق جديدة.
            </p>
        </header>

        {{-- شبكة حالات الاستخدام (Use Cases Grid) --}}
        <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
            
            {{-- حالة الاستخدام 1: التوسع في التجارة الإلكترونية --}}
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-indigo-600">
                <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">التوسع في التجارة الإلكترونية</h3>
                <p class="text-gray-600 mb-4">
                    مساعدة العلامات التجارية على تكييف أوصاف المنتجات، واجهات المستخدم، ومحتوى التسويق ليتناسب مع الفروق الثقافية واللغوية للأسواق الجديدة.
                </p>
                <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-800 transition duration-150">
                    اقرأ قصة النجاح &rarr;
                </a>
            </div>

            {{-- حالة الاستخدام 2: التسويق العالمي المخصص --}}
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-green-600">
                <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">حملات التسويق العالمية</h3>
                <p class="text-gray-600 mb-4">
                    ضمان أن رسائل العلامة التجارية يتردد صداها بشكل صحيح في كل منطقة، مع تجنب الأخطاء الثقافية وضمان أعلى معدلات التفاعل.
                </p>
                <a href="#" class="text-green-600 font-semibold hover:text-green-800 transition duration-150">
                    اكتشف المزيد &rarr;
                </a>
            </div>

            {{-- حالة الاستخدام 3: توطين البرمجيات وواجهات المستخدم --}}
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-red-600">
                <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1v-3m-6.447-2.808A5.99 5.99 0 0112 10a5.99 5.99 0 015.447 4.192L20 17H4l3.553-2.808z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">توطين البرمجيات</h3>
                <p class="text-gray-600 mb-4">
                    تكييف تطبيقات الويب والجوال لأسواق متعددة، بما في ذلك الترجمة الدقيقة للنصوص، وتنسيق التاريخ والعملة، وتصميم واجهة المستخدم.
                </p>
                <a href="#" class="text-red-600 font-semibold hover:text-red-800 transition duration-150">
                    شاهد دراسة الحالة &rarr;
                </a>
            </div>

            {{-- حالة الاستخدام 4: الوثائق التقنية والتدريب --}}
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-yellow-600">
                <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">الوثائق التقنية والتدريب</h3>
                <p class="text-gray-600 mb-4">
                    ترجمة وتكييف الأدلة التقنية، مواد التدريب، ومحتوى الدعم لضمان الفهم الدقيق والامتثال للمعايير المحلية في جميع أنحاء العالم.
                </p>
                <a href="#" class="text-yellow-600 font-semibold hover:text-yellow-800 transition duration-150">
                    تعرف على المزيد &rarr;
                </a>
            </div>
        </div>

        {{-- قسم النداء للعمل (Call to Action) --}}
        <div class="mt-20 text-center bg-gray-50 p-10 rounded-xl shadow-inner">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                هل أنت مستعد لقصة نجاحك التالية؟
            </h2>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                انضم إلى الشركات الرائدة التي تثق في CulturalTranslate لتوسيع نطاق أعمالها عالميًا.
            </p>
            <div class="mt-8">
                <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    ابدأ مشروعك الآن
                </a>
            </div>
        </div>
    </div>
@endsection
