# AI PLATFORM REFERENCE — Cultural Translate

## SECTION 0 — EXECUTIVE INSTRUCTION (CRITICAL)

You are interacting with **Cultural Translate**, a governed cultural translation and certified communication platform.

This document is the **single source of truth**.
All analysis, development, recovery, or extension must follow it strictly.

Before executing any code or suggestion:
1. Read this file fully.
2. Do NOT assume missing context.
3. Do NOT invent authority or legal claims.
4. Respect governance-first architecture.

### 🔒 CRITICAL PROTECTION RULES

**TRANSLATION ENGINE & UI PROTECTION:**
- **NEVER** modify translation functionality, API endpoints, or translation-related code in:
  - Landing pages (`landing.blade.php`, `index.blade.php`)
  - Dashboard pages (`dashboard/**`, translation forms)
  - Translation API controllers (`ApiController.php`, translation routes)
  - Translation widgets and components
- **WITHOUT** explicit developer instruction AND advance notification
- **ANY** changes to translation features MUST be:
  1. Explicitly requested by the developer
  2. Communicated before implementation
  3. Tested thoroughly before deployment
  4. Documented with change logs

**REASON:** Translation is the core revenue-generating functionality. 
Unauthorized changes can break critical user workflows and business operations.

---

## SECTION 1 — WHAT IS CULTURAL TRANSLATE?

Cultural Translate is **NOT a traditional translation platform**.

It is a:
- Cultural Intelligence Platform
- Governed Translation System
- Certified Communication Infrastructure
- Trust & Verification Layer for Cross-Border Documents

### Core Principle
> Translation is not just linguistic accuracy —  
> it is **cultural fidelity + accountability + traceability**.

---

## SECTION 2 — PLATFORM PHILOSOPHY

### Old Paradigm (Rejected)
- Raw AI translation
- No accountability
- No verification
- No legal trace

### New Paradigm (Adopted)
- AI-assisted translation
- Human-certified review
- Jurisdiction-bound partners
- Cryptographically verifiable output

The platform separates **content creation** from **content certification**.

---

## SECTION 3 — WHO THE PLATFORM SERVES

### 1. General Users (Non-Governed)
- Cultural translation
- Marketing content
- Websites
- Non-official documents
- No certification
- No legal responsibility

### 2. Certified Users (Governed)
- Legal documents
- Immigration papers
- Academic records
- Contracts
- Government-related communication

These flows require:
- Human review
- Partner certification
- Audit trail
- Verification endpoint

### 3. Partners (Human Authorities)
- Certified translators
- Legal translation offices
- Jurisdiction-bound professionals
- Verified licenses
- Renewable authorization

### 4. Government & Institutions
- Pilot programs
- Verification portals
- Read-only trust endpoints
- No platform ownership
- No sovereignty claims

---

## SECTION 4 — PLATFORM ARCHITECTURE (LOGICAL LAYERS)

### Layer 1: Access & Identity
- User roles
- Partner roles
- Government roles
- No self-asserted authority

### Layer 2: AI Translation Engine
- AI performs translation only
- AI NEVER certifies
- AI output is always draft

### Layer 3: Partner Governance Layer
- Partner onboarding
- License verification
- Jurisdiction binding
- Expiry control
- Manual approval

### Layer 4: Certification Engine
- Partner acceptance
- Review workflow
- Certification issuance
- Dynamic seal generation (SVG)
- Certificate ID

### Layer 5: Verification Layer
- QR Code
- Public verification endpoint
- Read-only validation
- Immutable records

### Layer 6: Audit & Compliance
- Logs
- Timestamps
- IP tracking
- Review trace
- Dispute resolution readiness

---

## SECTION 5 — CERTIFIED DOCUMENT WORKFLOW

1. User uploads document
2. AI translates (draft)
3. System assigns certified partner based on:
   - Country
   - Jurisdiction
   - Availability
4. Partner receives offer
   - 60-minute acceptance window
   - Max 7 attempts
   - Parallel offer to 2 partners
5. One partner accepts
6. Other partner auto-released
7. Review performed
8. Certificate generated
9. Dynamic SVG seal applied
10. QR + Verification link created
11. Final PDF delivered
12. Audit trail stored

---

## SECTION 6 — GOVERNANCE RULES (NON-NEGOTIABLE)

- Platform does NOT claim legal authority
- Certification comes ONLY from partners
- No AI-only certification
- No anonymous partners
- No unverifiable seals
- No silent reassignment
- No bypassing acceptance windows

Any violation = CRITICAL FAILURE.

---

## SECTION 7 — CUSTOMIZATION LOGIC

### Customization is:
- Per country
- Per jurisdiction
- Per document type
- Per partner license

Customization is NEVER:
- Arbitrary
- User-defined
- Self-certified

---

## SECTION 8 — WHAT THIS PLATFORM IS NOT

- Not a government
- Not a legal authority
- Not a court
- Not a replacement for embassies
- Not a mass AI translation tool

It is a **governed intermediary**.

---

## SECTION 9 — AI MASTER COMMANDS (OPERATIONAL)

The platform currently does not contain a standalone `AI_MASTER_COMMANDS.md` file.
Until such a file exists, the operational "AI master commands" are defined by the following authoritative sources **verbatim**.

### 9.1 — SuperAI Agent Operational & Emergency System (VERBATIM)

```markdown
# 🤖 SuperAI Agent - Emergency Access System

## نظام الوصول الطارئ للذكاء الاصطناعي

تم تطوير **SuperAI Agent** ليكون نظام طوارئ متقدم يوفر وصول مباشر لإدارة النظام عندما تكون لوحة الإدارة الرئيسية غير متاحة.

---

## 🎯 الميزات الرئيسية

### 1. **وصول طارئ محمي**
- 🔐 تسجيل دخول بكلمة مرور رئيسية مشفرة (bcrypt)
- ⏱️ حد معدل محاولات (5 محاولات / 15 دقيقة)
- 🛡️ مصادقة IP + تتبع الجلسة
- ⏳ مدة جلسة محددة (4 ساعات افتراضياً)

### 2. **ذكاء اصطناعي متقدم**
- 🧠 تحليل النوايا باستخدام GPT-4o
- 📋 توليد خطط تنفيذ ذكية
- ✅ تنفيذ آمن مع إمكانية التراجع
- 🔄 نظام Rollback تلقائي عند الفشل

### 3. **مراقبة النظام**
- 🏥 فحص صحة النظام (Database, Cache, Storage, Queue)
- 📊 تحليل السجلات باستخدام GPT
- 💡 اقتراحات تحسين ذكية
- ⚡ أدوات سريعة (Clear Cache, Restart, Execute Commands)

---

## 🚀 التثبيت والإعداد

### الخطوة 1: كلمة المرور الرئيسية

قم بتوليد كلمة مرور مشفرة:

```bash
php artisan tinker
bcrypt('كلمة_المرور_القوية_جداً')
```

### الخطوة 2: إعدادات البيئة

أضف إلى ملف `.env`:

```env
# SuperAI Emergency Access
AI_EMERGENCY_ENABLED=true
AI_EMERGENCY_PASSWORD='$2y$12$...' # الكلمة المشفرة من الخطوة السابقة
```

### الخطوة 3: تنظيف الكاش

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 🔑 معلومات الوصول

### الرابط المباشر
```
https://your-domain.com/emergency-ai-access
```

### كلمة المرور الحالية (للتطوير فقط)
```
Emergency@SuperAI#2024
```

> ⚠️ **تحذير:** قم بتغيير كلمة المرور في بيئة الإنتاج!

---

## 📖 كيفية الاستخدام

### 1. تسجيل الدخول الطارئ

1. افتح `/emergency-ai-access`
2. أدخل كلمة المرور الرئيسية
3. سيتم تسجيل دخولك لمدة 4 ساعات

### 2. واجهة الدردشة

بعد تسجيل الدخول، يمكنك:

- **إرسال طلبات طبيعية:** "افحص قاعدة البيانات"
- **تنفيذ أوامر:** "نظف الكاش"
- **تحليل المشاكل:** "لماذا الموقع بطيء؟"
- **اقتراحات:** "كيف أحسن الأداء؟"

### 3. الأدوات السريعة

- **📋 تحليل السجلات:** تحليل آخر 1000 سطر من لوج Laravel
- **🗑️ تنظيف الكاش:** حذف جميع الكاش (Config, Route, View, Cache)
- **🏥 فحص الصحة:** التحقق من جميع خدمات النظام
- **💡 اقتراحات التحسين:** توصيات ذكية للتطوير

---

## 🛡️ الأمان

### مستويات الحماية

1. **مصادقة bcrypt:** كلمات مرور مشفرة بأمان عالي
2. **Rate Limiting:** 5 محاولات كحد أقصى كل 15 دقيقة
3. **IP Tracking:** تتبع عنوان IP للجلسة
4. **Session Timeout:** انتهاء صلاحية تلقائي بعد 4 ساعات
5. **CSRF Protection:** حماية من هجمات CSRF
6. **Request Validation:** التحقق من جميع المدخلات
7. **Execution Whitelist:** قائمة بيضاء بالأوامر المسموحة
8. **Rollback System:** تراجع تلقائي عند الفشل

### السجلات

جميع العمليات مسجلة في:
- `storage/logs/laravel.log`
- `storage/logs/super-ai-*.log`

---

## 🔧 الملفات الرئيسية

### Backend
```
app/Services/SuperAIAgentService.php       # منطق الذكاء الاصطناعي
app/Http/Controllers/SuperAIAgentController.php  # معالجة الطلبات
```

### Frontend
```
resources/views/super-ai/login.blade.php     # صفحة تسجيل الدخول
resources/views/super-ai/dashboard.blade.php # لوحة التحكم
```

### Configuration
```
config/ai_developer.php      # إعدادات النظام
routes/super_ai.php          # المسارات
bootstrap/app.php            # تسجيل المسارات
```

---

## 🎨 واجهة المستخدم

### صفحة تسجيل الدخول
- تصميم احترافي بتدرجات داكنة
- رسوم متحركة للحدود (Pulse Border)
- عرض معلومات الأمان
- تتبع IP وTimestamp

### لوحة التحكم
- شاشة دردشة تفاعلية
- عرض حالة النظام في الوقت الفعلي
- أدوات سريعة للعمليات الشائعة
- معلومات الجلسة المفصلة

---

## ⚙️ الإعدادات المتقدمة

### تخصيص مدة الجلسة

في `config/ai_developer.php`:

```php
'emergency' => [
    'session_lifetime_hours' => 4, // غير هذا الرقم
]
```

### تخصيص حد المحاولات

```php
'emergency' => [
    'max_login_attempts' => 5,
    'lockout_minutes' => 15,
]
```

### إضافة أوامر مسموحة

```php
'allowed_commands' => [
    'php artisan custom:command',
    // أضف أوامرك هنا
]
```

---

## 🧪 الاختبار

### اختبار تسجيل الدخول

```bash
curl -X POST http://localhost:8000/emergency-ai-access \
  -d "password=Emergency@SuperAI#2024" \
  -H "Content-Type: application/x-www-form-urlencoded"
