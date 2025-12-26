@extends('layouts.app')

@section('title', 'سياسة الخصوصية - CulturalTranslate')
@section('description', 'اطلع على سياسة الخصوصية الشاملة لمنصة CulturalTranslate، والتي توضح كيفية جمعنا واستخدامنا وحماية معلوماتك الشخصية.')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-5xl" dir="rtl">
    <header class="text-center mb-12">
        <h1 class="text-5xl font-extrabold text-gray-900 mb-4">سياسة الخصوصية</h1>
        <p class="text-xl text-indigo-600 font-semibold">CulturalTranslate</p>
        <p class="text-sm text-gray-500 mt-4">**تاريخ السريان:** 10 ديسمبر 2025</p>
    </header>

    <div class="space-y-12 text-gray-700 leading-relaxed text-lg">
        <!-- Introduction -->
        <section>
            <p class="bg-indigo-50 p-4 rounded-lg border-r-4 border-indigo-500 shadow-sm">
                مرحبًا بك في CulturalTranslate. نحن نولي أهمية قصوى لخصوصية مستخدمينا ونلتزم بحماية المعلومات الشخصية التي تشاركها معنا. توضح سياسة الخصوصية هذه كيفية جمعنا واستخدامنا والكشف عن معلوماتك عند استخدامك لمنصتنا وخدماتنا.
            </p>
        </section>

        <!-- 1. المعلومات التي نجمعها -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">1. المعلومات التي نجمعها</h2>
            <p class="mb-6">نجمع أنواعًا مختلفة من المعلومات لأغراض متنوعة لتحسين وتوفير خدماتنا لك.</p>

            <!-- 1.1. البيانات الشخصية -->
            <h3 class="text-2xl font-semibold text-gray-800 mb-3">1.1. البيانات الشخصية</h3>
            <p class="mb-4">أثناء استخدامك لخدمتنا، قد نطلب منك تزويدنا ببعض المعلومات الشخصية التي يمكن استخدامها للاتصال بك أو تحديد هويتك ("البيانات الشخصية"). قد تشمل هذه البيانات، على سبيل المثال لا الحصر:</p>
            <ul class="list-disc list-inside space-y-2 pr-6">
                <li>الاسم وعنوان البريد الإلكتروني.</li>
                <li>بيانات ملف التعريف (مثل اسم المستخدم، صورة الملف الشخصي).</li>
                <li>بيانات الدفع (في حال استخدام الخدمات المدفوعة).</li>
                <li>أي محتوى ترسله إلينا (مثل النصوص المترجمة، الملاحظات، الاستفسارات).</li>
            </ul>

            <!-- 1.2. بيانات الاستخدام -->
            <h3 class="text-2xl font-semibold text-gray-800 mt-6 mb-3">1.2. بيانات الاستخدام</h3>
            <p class="mb-4">نقوم أيضًا بجمع معلومات حول كيفية الوصول إلى الخدمة واستخدامها ("بيانات الاستخدام"). قد تتضمن بيانات الاستخدام هذه معلومات مثل:</p>
            <ul class="list-disc list-inside space-y-2 pr-6">
                <li>عنوان بروتوكول الإنترنت لجهازك (مثل عنوان IP).</li>
                <li>نوع المتصفح وإصداره.</li>
                <li>صفحات خدمتنا التي تزورها.</li>
                <li>وقت وتاريخ زيارتك والوقت الذي تقضيه في تلك الصفحات.</li>
                <li>معرفات الجهاز الفريدة والبيانات التشخيصية الأخرى.</li>
            </ul>
        </section>

        <!-- 2. كيفية استخدام معلوماتك -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">2. كيفية استخدام معلوماتك</h2>
            <p class="mb-4">تستخدم CulturalTranslate البيانات المجمعة لأغراض مختلفة، تشمل:</p>
            <ul class="list-disc list-inside space-y-2 pr-6">
                <li>توفير وصيانة خدمتنا.</li>
                <li>إخطارك بالتغييرات التي تطرأ على خدمتنا.</li>
                <li>السماح لك بالمشاركة في الميزات التفاعلية لخدمتنا.</li>
                <li>تقديم دعم العملاء.</li>
                <li>تحليل أو جمع معلومات قيمة لتحسين خدمتنا.</li>
                <li>مراقبة استخدام خدمتنا واكتشاف ومنع ومعالجة المشكلات التقنية.</li>
                <li>تزويدك بالأخبار والعروض الخاصة والمعلومات العامة حول السلع والخدمات، ما لم تختر عدم تلقي هذه المعلومات.</li>
            </ul>
        </section>

        <!-- 3. الكشف عن بياناتك -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">3. الكشف عن بياناتك</h2>
            <p class="mb-4">قد نشارك معلوماتك الشخصية في الحالات التالية:</p>
            <ul class="list-disc list-inside space-y-4 pr-6">
                <li>
                    <strong class="text-indigo-600">مع مزودي الخدمة:</strong> قد نستخدم شركات وأفراد من جهات خارجية لتسهيل خدمتنا ("مزودي الخدمة")، لتقديم الخدمة نيابة عنا، أو لأداء خدمات متعلقة بالخدمة، أو لمساعدتنا في تحليل كيفية استخدام خدمتنا.
                </li>
                <li>
                    <strong class="text-indigo-600">للامتثال للقانون:</strong> إذا كنا نعتقد بحسن نية أن هذا الإجراء ضروري من أجل الامتثال لالتزام قانوني، أو حماية والدفاع عن حقوق أو ممتلكات CulturalTranslate، أو منع أو التحقيق في المخالفات المحتملة، أو حماية السلامة الشخصية للمستخدمين، أو الحماية من المسؤولية القانونية.
                </li>
                <li>
                    <strong class="text-indigo-600">لأغراض العمل:</strong> قد نشارك أو ننقل معلوماتك الشخصية فيما يتعلق بأي اندماج أو بيع لأصول الشركة أو تمويل أو استحواذ على كل أو جزء من أعمالنا لشركة أخرى.
                </li>
            </ul>
        </section>

        <!-- 4. أمن البيانات -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">4. أمن البيانات</h2>
            <p>يعد أمن بياناتك أمرًا مهمًا بالنسبة لنا، ولكن تذكر أنه لا توجد طريقة إرسال عبر الإنترنت أو طريقة تخزين إلكتروني آمنة بنسبة 100%. بينما نسعى جاهدين لاستخدام وسائل مقبولة تجاريًا لحماية بياناتك الشخصية، لا يمكننا ضمان أمنها المطلق.</p>
        </section>

        <!-- 5. حقوقك في حماية البيانات -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">5. حقوقك في حماية البيانات</h2>
            <p class="mb-4">في ظل ظروف معينة، لديك حقوق حماية البيانات التالية:</p>
            <ul class="list-disc list-inside space-y-2 pr-6">
                <li><strong class="text-indigo-600">الحق في الوصول:</strong> الحق في الوصول إلى المعلومات التي لدينا عنك وتلقي نسخة منها.</li>
                <li><strong class="text-indigo-600">الحق في التصحيح:</strong> الحق في تصحيح أي معلومات غير دقيقة أو غير كاملة عنك.</li>
                <li><strong class="text-indigo-600">الحق في الاعتراض:</strong> الحق في الاعتراض على معالجتنا لبياناتك الشخصية.</li>
                <li><strong class="text-indigo-600">الحق في التقييد:</strong> الحق في طلب تقييد معالجة بياناتك الشخصية.</li>
                <li><strong class="text-indigo-600">الحق في النقل:</strong> الحق في الحصول على نسخة من معلوماتك بتنسيق منظم وشائع الاستخدام وقابل للقراءة آليًا.</li>
                <li><strong class="text-indigo-600">الحق في السحب:</strong> الحق في سحب موافقتك في أي وقت إذا كانت CulturalTranslate تعتمد على موافقتك لمعالجة معلوماتك الشخصية.</li>
            </ul>
        </section>

        <!-- 6. روابط لمواقع أخرى -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">6. روابط لمواقع أخرى</h2>
            <p>قد تحتوي خدمتنا على روابط لمواقع أخرى لا يتم تشغيلها بواسطتنا. إذا نقرت على رابط جهة خارجية، فسيتم توجيهك إلى موقع تلك الجهة الخارجية. ننصحك بشدة بمراجعة سياسة الخصوصية لكل موقع تزوره.</p>
        </section>

        <!-- 7. التغييرات على سياسة الخصوصية هذه -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">7. التغييرات على سياسة الخصوصية هذه</h2>
            <p>قد نقوم بتحديث سياسة الخصوصية الخاصة بنا من وقت لآخر. سنخطرك بأي تغييرات عن طريق نشر سياسة الخصوصية الجديدة على هذه الصفحة وتحديث "تاريخ السريان" في الأعلى.</p>
        </section>

        <!-- 8. اتصل بنا -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200">8. اتصل بنا</h2>
            <p class="mb-4">إذا كانت لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا:</p>
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
                <p class="mb-2"><strong class="text-indigo-600">عبر البريد الإلكتروني:</strong> <a href="mailto:privacy@culturaltranslate.com" class="text-blue-600 hover:text-blue-800 underline">privacy@culturaltranslate.com</a></p>
                <p><strong class="text-indigo-600">عبر نموذج الاتصال:</strong> المتوفر على موقعنا.</p>
            </div>
        </section>
    </div>
</div>
@endsection
