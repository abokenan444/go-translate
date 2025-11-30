<?php

return [

    'enabled' => env('AI_DEV_ENABLED', true),

    'owner_email' => env('AI_DEV_OWNER_EMAIL', 'admin@example.com'),

    // safe  = تحليل فقط بدون أي مقترحات تنفيذية
    // review = اقتراح تغييرات + تخزينها كـ pending حتى تقوم بالموافقة اليدوية
    // full  = (غير موصى به) محاولة التنفيذ المباشر – لا تُستخدم في الإنتاج
    'mode' => env('AI_DEV_MODE', 'review'),

    // مسار المشروع الجذري الذي يُسمح للـ Agent بالعمل ضمنه
    'project_root' => base_path(),

    // إعدادات OpenAI
    'openai' => [
        'api_base' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'api_key'  => env('OPENAI_API_KEY'),
        // اختر موديل خفيف وسريع للتطوير اليومي
        'model'    => env('AI_DEV_MODEL', 'gpt-4.1-mini'),
    ],

    // الأوامر المسموح بتنفيذها من صفحة الـ Deploy
    'allowed_commands' => [
        'php artisan config:cache',
        'php artisan config:clear',
        'php artisan cache:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan optimize:clear',
        'php artisan migrate',
        'php artisan migrate --force',
        'php artisan queue:restart',
        'php artisan queue:work --once',
        'composer dump-autoload -o',
    ],

    // نظام الطوارئ للـ SuperAI Agent
    'emergency' => [
        // تفعيل نظام الطوارئ
        'enabled' => env('AI_EMERGENCY_ENABLED', true),
        
        // كلمة المرور المشفرة بـ bcrypt
        // يمكن توليد كلمة مرور مشفرة بالأمر: php artisan tinker
        // ثم: bcrypt('كلمة_المرور_القوية_جداً')
        'password' => env('AI_EMERGENCY_PASSWORD'),
        
        // مدة صلاحية الجلسة بالساعات
        'session_lifetime_hours' => 4,
        
        // عدد محاولات تسجيل الدخول المسموحة
        'max_login_attempts' => 5,
        
        // مدة الحظر بالدقائق بعد تجاوز المحاولات
        'lockout_minutes' => 15,
    ],
];