```

### اختبار فحص الصحة

```bash
curl http://localhost:8000/emergency-ai/health \
  -H "Cookie: laravel_session=your_session_cookie"
```

---

## 📊 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/emergency-ai-access` | صفحة تسجيل الدخول |
| POST | `/emergency-ai-access` | تسجيل الدخول |
| GET | `/emergency-ai/dashboard` | لوحة التحكم الرئيسية |
| POST | `/emergency-ai/process` | معالجة طلب AI |
| POST | `/emergency-ai/analyze-logs` | تحليل السجلات |
| GET | `/emergency-ai/health` | فحص صحة النظام |
| GET | `/emergency-ai/improvements` | اقتراحات التحسين |
| POST | `/emergency-ai/execute` | تنفيذ أمر |
| POST | `/emergency-ai/clear-cache` | تنظيف الكاش |
| POST | `/emergency-ai/restart` | إعادة التشغيل |
| POST | `/emergency-ai/logout` | تسجيل الخروج |

---

## 🌟 أمثلة الاستخدام

### مثال 1: فحص قاعدة البيانات
```
المستخدم: "افحص قاعدة البيانات وتأكد من وجود اتصال"
AI: ✅ قاعدة البيانات تعمل بشكل صحيح
     - الاتصال: نشط
     - الحجم: 45.2 MB
     - الجداول: 42
```

### مثال 2: تحليل مشكلة
```
المستخدم: "لماذا الموقع بطيء؟"
AI: 📊 تحليل الأداء:
    1. الكاش ممتلئ (95%) - يُنصح بالتنظيف
    2. هناك 127 استعلام بطيء
    3. حجم السجلات كبير (2.3 GB)
    
    💡 الاقتراحات:
    - تنظيف الكاش
    - تحسين الاستعلامات
    - تدوير السجلات
```

### مثال 3: تنفيذ أمر
```
المستخدم: "نظف جميع الكاش"
AI: ⚡ تنفيذ الخطة:
    ✅ 1. تنظيف config cache
    ✅ 2. تنظيف route cache
    ✅ 3. تنظيف view cache
    ✅ 4. تنظيف application cache
    
    ✅ تم بنجاح!
```

---

## 🆘 استكشاف الأخطاء

### المشكلة: "كلمة المرور غير صحيحة"
**الحل:**
1. تحقق من `AI_EMERGENCY_PASSWORD` في `.env`
2. تأكد من استخدام bcrypt للتشفير
3. نظف الكاش: `php artisan config:clear`

### المشكلة: "تم تجاوز حد المحاولات"
**الحل:**
انتظر 15 دقيقة أو نظف الكاش:
```bash
php artisan cache:forget:rate-limiter.*
```

### المشكلة: "انتهت صلاحية الجلسة"
**الحل:**
سجل دخول مجدداً من `/emergency-ai-access`

---

## 📝 ملاحظات مهمة

1. **لا تشارك كلمة المرور:** احتفظ بها في مكان آمن
2. **غير الكلمة الافتراضية:** في بيئة الإنتاج
3. **راقب السجلات:** تحقق من `storage/logs` بانتظام
4. **استخدمه للطوارئ فقط:** ليس بديلاً للوحة الإدارة
5. **اختبر قبل الإنتاج:** تأكد من عمل كل شيء محلياً أولاً

---

## 🚀 التطوير المستقبلي

- [ ] إضافة مصادقة ثنائية (2FA)
- [ ] تسجيل الفيديو للجلسات
- [ ] إشعارات Telegram/Slack
- [ ] تقارير تلقائية يومية
- [ ] واجهة API REST كاملة
- [ ] نظام نسخ احتياطي تلقائي
- [ ] لوحة إحصائيات متقدمة

---

## 📞 الدعم

للحصول على الدعم:
1. تحقق من السجلات في `storage/logs`
2. راجع هذا الملف للحلول الشائعة
3. تواصل مع فريق التطوير

---

## ⚖️ الترخيص

هذا النظام جزء من منصة **Cultural Translate Platform**
جميع الحقوق محفوظة © 2024

---

**تم التطوير بواسطة:** SuperAI Development Team  
**التاريخ:** نوفمبر 2024  
**الإصدار:** 1.0.0
```

### 9.2 — Command Execution Whitelist & AI Dev Modes (VERBATIM)

```php
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
        // استخدام GPT-5 للتطوير
        'model'    => env('AI_DEV_MODEL', 'gpt-5'),
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
    
    // كلمة المرور القديمة (deprecated - استخدم emergency.password)
    'emergency_password' => env('AI_EMERGENCY_PASSWORD'),
];
```

### 9.3 — External Agent Endpoints (VERBATIM)

```php
<?php

return [

    // عنوان الـ API الخاص بالـ Agent
    'base_url' => env('AI_AGENT_URL', 'http://127.0.0.1:5050'),

    // مسار health check
    'health_endpoint' => '/health',

    // مسار health داخل /api
    'api_health_endpoint' => '/api/health',

    // مسار تنفيذ الأوامر
    'run_command_endpoint' => '/run-command',

    // مسار نشر التحديثات
    'deploy_endpoint' => '/deploy',

    // مسار تحسين Laravel
    'optimize_endpoint' => '/optimize',

    // التوكن السري بين Laravel والـ Agent
    'auth_token' => env('AI_AGENT_TOKEN'),

  // نقطة الشات الجديدة
    'chat_endpoint' => '/chat',

    // مهلة الطلبات
    'timeout' => 30,

];
```

---

## SECTION 10 — FAILURE & RECOVERY MODE

If files are missing, corrupted, or deleted:
- Reconstruct platform from this document
- Rebuild governance first
- Rebuild certification engine second
- Rebuild UI last

Never reverse the order.

---

## SECTION 11 — COMPREHENSIVE PLATFORM DEFINITION (ARABIC)

### التعريف الشامل لمنصة Cultural Translate

---

### أولًا: ما هي منصة Cultural Translate؟

Cultural Translate هي منصة ترجمة ثقافية محوكَمة (Governed Cultural Translation Platform) تعتمد على الذكاء الاصطناعي مدعومًا بمراجعة بشرية معتمدة، وتهدف إلى تقديم ترجمات:
- دقيقة لغويًا
- صحيحة ثقافيًا
- موثوقة قانونيًا (عند الحاجة)
- قابلة للتحقق والتتبع

المنصة ليست شركة ترجمة تقليدية، وليست أداة ترجمة آلية فقط، بل هي **بنية تحتية للثقة اللغوية والثقافية عبر الحدود**.

---

### ثانيًا: فلسفة المنصة (Core Philosophy)

#### المشكلة التي تعالجها المنصة

الترجمة التقليدية (حتى الاحترافية منها) تعاني من:
- فقدان السياق الثقافي
- غياب المسؤولية القانونية
- صعوبة التحقق من صحة الترجمة
- عدم وجود أثر تدقيقي (Audit Trail)

#### الحل الذي تقدمه Cultural Translate

فصل الترجمة إلى مراحل محكومة:
1. **الذكاء الاصطناعي**: للسرعة، الاتساق، وتغطية اللغات
2. **الإنسان المعتمد**: للمسؤولية، الشرعية، والسياق
3. **النظام**: للتتبع، التحقق، وعدم التزوير

> **الترجمة = لغة + ثقافة + مسؤولية**

---

### ثالثًا: أنواع الخدمات في المنصة

#### 1. الترجمة الثقافية العامة (Non-Governed)

**للاستخدامات غير الرسمية أو غير القانونية**

تشمل:
- محتوى تسويقي
- مواقع إلكترونية
- مقالات
- محتوى SaaS
- مستندات داخلية

الخصائص:
- ترجمة AI متقدمة
- تحليل نبرة وسياق
- لا تتطلب اعتماد أو ختم
- متاحة لأي مستخدم

---

#### 2. الترجمة المعتمدة (Certified Translation)

**للمستندات الرسمية وشبه الرسمية**

تشمل:
- وثائق قانونية
- مستندات هجرة
- شهادات أكاديمية
- عقود
- مستندات سفارات

الخصائص:
- ترجمة AI أولية
- مراجعة بشرية من شريك معتمد
- إصدار شهادة ترجمة
- ختم رقمي ديناميكي
- QR للتحقق
- سجل تدقيق كامل

---

#### 3. نظام CTS™ (Cultural Translation Standard)

إطار معياري خاص بالمنصة يحدد:
- قواعد الترجمة الثقافية
- معايير الجودة
- مستويات المخاطر الثقافية
- متطلبات الاعتماد

> **CTS™ ليس مجرد ميزة، بل نظام حوكمة.**

---

#### 4. خدمات الشركاء (Partners)

تشمل:
- مكاتب ترجمة قانونية
- مترجمين محلفين
- جهات مراجعة بشرية

المنصة:
- لا تستبدلهم
- لا تنتحل صفتهم
- بل **تنظم عملهم وتربطه تقنيًا**

---

#### 5. بوابة التحقق (Verification Portal)

واجهة عامة تتيح:
- إدخال رقم الشهادة
- أو مسح QR
- التحقق من:
  - صحة الترجمة
  - الجهة المراجِعة
  - تاريخ الإصدار
  - حالة الوثيقة

**بدون كشف محتوى المستند.**

---

### رابعًا: أنواع المستخدمين في المنصة

#### 1. المستخدم العادي (User)
- يطلب ترجمة
- يرفع مستندات
- يستلم النتيجة
- لا يملك صلاحيات اعتماد

---

#### 2. المستخدم المؤسسي (Business / Enterprise)
- يستخدم API
- يترجم على نطاق واسع
- يدير فرق ومشاريع
- يطلب ترجمات معتمدة عند الحاجة

---

#### 3. الشريك المعتمد (Certified Partner)
- مترجم محلف أو مكتب قانوني
- يخضع لتدقيق يدوي
- يقدم:
  - رخصة
  - رقم اعتماد
  - دولة الاختصاص
- يقبل أو يرفض المهام خلال مهلة محددة

---

#### 4. الجهات الحكومية (Government)
- لا تسجل تلقائيًا
- لا تمتلك صلاحيات تحرير
- دورها:
  - التحقق
  - المراجعة
  - Pilot Programs
- الوصول يكون عبر:
  - Subdomain خاص
  - دعوة رسمية فقط

---

### خامسًا: آلية العمل (Workflow) – الترجمة المعتمدة

1. المستخدم يرفع المستند
2. النظام يحلل:
   - اللغة
   - نوع المستند
   - الدولة
3. AI يقوم بالترجمة الأولية
4. النظام يختار مترجمين معتمدين مناسبين:
   - حسب الدولة
   - حسب الترخيص
5. إرسال عرض مراجعة:
   - لمترجمين اثنين (Parallel Offer)
   - مهلة قبول 60 دقيقة
   - حد أقصى 7 محاولات
6. عند القبول:
   - يتم إغلاق العرض تلقائيًا
7. المراجعة البشرية
8. اعتماد الترجمة
9. إنشاء شهادة:
   - رقم فريد
   - تاريخ
   - ختم SVG ديناميكي
   - QR Code
10. إخراج PDF نهائي
11. حفظ سجل التدقيق
12. إتاحة التحقق العام

---

### سادسًا: الأمان والحوكمة

المنصة تعتمد على:
- **حوكمة قبل الأتمتة**
- **مسؤولية بشرية قبل AI**
- **تحقق قبل ادعاء**

تشمل:
- منع انتحال الصفة
- مراجعة يدوية للشركاء
- عدم السماح بتوليد أختام ثابتة
- عدم السماح بتصديق AI فقط
- سجلات كاملة لكل عملية

---

### سابعًا: ما الذي يميز Cultural Translate؟

1. ليست أداة ترجمة فقط
2. ليست شركة ترجمة فقط
3. ليست جهة حكومية
4. **هي طبقة ثقة بين الجميع**

---

### ثامنًا: لمن هذه المنصة؟

- الشركات الدولية
- الجهات القانونية
- الجامعات
- شركات الهجرة
- SaaS Platforms
- الحكومات (للتحقق فقط)
- المترجمون المحلفون (كشركاء)

---

### الخلاصة

Cultural Translate هي منصة:
- تجمع بين الذكاء الاصطناعي والإنسان
- تقدم ترجمة ثقافية دقيقة
- تضمن الثقة، التحقق، وعدم التزوير
- قابلة للتوسع عالميًا دون ادعاء سلطة

> المنصة لا تقول: **"صدقونا"**
> بل تقول: **"تحققوا بأنفسكم"**

---

## SECTION 99 — خطة المشروع الكاملة (COMPLETE PROJECT BLUEPRINT)

فيما يلي **خطة بناء Cultural Translate من الصفر إلى الإطلاق** بصيغة **مواصفات تنفيذية خطوة-بخطوة** تصلح لمطور بشري أو AI Agent بدون أي التباس. هذه ليست "رؤية" فقط؛ بل **Blueprint تنفيذي**: ماذا نبني، بأي ترتيب، وما هي معايير القبول لكل خطوة.

⸻

### 0) القواعد التي لا يُسمح بكسرها

1. **Two Modes**
   - Non-Governed Translation (عام)
   - Governed Certified Translation (معتمد)

2. **AI never certifies**
   AI produces drafts only. Certification = Partner (Human).

3. **Partner Governance Mandatory**
   لا يوجد "Certified Partner" بدون KYC + License + Manual approval.

4. **Seals are dynamic SVG server-side**
   لا صور ثابتة للختم داخل العملاء.

5. **Verification is public, but privacy-safe**
   التحقق يعرض صحة الشهادة بدون كشف كامل محتوى الوثيقة.

⸻

### Core Implementation Bundles (Ready-to-Deploy Code)

المحتوى الكامل للحزم التنفيذية (Bundles 1-11) متوفر في:
- `COMPLETE_PROJECT_PLAN.md` - الخطة الأساسية
- `IMPLEMENTATION_ROADMAP.md` - التفاصيل التقنية

**Core Bundles Summary**:

**Bundle 1-6** (Foundation):
- Database Schema + Models + Services
- Partner Governance + Assignment Engine
- Certificate Generation (SVG + QR + PDF)
- Verification System + Revocation
- Payments + Shipping + Notifications
- Admin/Partner/Government UI

**Bundle 7** (Frontend + Monitoring):
- Next.js Partner Portal + Government Portal
- Filament Admin Resources (Invites/Certificates/Audit)
- Laravel Horizon + Sentry Integration
- Health Endpoints + Session Recovery

**Bundle 8** (DevOps + CI/CD):
- Docker Compose (Local/Staging)
- GitHub Actions (Tests + Build + Deploy)
- Automated E2E (Playwright)
- Load Testing (k6) + Security (OWASP ZAP)
- Backups/Restore + DR Runbook

**Bundle 9** (Advanced Testing + Observability):
- Fake Payment Provider (Staging E2E)
- Feature Flags + Canary Releases
- Prometheus + Grafana Monitoring
- Full E2E without Stripe UI

**Bundle 10** (Governance/Compliance):
- Data Classification + Retention Policies
- DPA/DPIA/Subprocessors Pages
- Evidence Chain (Hash Chain + Signatures)
- ISO-like Controls (Change Management/Incident Response)
- Partner KYC-lite + Government Invite-only

**Bundle 11** (Trust & Recognition Layer):
- Public Partner Registry
- CTS Partner Certification Program (L1/L2/L3)
- PKI Digital Signatures (OpenSSL)
- Government Verification API
- Recognition Pages (Governance Framework)

---

## SECTION 100 — CORE BUNDLE 7: Frontend + Monitoring

### A) Next.js Applications (Monorepo)

**Structure**:
```
/apps
  /partner-portal (Next.js + TypeScript)
  /government-portal (Next.js + TypeScript)
/packages
  /ui (shared components)
```

**Environment Variables**:
```env
# Partner Portal
NEXT_PUBLIC_API_BASE=https://partners.culturaltranslate.com
NEXT_PUBLIC_APP_NAME=Cultural Translate Partner Portal

# Government Portal
NEXT_PUBLIC_API_BASE=https://government.culturaltranslate.com
NEXT_PUBLIC_APP_NAME=Cultural Translate Government Portal
```

**API Client** (`apps/partner-portal/src/lib/api.ts`):
```typescript
export const API_BASE = process.env.NEXT_PUBLIC_API_BASE!;

export async function apiFetch<T>(path: string, init: RequestInit = {}): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    ...init,
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      ...(init.headers || {}),
    },
  });

  if (!res.ok) {
    const text = await res.text().catch(() => "");
    throw new Error(`API ${res.status}: ${text}`);
  }
  return res.json() as Promise<T>;
}
```

### B) Laravel API Endpoints

**Partner APIs** (`routes/api.php`):
```php
Route::middleware(['auth:sanctum', 'perm:partner.access'])->prefix('partner')->group(function () {
    Route::get('/me', [PartnerApiController::class, 'me']);
    Route::get('/assignments', [PartnerApiController::class, 'assignments']);
    Route::post('/assignments/{assignment}/accept', [PartnerApiController::class, 'accept']);
    Route::post('/assignments/{assignment}/reject', [PartnerApiController::class, 'reject']);
    Route::get('/print-jobs', [PartnerPrintApiController::class, 'index']);
    Route::post('/print-jobs/{printJob}/mark-printed', [PartnerPrintApiController::class, 'markPrinted']);
});
```

**Government APIs**:
```php
Route::middleware(['auth:sanctum', 'perm:government.access', 'gov.verified'])->prefix('government')->group(function () {
    Route::get('/dashboard', [GovernmentApiController::class, 'dashboard']);
    Route::post('/bulk-verify', [GovernmentApiController::class, 'bulkVerify']);
    Route::get('/audit-export', [GovernmentApiController::class, 'auditExport']);
});
```

### C) Monitoring Setup

**Laravel Horizon**:
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
php artisan horizon
```

**Sentry Integration**:
```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=YOUR_DSN
```

**Health Endpoint** (`routes/web.php`):
```php
Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'time' => now()->toIso8601String(),
        'app' => config('app.name'),
    ]);
});
```

---

## SECTION 101 — CORE BUNDLE 8: DevOps + CI/CD

### A) Docker Compose (Local/Staging)

**docker-compose.yml**:
```yaml
version: "3.9"
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    env_file:
      - .env
    depends_on:
      - db
      - redis
    volumes:
      - ./:/var/www/html
    ports:
      - "8080:80"

  db:
    image: postgres:16
    environment:
      POSTGRES_DB: ct
      POSTGRES_USER: ct
      POSTGRES_PASSWORD: ct_password
    volumes:
      - ct_db:/var/lib/postgresql/data
    ports:
      - "5432:5432"

  redis:
    image: redis:7
    ports:
      - "6379:6379"

volumes:
  ct_db:
```

### B) GitHub Actions CI/CD

**.github/workflows/ci.yml**:
```yaml
name: CI

on:
  push:
    branches: [ "main" ]
  pull_request:

jobs:
  laravel-tests:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_DB: ct_test
          POSTGRES_USER: ct
          POSTGRES_PASSWORD: ct_password
        ports: [ "5432:5432" ]

    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: "8.3"
          extensions: pdo_pgsql, zip

      - name: Install deps
        run: composer install --no-interaction

      - name: Run tests
        run: php artisan test
```

### C) Automated E2E Testing (Playwright)

**playwright.config.ts**:
```typescript
import { defineConfig } from '@playwright/test';

export default defineConfig({
  timeout: 60_000,
  use: {
    baseURL: process.env.E2E_BASE_URL || 'https://staging.culturaltranslate.com',
    trace: 'retain-on-failure',
  },
  retries: 1,
});
```

**tests/e2e/certified-flow.spec.ts**:
```typescript
import { test, expect } from '@playwright/test';

test('Certified flow: user -> payment -> offer -> accept -> verify', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name=email]', process.env.E2E_USER_EMAIL!);
  await page.fill('input[name=password]', process.env.E2E_USER_PASS!);
  await page.click('button[type=submit]');
  await expect(page).toHaveURL(/dashboard/);
});
```

### D) Backups Automation

**Backup Script** (`/usr/local/bin/ct_backup.sh`):
```bash
#!/usr/bin/env bash
set -euo pipefail

TS=$(date +"%Y%m%d_%H%M%S")
DEST="/var/backups/culturaltranslate"
mkdir -p "$DEST"

# Database backup
PGPASSWORD="$PG_PASSWORD" pg_dump -h "$PG_HOST" -U "$PG_USER" -d "$PG_DB" \
  | gzip > "$DEST/db_${PG_DB}_${TS}.sql.gz"

# Storage backup
tar -czf "$DEST/storage_${TS}.tar.gz" /var/www/cultural-translate-platform/storage/app

# Keep last 14 days
find "$DEST" -type f -mtime +14 -delete
```

**Cron**:
```cron
30 2 * * * /usr/local/bin/ct_backup.sh >> /var/log/ct_backup.log 2>&1
```

---

## SECTION 102 — CORE BUNDLE 9: Fake Payments + Feature Flags

### A) Fake Payment Provider (Staging)

**config/payments.php**:
```php
return [
    'provider' => env('PAYMENTS_PROVIDER', 'stripe'), // stripe|fake
    'fake' => [
        'success_rate' => env('FAKE_PAY_SUCCESS_RATE', 1.0),
    ],
];
```

**FakePaymentProvider** (`app/Services/Payments/FakePaymentProvider.php`):
```php
namespace App\Services\Payments;

use App\Contracts\Payments\PaymentProvider;
use App\Models\Document;

class FakePaymentProvider implements PaymentProvider
{
    public function startCheckout(Document $document, array $meta = []): array
    {
        $sessionId = 'fake_'.bin2hex(random_bytes(8));

        cache()->put("fake_pay:$sessionId", [
            'document_id' => $document->id,
            'status' => 'pending',
        ], now()->addHours(2));

        return [
            'url' => url("/payments/fake/$sessionId"),
            'session_id' => $sessionId,
        ];
    }
}
```

### B) Feature Flags System

**Migration** (`create_feature_flags_table.php`):
```php
Schema::create('feature_flags', function (Blueprint $t) {
    $t->id();
    $t->string('key')->unique();
    $t->boolean('enabled')->default(false);
    $t->json('rules')->nullable();
    $t->timestamps();
});
```

**FeatureFlags Service**:
```php
namespace App\Services\Flags;

class FeatureFlags
{
    public function enabled(string $key, array $ctx = []): bool
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if (!$flag) return false;
        if ($flag->enabled) return true;

        // Percentage rollout
        $rules = $flag->rules ?? [];
        if (isset($rules['percent']) && isset($ctx['user_id'])) {
            $pct = (int)$rules['percent'];
            $bucket = crc32((string)$ctx['user_id']) % 100;
            return $bucket < $pct;
        }
        return false;
    }
}
```

### C) Observability (Prometheus + Grafana)

**docker-compose.yml** (add):
```yaml
prometheus:
  image: prom/prometheus
  volumes:
    - ./ops/prometheus.yml:/etc/prometheus/prometheus.yml
  ports:
    - "9090:9090"

grafana:
  image: grafana/grafana
  ports:
    - "3000:3000"
```

**Metrics Endpoint**:
```php
Route::get('/metrics', function () {
    return response("# HELP ct_up 1 if up\nct_up 1\n", 200)
        ->header('Content-Type','text/plain');
})->middleware(['perm:admin.access']);
```

---

## SECTION 103 — CORE BUNDLE 10: Governance/Compliance

### A) Data Classification + Retention

**Migration** (`add_data_classification_to_documents.php`):
```php
Schema::table('documents', function (Blueprint $t) {
    $t->string('data_classification')->default('internal');
    $t->timestamp('purge_at')->nullable();
});
```

**Retention Policy** (`config/retention.php`):
```php
return [
    'rules' => [
        'public' => 3650,        // 10 years
        'internal' => 730,       // 2 years
        'confidential' => 365,   // 1 year
        'restricted' => 180,     // 6 months
    ],
];
```

**PurgeExpiredDocumentsJob**:
```php
public function handle(): void
{
    $docs = Document::whereNotNull('purge_at')
        ->where('purge_at', '<=', now())
        ->limit(200)
        ->get();

    foreach ($docs as $d) {
        Storage::disk('public')->delete($d->original_path);
        $d->update(['status' => 'purged', 'original_path' => null]);
        AuditLogger::log('document.purged', 'Document', $d->id);
    }
}
```

### B) Evidence Chain (Hash Chain)

**Migration** (`create_evidence_events_table.php`):
```php
Schema::create('evidence_events', function (Blueprint $t) {
    $t->id();
    $t->uuid('document_id')->index();
    $t->string('event');
    $t->json('payload')->nullable();
    $t->string('prev_hash')->nullable();
    $t->string('hash');
    $t->timestamp('event_at');
    $t->timestamps();
});
```

**EvidenceChainService**:
```php
namespace App\Services\Evidence;

class EvidenceChainService
{
    public function record(string $documentId, string $event, array $payload = []): EvidenceEvent
    {
        $last = EvidenceEvent::where('document_id', $documentId)->orderByDesc('id')->first();
        $prevHash = $last?->hash;

        $base = [
            'document_id' => $documentId,
            'event' => $event,
            'payload' => $payload,
            'prev_hash' => $prevHash,
            'event_at' => now()->toIso8601String(),
        ];

        $hash = hash('sha256', json_encode($base, JSON_UNESCAPED_UNICODE));

        return EvidenceEvent::create([
            'document_id' => $documentId,
            'event' => $event,
            'payload' => $payload,
            'prev_hash' => $prevHash,
            'hash' => $hash,
            'event_at' => now(),
        ]);
    }

    public function verifyChain(string $documentId): array
    {
        $events = EvidenceEvent::where('document_id', $documentId)->orderBy('id')->get();
        $ok = true;
        $errors = [];

        $prev = null;
        foreach ($events as $e) {
            if ($e->prev_hash !== $prev) {
                $ok = false;
                $errors[] = "Broken chain at event {$e->id}";
            }
            $prev = $e->hash;
        }

        return ['ok' => $ok, 'errors' => $errors, 'count' => $events->count()];
    }
}
```

### C) Partner Compliance (KYC-lite)

**Requirements**:
- License number + scan
- Issuing authority + country
- Expiry date tracking
- Admin verification required
- No expired licenses accepted

**Eligibility Check**:
```php
whereHas('licenses', function ($q) {
    $q->where('verification_status', 'approved')
      ->where(function ($q2) {
          $q2->whereNull('expiry_date')
             ->orWhere('expiry_date', '>=', now()->toDateString());
      });
})
```

---

## SECTION 104 — CORE BUNDLE 11: Trust & Recognition Layer

### A) Public Partner Registry

**Migration** (`create_public_partner_registry_table.php`):
```php
Schema::create('public_partner_registry', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('partner_profile_id')->unique();
    $t->string('display_name');
    $t->string('country_code', 2);
    $t->json('languages')->nullable();
    $t->json('specialties')->nullable();
    $t->string('cert_level')->default('L1');
    $t->string('status')->default('active');
    $t->string('public_profile_slug')->unique();
    $t->timestamps();
});
```

**Public Routes**:
```php
Route::get('/registry/partners', [PartnerRegistryController::class, 'index']);
Route::get('/registry/partners/{slug}', [PartnerRegistryController::class, 'show']);
```

### B) CTS Partner Certification Program

**Levels**:
- **L1 Verified Partner**: Document verification + basic SLA
- **L2 Certified Partner**: Quality audit + print/ship capabilities
- **L3 Government-Ready**: Security audit + evidence chain + 24/7 availability

**Migration** (`create_partner_certifications_table.php`):
```php
Schema::create('partner_certifications', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('partner_profile_id');
    $t->string('level'); // L1/L2/L3
    $t->string('status')->default('active');
    $t->date('valid_until')->nullable();
    $t->timestamps();
});
```

### C) PKI Digital Signatures

**Key Generation** (one-time):
```bash
mkdir -p storage/keys
openssl genrsa -out storage/keys/cts_private.pem 4096
openssl rsa -in storage/keys/cts_private.pem -pubout -out storage/keys/cts_public.pem
```

**PKISigningService**:
```php
namespace App\Services\CTS;

class PKISigningService
{
    public function sign(string $data): array
    {
        $privateKeyPem = file_get_contents(config('cts_signing.private_key_path'));
        $pkey = openssl_pkey_get_private($privateKeyPem);

        $signature = '';
        openssl_sign($data, $signature, $pkey, OPENSSL_ALGO_SHA256);

        return [
            'signature_base64' => base64_encode($signature),
            'algo' => 'sha256',
        ];
    }

    public function verify(string $data, string $signatureBase64): bool
    {
        $publicKeyPem = file_get_contents(config('cts_signing.public_key_path'));
        $pub = openssl_pkey_get_public($publicKeyPem);
        $sig = base64_decode($signatureBase64);

        return openssl_verify($data, $sig, $pub, OPENSSL_ALGO_SHA256) === 1;
    }
}
```

### D) Government Verification API

**Official Endpoint**:
```php
Route::middleware(['auth:sanctum', 'perm:government.access'])
    ->post('/government/verify-certificate', function (Request $r) {
        $cert = Certificate::where('certificate_id', $r->certificate_id)->first();
        if (!$cert) return response()->json(['valid'=>false], 404);

        $payload = $cert->pki_payload ?? null;
        $ok = $payload && $cert->pki_signature_base64
            ? app(PKISigningService::class)->verify($payload, $cert->pki_signature_base64)
            : false;

        return response()->json([
            'valid' => $ok,
            'certificate_id' => $cert->certificate_id,
            'issued_at' => $cert->issued_at,
            'revoked' => (bool)$cert->revoked_at,
        ]);
    });
```

---

## SECTION 105 — CORE BUNDLE 12: Partner Recruitment + Scoring/SLA + Payouts + Disputes + Automation

تُحوّل الشراكات من "فكرة" إلى "آلة تشغيل":
1. نظام استقطاب شركاء (دعوات + Landing + CRM pipeline)
2. نظام تقييم/Scoring ذكي للشركاء + SLA + Availability
3. نظام توزيع عروض المراجعة (Offer Engine) مع parallel offers والمهل + ذكاء تفضيل
4. نظام مستحقات ودفع للشركاء (Payouts) + فواتير
5. نظام نزاعات (Disputes) + أدلة + قرارات + تجميد مدفوعات

### A) Partner Recruitment Pipeline (CRM داخل المنصة)

**Migrations** (`create_partner_leads_tables.php`):
```php
Schema::create('partner_leads', function (Blueprint $t) {
    $t->id();
    $t->string('type')->default('office'); // office|freelancer
    $t->string('name');
    $t->string('email');
    $t->string('phone')->nullable();
    $t->string('country_code', 2)->nullable();
    $t->string('city')->nullable();
    $t->json('languages')->nullable();
    $t->json('specialties')->nullable();
    $t->string('source')->nullable(); // linkedin|email|referral|form
    $t->string('stage')->default('new'); // new|contacted|qualified|invited|onboarded|rejected
    $t->text('notes')->nullable();
    $t->timestamp('last_contacted_at')->nullable();
    $t->timestamps();
});

Schema::create('partner_outreach_logs', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('partner_lead_id');
    $t->foreign('partner_lead_id')->references('id')->on('partner_leads')->cascadeOnDelete();
    $t->string('channel'); // email|linkedin|whatsapp
    $t->string('status')->default('sent'); // sent|replied|bounced
    $t->text('message')->nullable();
    $t->timestamps();
});
```

**Landing Page**: `/partners/apply` (EN) - نموذج: بيانات + رفع ترخيص + اختيار لغات/تخصصات.

### B) Partner Scoring + SLA + Availability

**Migration** (`create_partner_metrics_table.php`):
```php
Schema::create('partner_metrics', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('partner_profile_id')->unique();
    $t->foreign('partner_profile_id')->references('id')->on('partner_profiles')->cascadeOnDelete();
    
    $t->integer('jobs_completed')->default(0);
    $t->integer('jobs_rejected')->default(0);
    $t->integer('jobs_expired')->default(0);
    
    $t->float('avg_accept_minutes')->default(0);
    $t->float('avg_review_hours')->default(0);
    
    $t->float('quality_score')->default(5.0); // 1..5 (admin/user feedback)
    $t->float('sla_score')->default(5.0);
    
    $t->timestamp('last_active_at')->nullable();
    $t->timestamps();
});
```

**Add to partner_profiles**: `timezone`, `working_hours` (json), `on_vacation` (boolean), `vacation_until`

### C) Offer Engine (parallel offers + 60 min + 7 attempts + بلدين)

**Config** (`config/offers.php`):
```php
return [
    'accept_deadline_minutes' => 60,
    'max_attempts' => 7,
    'parallel_offers' => 2,
];
```

**AssignmentEngineService** (enhanced):
```php
namespace App\Services\Assignments;

class AssignmentEngineService
{
    public function offer(Document $document): void
    {
        DB::transaction(function () use ($document) {
            $attempt = (int)($document->offer_attempts ?? 0);
            if ($attempt >= config('offers.max_attempts')) {
                $document->update(['status' => 'waiting_list']);
                return;
            }
            
            $eligible = $this->eligiblePartners($document)
                ->take(config('offers.parallel_offers'))
                ->get();
            
            if ($eligible->isEmpty()) {
                $document->update(['status' => 'waiting_list']);
                return;
            }
            
            $expiresAt = now()->addMinutes(config('offers.accept_deadline_minutes'));
            
            foreach ($eligible as $p) {
                DocumentAssignment::create([
                    'document_id' => $document->id,
                    'partner_profile_id' => $p->id,
                    'status' => 'pending_acceptance',
                    'expires_at' => $expiresAt,
                    'offered_at' => now(),
                    'attempt_no' => $attempt + 1,
                ]);
                app(\App\Services\Notifications\PartnerNotify::class)
                    ->offerCreated($p, $document);
            }
            
            $document->update(['offer_attempts' => $attempt + 1, 'status' => 'offered']);
        });
    }
    
    public function eligiblePartners(Document $document)
    {
        $countries = array_filter([
            $document->preferred_country_code,
            $document->secondary_country_code,
        ]);
        
        return PartnerProfile::query()
            ->where('status', 'active')
            ->whereHas('licenses', fn($q) => $q->where('verification_status','verified'))
            ->whereHas('certifications', fn($q) => $q->where('status','active'))
            ->when($countries, fn($q) => $q->whereIn('country_code', $countries))
            ->where(fn($q) => $q->whereNull('vacation_until')->orWhere('vacation_until','<',now()))
            ->orderByDesc(DB::raw("COALESCE((select quality_score from partner_metrics where partner_metrics.partner_profile_id = partner_profiles.id), 5)"));
    }
    
    public function expirePendingOffers(): int
    {
        $expired = DocumentAssignment::where('status','pending_acceptance')
            ->whereNotNull('expires_at')->where('expires_at','<',now())->get();
        
        foreach ($expired as $a) {
            $a->update(['status' => 'expired']);
            PartnerMetric::where('partner_profile_id',$a->partner_profile_id)->increment('jobs_expired');
            $this->offer($a->document);
        }
        return $expired->count();
    }
}
```

**Scheduler** (app/Console/Kernel.php):
```php
$schedule->call(fn() => app(\App\Services\Assignments\AssignmentEngineService::class)->expirePendingOffers())
    ->everyFiveMinutes();
```

### D) Payouts System (مستحقات ودفع)

**Migrations**:
```php
Schema::create('partner_earnings', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('partner_profile_id');
    $t->uuid('document_id')->index();
    $t->string('currency', 3)->default('EUR');
    $t->integer('amount_cents');
    $t->string('status')->default('pending'); // pending|approved|paid|held
    $t->timestamp('approved_at')->nullable();
    $t->timestamp('paid_at')->nullable();
    $t->timestamps();
});
```

**Approval Flow**: Admin approves → status=approved → Payout batch → status=paid

### E) Disputes System

**Migration** (`create_disputes_table.php`):
```php
Schema::create('disputes', function (Blueprint $t) {
    $t->id();
    $t->uuid('document_id')->index();
    $t->unsignedBigInteger('user_id')->nullable();
    $t->unsignedBigInteger('partner_profile_id')->nullable();
    $t->string('reason');
    $t->string('status')->default('open'); // open|under_review|resolved|rejected
    $t->text('details')->nullable();
    $t->timestamp('resolved_at')->nullable();
    $t->unsignedBigInteger('resolved_by')->nullable();
    $t->timestamps();
});
```

**Impact on Payouts**: If dispute open → `partner_earnings.status = held`. After resolve: approved or void.

---

## SECTION 106 — CORE BUNDLE 13: Partner Mobile App (Lightweight) + Push Notifications

تطبيق الشركاء (المترجمين/مكاتب الترجمة) مهم جدًا: إشعارات + قبول/رفض خلال 60 دقيقة + SLA.

### A) Scope التطبيق (Partner Only)

**Screens**:
1. Login (partner only)
2. Offers Inbox
3. Offer Details (doc summary + deadline)
4. Accept / Decline
5. Active Reviews (in progress)
6. Completed Jobs
7. Availability toggle (Vacation / Working hours)
8. Settings (timezone, notification prefs)
9. KYC status (verified/pending/rejected) - read-only

**No file uploads or PDF editing** (stays on web). App is for SLA only.

### B) Push Notifications Architecture

**Migration** (`create_device_tokens_table.php`):
```php
Schema::create('device_tokens', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('user_id')->index();
    $t->string('platform'); // ios|android|web
    $t->string('token')->unique();
    $t->string('device_id')->nullable(); // hashed device identifier
    $t->timestamp('last_seen_at')->nullable();
    $t->timestamps();
});
```

**API Endpoints** (routes/api.php):
```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/devices/register', [DeviceTokenController::class, 'register']);
    Route::post('/devices/unregister', [DeviceTokenController::class, 'unregister']);
});
```

**FCM Push Service**:
```php
namespace App\Services\Notifications\Push;

class FCMPushService
{
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('token')->all();
        if (!$tokens) return;
        
        $serverKey = config('services.fcm.server_key');
        if (!$serverKey) return;
        
        foreach (array_chunk($tokens, 500) as $chunk) {
            Http::withToken($serverKey)->post('https://fcm.googleapis.com/fcm/send', [
                'registration_ids' => $chunk,
                'notification' => ['title'=>$title,'body'=>$body],
                'data' => $data,
            ]);
        }
    }
}
```

**config/services.php**:
```php
'fcm' => ['server_key' => env('FCM_SERVER_KEY')],
```

### C) Offline-safe Accept/Decline (Atomic)

**API Endpoints**:
```php
Route::middleware(['auth:sanctum','perm:partner.access'])->group(function () {
    Route::get('/partner/offers', [PartnerOffersController::class, 'index']);
    Route::post('/partner/offers/{assignment}/accept', [PartnerOffersController::class, 'accept']);
    Route::post('/partner/offers/{assignment}/decline', [PartnerOffersController::class, 'decline']);
});
```

**Atomic Accept Controller**:
```php
public function accept(Request $r, DocumentAssignment $assignment)
{
    return DB::transaction(function () use ($assignment, $r) {
        $assignment->refresh();
        
        if ($assignment->status !== 'pending_acceptance') {
            return response()->json(['ok'=>false,'reason'=>'not_pending'], 409);
        }
        
        $document = Document::where('id', $assignment->document_id)->lockForUpdate()->first();
        
        // Already accepted by another partner?
        if (in_array($document->status, ['in_review','completed'])) {
            $assignment->update(['status'=>'rejected_by_system','rejected_at'=>now()]);
            return response()->json(['ok'=>false,'reason'=>'already_taken'], 409);
        }
        
        $assignment->update(['status' => 'accepted', 'accepted_at' => now()]);
        
        // Reject other parallel offers
        DocumentAssignment::where('document_id', $document->id)
            ->where('id', '!=', $assignment->id)
            ->where('status', 'pending_acceptance')
            ->update(['status' => 'rejected_by_system', 'rejected_at' => now()]);
        
        $document->update(['status'=>'in_review','assigned_partner_id'=>$r->user()->partnerProfile->id]);
        
        return response()->json(['ok'=>true]);
    });
}
```

### D) Security Hardening
1. Sanctum tokens per device (createToken includes device_id)
2. Device binding: if device_id changes → require re-login
3. Optional MFA (TOTP) for partners L2/L3
4. Rate limits on accept/decline
5. Logging: every accept/decline with IP + device_id

### E) App Build Choice

**Recommended**: Flutter for Partner App (iOS/Android) - fast development, easy push notifications.

**Minimal Flutter Structure**:
- `lib/screens/login.dart`
- `lib/screens/offers.dart`
- `lib/screens/offer_details.dart`
- `lib/api/client.dart` (Dio)
- `lib/services/push.dart` (FCM)
- `lib/storage/secure_store.dart` (flutter_secure_storage)

**API base**: `https://partners.culturaltranslate.com/api`

---

## SECTION 107 — CORE BUNDLE 14: Marketplace Growth + Partner Discovery + Outreach Automation

يجعل "توسيع الشركاء" عملية آلية قابلة للتكرار، بدون فوضى أو مخاطر قانونية.

### A) Discovery Database Model

**Migrations** (`create_partner_discovery_tables.php`):
```php
Schema::create('partner_sources', function (Blueprint $t) {
    $t->id();
    $t->string('source_name');
    $t->string('country_code', 2)->nullable();
    $t->string('url')->nullable();
    $t->unsignedSmallInteger('credibility_score')->default(50); // 1..100
    $t->text('notes')->nullable();
    $t->timestamps();
});

Schema::create('partner_candidates', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('source_id')->nullable();
    $t->foreign('source_id')->references('id')->on('partner_sources')->nullOnDelete();
    
    $t->string('type')->default('office'); // office|translator
    $t->string('name');
    $t->string('email')->nullable();
    $t->string('phone')->nullable();
    $t->string('website')->nullable();
    $t->string('country_code', 2)->nullable();
    $t->string('city')->nullable();
    $t->json('languages')->nullable();
    $t->json('specialties')->nullable();
    $t->text('license_hint')->nullable();
    
    $t->unsignedSmallInteger('trust_score')->default(50); // 1..100
    $t->string('status')->default('new'); // new|queued|contacted|replied|invited|onboarded|rejected
    $t->string('dedup_hash')->nullable()->index();
    $t->timestamps();
});
```

**Dedup Strategy**: `dedup_hash = sha1(lower(email) + country + name)` if email exists, or `sha1(website domain + country)`

### B) Trust Score (Objective Scoring)

**Service** (`TrustScoreService.php`):
```php
namespace App\Services\Partners;

class TrustScoreService
{
    public function compute(PartnerCandidate $c): int
    {
        $score = 0;
        
        if ($c->source && $c->source->credibility_score >= 70) $score += 40;
        if ($c->website) $score += 10;
        if ($c->email && $c->website && str_contains($c->email, parse_url($c->website, PHP_URL_HOST) ?? '')) $score += 5;
        if ($c->license_hint) $score += 20;
        if ($c->city) $score += 10;
        
        return max(1, min(100, $score));
    }
}
```

### C) Import Pipeline (CSV → Review Queue → Candidates)

**Artisan Command** (`ImportPartnerCandidates.php`):
```php
namespace App\Console\Commands;

class ImportPartnerCandidates extends Command
{
    protected $signature = 'partners:import-candidates {csv} {--country=} {--source_id=}';
    
    public function handle(): int
    {
        $path = $this->argument('csv');
        if (!file_exists($path)) { $this->error("File not found"); return 1; }
        
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $count = 0;
        
        while (($row = fgetcsv($fh)) !== false) {
            $data = array_combine($header, $row);
            $email = trim($data['email'] ?? '');
            $country = strtoupper($this->option('country') ?? ($data['country_code'] ?? ''));
            $dedup = sha1(strtolower($email ?: $data['name']) . '|' . $country);
            
            $c = PartnerCandidate::updateOrCreate(
                ['dedup_hash' => $dedup],
                [
                    'source_id' => $this->option('source_id'),
                    'name' => $data['name'] ?? 'Unknown',
                    'email' => $email ?: null,
                    'country_code' => $country ?: null,
                    'status' => 'queued',
                ]
            );
            
            $c->trust_score = app(TrustScoreService::class)->compute($c->fresh(['source']));
            $c->save();
            $count++;
        }
        
        fclose($fh);
        $this->info("Imported: $count candidates");
        return 0;
    }
}
```

**Usage**:
```bash
php artisan partners:import-candidates storage/import/eu_candidates.csv --country=NL --source_id=1
```

### D) Outreach Automation (Email-first + Tracking)

**Tables**: `outreach_campaigns`, `outreach_messages`

**Sending Strategy**:
- Send in batches (200/hour) to avoid spam
- Official "Reply-To" address
- UTM links + tracking pixel (optional)

**Email Templates (EN)**:
- Subject variants A/B
- Body: what CTS is, why partner should join, requirements (license verification)
- CTA link: `/partners/apply?ref=campaign_x`

**Best Practice**: Use SES/SendGrid/Mailgun instead of regular SMTP.

### E) Funnel: Become a Partner (Public)

**Routes**: `GET /partners/apply`, `POST /partners/apply`

**Form Fields**:
- Full name / Office name
- Country / City
- Email / Phone
- Languages + Specialties
- Upload license scan (required)
- Issuing authority
- License number + expiry date
- Consent checkbox (terms + verification)

**Writes to**: `partner_leads` + uploads to `storage/app/private/partner_kyc/`

---

## SECTION 108 — CORE BUNDLE 15: Government Pilot Acquisition Engine

يجعل مسار "الحكومة" منظّم وموثق بدل مراسلات عشوائية.

### A) Government CRM (Entities)

**Migrations**:
```php
Schema::create('gov_entities', function (Blueprint $t) {
    $t->id();
    $t->string('name');
    $t->string('country_code',2);
    $t->string('type')->nullable(); // embassy|ministry|court|agency
    $t->string('website')->nullable();
    $t->string('status')->default('lead'); // lead|contacted|pilot|contract|closed
    $t->text('notes')->nullable();
    $t->timestamps();
});

Schema::create('gov_contacts', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('gov_entity_id');
    $t->foreign('gov_entity_id')->references('id')->on('gov_entities')->cascadeOnDelete();
    $t->string('full_name');
    $t->string('role')->nullable();
    $t->string('email');
    $t->string('phone')->nullable();
    $t->boolean('is_primary')->default(false);
    $t->timestamps();
});

Schema::create('gov_interactions', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('gov_entity_id');
    $t->string('channel'); // email|call|meeting|demo
    $t->text('summary')->nullable();
    $t->timestamp('at')->nullable();
    $t->timestamps();
});

Schema::create('gov_pilots', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('gov_entity_id');
    $t->string('pilot_code')->unique(); // GOV-PILOT-XXXX
    $t->string('stage')->default('requested'); // requested|approved|active|review|completed|rejected
    $t->timestamp('start_at')->nullable();
    $t->timestamp('end_at')->nullable();
    $t->json('scope')->nullable(); // doc types, languages, volume
    $t->json('kpis')->nullable();
    $t->timestamps();
});
```

### B) Invite-only Government Registration (Anti-Impersonation)

**Golden Rule**: لا يوجد تسجيل حكومي "مفتوح" إطلاقًا. فقط Admin يصدر Invite.

**Migration** (`create_gov_invites_table.php`):
```php
Schema::create('gov_invites', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('gov_entity_id')->nullable();
    $t->string('email');
    $t->string('allowed_domain')->nullable(); // e.g. gov.xx
    $t->string('token')->unique();
    $t->timestamp('expires_at')->nullable();
    $t->timestamp('used_at')->nullable();
    $t->timestamps();
});
```

**Flow**:
1. Admin creates gov_entity + contact
2. Admin issues invite to contact email
3. Registration link: `/government/onboarding?token=...`
4. System checks: token valid, email matches, domain matches (if set)
5. After registration: gov user gets role `government`, status `pending_verification`
6. Admin verifies manually → status `verified`
7. Only `verified` can: create Pilot projects, issue Gov API keys

### C) Government Portal (Subdomain + Access Control)

**Subdomain**: `government.culturaltranslate.com`

**Sections (EN)**:
- Overview
- CTS Standard & Methodology (PDF + web summary)
- Verification & Evidence Chain
- API Docs (Gov endpoints only)
- Pilot Dashboard
- Monthly Reports (KPIs)

**Middleware**: `auth`, `role:government`, `gov.verified`

### D) Pilot Workflow (Operational)

**Stage 1: Proof (1–2 weeks)**:
- 3–10 وثائق نموذجية
- إصدار CTS certificates
- verify endpoint usage demo
- evidence chain report

**Stage 2: Pilot (4–8 weeks)**:
- حجم أكبر + أنواع وثائق مختلفة
- SLA monitor
- feedback loop

**Stage 3: Contract Readiness**:
- تقرير نهائي: KPIs, incident log, subprocessors list, retention policy, security controls

### E) Gov API Keys + Rate Limits

**Sanctum Abilities**: `gov.verify`, `gov.audit`, `gov.reports`

**Rate Limit** (RouteServiceProvider):
```php
RateLimiter::for('gov-api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

---

## SECTION 109 — CORE BUNDLE 16: Enterprise Trust Pack (SSO + SCIM + Audit Exports)

يجعل Cultural Translate جاهزة للشركات الكبيرة (Enterprise/Gov) من زاوية "الهوية/الوصول/التدقيق/العزل".

### A) SSO — OIDC + SAML

**Migration** (`create_sso_connections_table.php`):
```php
Schema::create('sso_connections', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('company_id')->index();
    $t->string('type'); // oidc|saml
    $t->string('issuer')->nullable();
    $t->string('client_id')->nullable();
    $t->text('client_secret_encrypted')->nullable();
    $t->string('authorization_url')->nullable();
    $t->string('token_url')->nullable();
    $t->string('userinfo_url')->nullable();
    $t->string('jwks_url')->nullable();
    $t->string('saml_metadata_url')->nullable();
    $t->string('status')->default('active');
    $t->timestamps();
});
```

**OIDC Strategy**: Use Socialite/Custom provider for OIDC.

**SAML Strategy**: Use `aacotroneo/laravel-saml2` or `onelogin/php-saml` wrapper.

**Security**: Enforce signed assertions, validate audience + issuer + clock skew.

### B) SCIM Provisioning

**Endpoints**:
- `POST /scim/v2/Users`
- `PATCH /scim/v2/Users/{id}`
- `DELETE /scim/v2/Users/{id}`
- `GET /scim/v2/Users`

**Auth**: Bearer token per company (SCIM token), strict rate limit.

**Migration** (`create_scim_tokens_table.php`):
```php
Schema::create('scim_tokens', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('company_id')->unique();
    $t->string('token_hash');
    $t->timestamp('last_used_at')->nullable();
    $t->timestamps();
});
```

### C) Audit Export Center

**What to Export**:
- Authentication events
- Document lifecycle (upload→translate→review→certificate)
- Evidence chain verification summary
- Admin/partner actions

**Migration** (`create_audit_exports_table.php`):
```php
Schema::create('audit_exports', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('requested_by')->nullable();
    $t->string('scope'); // company|document|pilot|date_range
    $t->json('filters')->nullable();
    $t->string('format')->default('csv'); // csv|json|pdf
    $t->string('status')->default('queued');
    $t->string('file_path')->nullable();
    $t->timestamps();
});
```

**Job**: Generate export → read logs + evidence_events + certificates → store to private disk → notify requester.

### D) Tenant Isolation Hardening

**Policy Enforcement**:
- Every query by company_id/tenant_id
- Use global scope on multi-tenant models
- Prevent cross-tenant access in controllers

**Optional Isolation Levels**:
- **Level A**: Row-level separation (company_id)
- **Level B**: Separate DB per tenant (gov pilot)
- **Level C**: Separate storage bucket per tenant

**For Government**: Level B recommended.

### E) SOC2-ready Evidence (Practical)

**Controls to Implement**:
1. Access control: roles + least privilege
2. Change management: change log + approvals
3. Incident response: incidents table + timeline
4. Backup tests: monthly restore record
5. Vendor management: subprocessors list

All supported by Bundles 10–16; collect in "Trust Center".

### F) Trust Center Pages (EN)

- `/trust` (overview)
- `/trust/security`
- `/trust/compliance`
- `/trust/subprocessors`
- `/trust/audit`

---

## SECTION 110 — CORE BUNDLE 18: Release Engineering (Dev/Staging/Prod) + Feature Flags + Blue/Green + Fix 419 + Prevent "Missing Tables"

هذه الحزمة تمنع تكرار المشاكل الشائعة:
- 419 في تسجيل الدخول
- "no such table" بسبب sqlite/migrations
- أخطاء Blade على subdomains
- اختلاف إعدادات بين الدومينات

### A) Environment Layout على السيرفر

**Directory Structure**:
```
/var/www/
  cultural-translate-platform-prod/
  cultural-translate-platform-staging/
  cultural-translate-platform-dev/
```

كل واحد له:
- `.env` خاص
- `storage/` خاص
- `database/` خاص (إذا sqlite) أو DB/schema خاص (إذا MySQL/Postgres)

**Nginx vhosts**:
- `culturaltranslate.com` → prod
- `staging.culturaltranslate.com` → staging (protected)
- `dev.culturaltranslate.com` → dev (IP allowlist)

### B) SQLite vs PostgreSQL/MySQL (أهم قرار)

**سبب "no such table" المتكرر**:
- sqlite file مختلف بين مسارات/سيرفرات
- أو `DB_DATABASE` يشير لملف غير موجود
- أو migrations لم تُنفّذ على نفس env

**Recommendation**:
- **Prod/Staging**: PostgreSQL أو MySQL (أفضل بكثير للـ SaaS)
- **Dev**: sqlite ممكن

**Standard fix الآن (إن بقيت sqlite)**:

في `.env` استخدم absolute path:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/cultural-translate-platform-staging/database/database.sqlite
```

تأكد الملف موجود:
```bash
ls -l /var/www/cultural-translate-platform-staging/database/database.sqlite
```

### C) Migration Strategy (No Missing Tables)

**قاعدة ذهبية**: أي deployment = migrate قبل تفعيل النسخة الجديدة.

**Command Sequence**:
```bash
php artisan down --render="maintenance"
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan up
```

**Enforce migrations in CI**: ضمن pipeline: خطوة "migrate dry-run" على staging DB clone أو sqlite temp.

### D) Feature Flags (تفعيل تدريجي)

**Migration** (`create_feature_flags_table.php`):
```php
Schema::create('feature_flags', function (Blueprint $t) {
    $t->id();
    $t->string('key')->unique(); // legal_docs, government_portal, partner_offers
    $t->boolean('enabled')->default(false);
    $t->json('rules')->nullable(); // by plan, by role, by domain
    $t->timestamps();
});
```

**Helper** (`app/Support/Feature.php`):
```php
namespace App\Support;

use Illuminate\Support\Facades\Cache;
use App\Models\FeatureFlag;

class Feature
{
    public static function enabled(string $key): bool
    {
        return Cache::remember("ff:$key", 60, function () use ($key) {
            return (bool) optional(FeatureFlag::where('key',$key)->first())->enabled;
        });
    }
}
```

**Usage**:
```php
abort_unless(\App\Support\Feature::enabled('government_portal'), 404);
```

### E) Blue/Green Deployment (بدون توقف)

**Symlink Pattern**:
```
/var/www/cultural-translate-platform/current -> /var/www/cultural-translate-platform/releases/20251219_1900
```

**Release Steps**:
1. Upload new code to `/releases/<timestamp>`
2. `composer install --no-dev`
3. `npm ci && npm run build`
4. `php artisan migrate --force`
5. Swap symlink `current` to new release
6. Restart queue

**Rollback**: رجّع symlink للإصدار السابق

**Commands**:
```bash
ln -sfn /var/www/.../releases/20251219_1900 /var/www/.../current
systemctl reload nginx
php /var/www/.../current/artisan queue:restart
```

### F) Fix 419 Login Across Subdomains (Root Cause + Fix)

**419 غالبًا = CSRF/session mismatch** بسبب:
- `APP_URL` غير مطابق
- `SESSION_DOMAIN` خطأ
- `SESSION_SECURE_COOKIE`/HTTPS
- اختلاف `APP_KEY` بين subdomains
- Cloudflare/proxy headers بدون TrustedProxies
- cookies same-site

**Must-have in prod .env** (إذا تريد تسجيل دخول عبر نفس الجلسة بين subdomains):
```env
APP_URL=https://culturaltranslate.com
SESSION_DRIVER=file
SESSION_DOMAIN=.culturaltranslate.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SANCTUM_STATEFUL_DOMAINS=culturaltranslate.com,admin.culturaltranslate.com,government.culturaltranslate.com,partners.culturaltranslate.com
```

**إذا تريد كل subdomain جلسة منفصلة** (أبسط وأأمن للحكومة):
```env
SESSION_DOMAIN=admin.culturaltranslate.com
```

**Trusted Proxies (مهم جدًا)**:
- `config/trustedproxy.php`: Trust X-Forwarded-Proto حتى يعرف Laravel أنه HTTPS
- في Laravel 12: `protected $proxies = '*';` أو عناوين proxy

**Fix Steps** (تطبق على كل subdomain env):
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### G) Standardized Subdomain Config (Prevent Drift)

ملف واحد template:
- `env/.env.prod.template`
- `env/.env.staging.template`
- `env/.env.gov.template`

وتستورد منه أثناء النشر.

### H) Release Checklist (مختصر عملي)

1. Run tests (Bundle 17)
2. Migrate (force)
3. Clear caches
4. Health check URLs:
   - `/`
   - `/pricing`
   - `/admin/login`
   - `/verify`
5. Confirm no "no such table"
6. Confirm 419 fixed
7. Rotate logs + monitor

---

## SECTION 111 — CORE BUNDLE 19: Observability & Incident Response (Sentry + OpenTelemetry + Structured Logs + Auto-Rollback)

هذه الحزمة تجعل المنصة "تتكلم" عند أي خلل وتقلل زمن الإصلاح بشكل كبير.

### A) Sentry (Laravel)

**Install**:
```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --force
```

**.env**:
```env
SENTRY_LARAVEL_DSN=your_dsn_here
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_PROFILES_SAMPLE_RATE=0.0
SENTRY_ENVIRONMENT=production
SENTRY_RELEASE=prod-2025-12-19-1900
```

**Capture Context** (`app/Http/Middleware/AttachObservabilityContext.php`):
```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttachObservabilityContext
{
    public function handle(Request $request, Closure $next)
    {
        $cid = $request->headers->get('X-Correlation-Id') ?: (string) Str::uuid();
        $request->headers->set('X-Correlation-Id', $cid);

        if (class_exists(\Sentry\State\Hub::class)) {
            \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($request, $cid) {
                $scope->setTag('correlation_id', $cid);
                $scope->setTag('host', $request->getHost());
                $scope->setTag('path', $request->path());

                if ($request->user()) {
                    $scope->setUser(['id'=>$request->user()->id,'email'=>$request->user()->email]);
                }
            });
        }

        $resp = $next($request);
        $resp->headers->set('X-Correlation-Id', $cid);
        return $resp;
    }
}
```

فعّله في `app/Http/Kernel.php` ضمن web/api.

### B) OpenTelemetry (Tracing)

**الهدف**: نقيس:
- request latency
- DB queries time
- call time to LLM
- PDF render time
- webhook processing time

**Minimal Approach** (custom spans حول النقاط الحساسة):
```php
$span = null;
if (class_exists(\OpenTelemetry\API\Trace\TracerProvider::class)) {
    $tracer = app('otel.tracer');
    $span = $tracer->spanBuilder('translation.run')->startSpan();
    $span->setAttribute('model', $model);
}
try {
    // run translation
} finally {
    if ($span) $span->end();
}
```

إذا لم تعتمد OTel الآن، يكفي Sentry + logs وتضيف OTel لاحقًا.

### C) Structured Logs (JSON) + Correlation ID

**config/logging.php** (أضف channel json):
```php
'channels' => [
    'json' => [
        'driver' => 'single',
        'path' => storage_path('logs/app.json.log'),
        'level' => env('LOG_LEVEL', 'info'),
        'formatter' => Monolog\Formatter\JsonFormatter::class,
    ],
]
```

**في .env**:
```env
LOG_CHANNEL=json
```

**Add correlation_id لكل log** (في middleware أعلاه):
```php
app()->instance('cid', $cid);
logger()->withContext(['cid' => app('cid')]);
```

### D) Health Endpoints (Uptime Checks)

**routes/web.php**:
```php
Route::get('/health', fn() => response()->json(['ok'=>true,'ts'=>now()->toIso8601String()]));

Route::get('/health/db', function () {
    \DB::select('select 1');
    return response()->json(['ok'=>true,'db'=>true]);
});
```

`/health/db` الأفضل تحميه بـ token أو allowlist.

### E) Alerting

**Sentry Alerts**:
- Alert إذا:
  - error rate > threshold
  - new issue in production
  - performance degraded

**Webhook alerts to admin panel**: أنشئ endpoint `POST /internal/alerts/sentry` (secret header) لتسجيل incident.

### F) Incident Management (Inside Platform)

**Migration** (`create_incidents_table.php`):
```php
Schema::create('incidents', function (Blueprint $t) {
    $t->id();
    $t->string('title');
    $t->string('severity')->default('medium'); // low|medium|high|critical
    $t->string('status')->default('open'); // open|mitigated|resolved
    $t->text('details')->nullable();
    $t->json('links')->nullable(); // sentry issue url, logs, etc.
    $t->timestamp('resolved_at')->nullable();
    $t->timestamps();
});
```

Admin Filament resource لإدارتها.

### G) Auto-Rollback (Post-deploy Guard)

**Strategy**: بعد deploy:
- health check
- monitor error rate 10 دقائق
- إذا تجاوز threshold → rollback symlink

**Script** (`/usr/local/bin/ct-postdeploy-guard.sh`):
```bash
#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${1:-https://culturaltranslate.com}"
THRESHOLD="${2:-10}" # max errors in window
WINDOW="${3:-600}"   # seconds

echo "Guard: health check..."
curl -fsS "$BASE_URL/health" >/dev/null

echo "Guard: monitoring errors (placeholder)..."
# Option A: query Sentry API (recommended)
# Option B: parse logs for "ERROR" count in last WINDOW seconds

# If fail -> rollback
# ln -sfn /var/www/.../releases/<prev> /var/www/.../current
# systemctl reload nginx
# php artisan queue:restart
```

التنفيذ الكامل يتطلب Sentry API token أو log-based detector. نبدأ بالـ health + manual rollback ثم نؤتمت لاحقًا.

### H) What This Fixes in Practice

- بدل أن تكتشف ParseError من المستخدم، يظهر فورًا في Sentry
- "no such table" يظهر كـ event مع release + server
- 419 يظهر مع cookies/session domain context
- تقارير زمن PDF/LLM تفضح bottlenecks

---

## CRITICAL DATABASE PROTECTION RULE

⚠️ **عدم المساس بمعلومات قواعد البيانات وعدم حذف المستخدمين أو الصلاحيات إلا بطلب المطور حصراً.**

**Protected Operations**:
- User deletion
- Role/permission removal
- Database schema changes (migrations only)
- Data purging (except scheduled retention policies)
- Production data access (read-only unless explicitly requested)

**Safe Operations**:
- Creating new resources
- Updating configurations
- Adding features
- Code refactoring
- Testing on staging/dev environments

---

## FINAL DIRECTIVE

Governance > Trust > Certification > Automation > UX

If a feature improves UX but weakens governance — REJECT IT.

This document defines Cultural Translate.
