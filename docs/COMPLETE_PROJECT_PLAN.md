# خطة المشروع الكاملة — Cultural Translate Platform

## Blueprint التنفيذي الشامل من الصفر إلى الإطلاق

هذه الوثيقة تحتوي على **المواصفات التنفيذية الكاملة خطوة-بخطوة** لبناء منصة Cultural Translate.

---

## 0) القواعد التي لا يُسمح بكسرها

1. **Two Modes**
   - Non-Governed Translation (عام)
   - Governed Certified Translation (معتمد)

2. **AI never certifies**
   - AI produces drafts only
   - Certification = Partner (Human)

3. **Partner Governance Mandatory**
   - لا يوجد "Certified Partner" بدون KYC + License + Manual approval

4. **Seals are dynamic SVG server-side**
   - لا صور ثابتة للختم داخل العملاء

5. **Verification is public, but privacy-safe**
   - التحقق يعرض صحة الشهادة بدون كشف كامل محتوى الوثيقة

---

## 1) اختيار التقنية والبنية

### 1.1 Tech Stack (الموصى به)

- **Backend**: Laravel 12
- **DB**: PostgreSQL (Production) + SQLite (Testing)
- **Cache/Queue**: Redis + Horizon (اختياري)
- **Storage**: S3-compatible (أو local في البداية)
- **PDF**: wkhtmltopdf (snappy) أو mPDF (اختر واحد)
- **QR**: endroid/qr-code
- **Frontend Web**: Blade + Tailwind (أبسط وأسرع)
- **E2E**: Playwright
- **Load**: k6

### 1.2 Environments

- local / testing / staging / production

**Subdomains**:
- `app.culturaltranslate.com` (لوحة المستخدم)
- `admin.culturaltranslate.com` (لوحة الإدارة)
- `partners.culturaltranslate.com` (لوحة الشركاء)
- `gov.culturaltranslate.com` (بوابة حكومية Invite-only)
- `verify.culturaltranslate.com` أو `/verify/{id}` (تحقق عام)
- `api.culturaltranslate.com` (API)

---

## 2) إنشاء المشروع (الأساس)

### 2.1 إنشاء Laravel

```bash
composer create-project laravel/laravel cultural-translate
cd cultural-translate
php artisan key:generate
```

### 2.2 إعداد الحزم الأساسية

```bash
composer require laravel/sanctum
composer require spatie/laravel-permission
composer require barryvdh/laravel-dompdf  # أو snappy/mPDF بدل dompdf
composer require endroid/qr-code
composer require ramsey/uuid
```

### 2.3 إعداد Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

---

## 3) تصميم قاعدة البيانات (DB Schema) — الأساس الذي لا يتغير

### 3.1 الجداول الأساسية

#### users
- id, name, email, password, status, locale, created_at

#### roles/permissions (spatie)
- roles, permissions, model_has_roles, role_has_permissions

#### companies (للـ B2B)
- id, user_id(owner), name, country, status

#### partner_profiles
- id, user_id, type (translator/agency), country_code, jurisdiction, status
- phone, address, availability_status

#### partner_licenses
- id, partner_profile_id
- license_number, issuing_authority
- issue_date, expiry_date
- document_path (scan)
- verification_status (pending/approved/rejected)
- verified_by_admin_id, verified_at

#### documents
- id (uuid), user_id, company_id nullable
- type (general/certified)
- country_code, jurisdiction
- source_lang, target_lang
- original_file_path
- status (uploaded/translating/awaiting_review/in_review/certified/failed)
- metadata json

#### translations
- id, document_id
- engine (gpt5/…)
- draft_text longtext
- quality_score
- cultural_analysis json
- status

#### document_assignments
- id, document_id, partner_profile_id
- offered_at, expires_at, accepted_at
- status (pending_acceptance/accepted/lost/rejected/expired)

#### reviews
- id, document_id, partner_profile_id
- decision (approved/revision_requested/rejected)
- notes text, decided_at, audit_hash

#### certificates
- id, document_id
- certificate_id (CT-CT-YYYY-######)
- issued_at, issuer_partner_id
- pdf_path
- verify_token (uuid/secure)
- public_status (valid/revoked)
- revocation_reason nullable

#### seals
- id, certificate_id
- seal_svg_template_path
- seal_payload json (certificate_id, date, qr_url, partner_ref, …)

#### audit_logs
- id, actor_type(user/partner/admin/system), actor_id
- action, entity_type, entity_id
- ip, user_agent, payload json
- created_at

#### pricing_plans + subscriptions (إذا عندك اشتراك)
- pricing_plans (active, sort_order, features json)
- user_subscriptions (status, stripe ids, expires_at)

### 3.2 Migrations

اكتب migrations لكل ما سبق، ثم:

```bash
php artisan migrate
```

**معيار قبول:** لا يوجد "no such table" في أي صفحة.

---

## 4) نظام الصلاحيات والأدوار

### 4.1 Roles
- user
- partner
- admin
- government (read/verify only)

### 4.2 Permissions (أمثلة)
- documents.upload
- documents.translate
- documents.request_certified
- partner.accept_assignment
- partner.review_document
- admin.verify_partner
- admin.manage_plans
- government.verify_certificate

**معيار قبول:** أي route محمي بـ middleware مناسب.

---

## 5) Authentication + Subdomain Routing

### 5.1 Guards
- web للمستخدمين
- partner للشركاء (يمكن نفس users table مع role partner)
- admin للإدارة

الأبسط: **users table واحدة** + roles.

### 5.2 Subdomain routes

Laravel route groups حسب domain:
- `Route::domain('admin.culturaltranslate.com')...`
- `Route::domain('partners.culturaltranslate.com')...`
- إلخ

**معيار قبول:** كل لوحة تفتح وتعمل بدون 419.

---

## 6) حل مشكلة 419 (CSRF / Session) من البداية

- SESSION_DOMAIN مضبوط: `.culturaltranslate.com` (للسماح عبر subdomains)
- SESSION_SECURE_COOKIE=true (في https)
- SameSite = Lax أو None حسب الحالة

في .env:

```env
SESSION_DRIVER=file
SESSION_DOMAIN=.culturaltranslate.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

**معيار قبول:** تسجيل الدخول يعمل في كل subdomain بدون 419.

---

## 7) Translation Engine (AI Draft Only)

### 7.1 واجهة خدمة

أنشئ:
- TranslationServiceInterface
- Gpt5TranslationService
- FakeTranslationService (testing)

### 7.2 تدفق ترجمة

- **Input**: text/document extracted text
- **Output**:
  - draft translation
  - cultural analysis
  - score

**معيار قبول:** أي ترجمة تنتج "draft" فقط (status=draft).

---

## 8) Document Processing (PDF → Text)

اختياران:
1. Simple: ترجمة "metadata + extracted text" فقط
2. Advanced: الحفاظ على تنسيق PDF (مطلوب عندك)

للمرحلة الأولى:
- استخراج النص (حسب مكتبة)
- حفظه في translations.draft_text

**معيار قبول:** يمكن رفع PDF واستلام ترجمة draft.

---

## 9) Partner Governance (Onboarding + Verification)

### 9.1 Partner Registration
- نموذج تسجيل Partner (role=partner)
- إدخال بيانات + رفع رخصة
- حالة partner: `pending_verification`

### 9.2 Admin Verification

Admin panel:
- Review license scan
- Approve/Reject
- Set jurisdiction/country
- Activate partner

**معيار قبول:** لا يمكن للشريك قبول مهام قبل approval.

---

## 10) Smart Assignment Engine (المنطق الحاكم)

### قواعد ثابتة
- acceptance window = 60 minutes
- parallel offer = 2
- max attempts = 7

### خطوات التنفيذ

1. عند طلب Certified:
   - system selects top 2 partners matching: country_code, jurisdiction, language pair, availability

2. Create 2 records in document_assignments
   - status = pending_acceptance
   - expires_at = now + 60min

3. Notifications (email/SMS/push لاحقًا)
   - send to both

4. Accept logic:
   - first accept wins
   - second becomes lost + notified

5. Expire job:
   - cron كل 5 دقائق
   - إذا expired ولم يقبل: mark expired, attempt++, reassign حتى 7

**معيار قبول:** السيناريوهات الأربعة تعمل: (accept/dual accept/reject/timeout).

---

## 11) Human Review Workflow

Partner panel:
- Inbox: assignments
- view document + draft
- actions:
  - Approve
  - Request revision
  - Reject

عند Approve:
- create review record
- move document status → certified_pending_output

**معيار قبول:** لا توجد طريقة لإصدار certificate بدون review approved.

---

## 12) Certificate & Seal Engine (Dynamic SVG + QR + PDF)

### 12.1 Certificate ID Generator

نمط: `CT-CT-YYYY-00000001`

### 12.2 Seal SVG Template

SVG يحتوي placeholders:
- {{CERT_ID}}
- {{DATE}}
- {{QR_URL}}
- {{PARTNER_REF}}

يُولّد SVG نهائي runtime ثم يُدمج في PDF.

### 12.3 PDF Generator

HTML template للشهادة + Embed:
- original document preview (اختياري)
- translated content
- dynamic seal SVG
- QR

**معيار قبول:** PDF الناتج يحتوي ختم دائري صحيح الحجم، وQR يعمل، والتاريخ/المعرف ديناميكيان.

---

## 13) Verification System (Public)

Route: `/verify/{certificate_id}` أو token

يعرض:
- Valid/Revoked
- Issue date
- Partner (اسم + بلد + authority)
- hash/fingerprint للملف (اختياري)
- بدون عرض كامل محتوى الوثيقة

**معيار قبول:** التحقق يعمل بدون تسجيل دخول.

---

## 14) Subscription & Payments (إذا مطلوبة)

- pricing_plans + admin CRUD
- Stripe checkout test mode
- Webhooks:
  - subscription.created
  - subscription.updated
  - payment_failed

**معيار قبول:** upgrade يعمل وuser_subscriptions تتحدث.

---

## 15) Admin Panel (Filament أو custom)

يجب أن يحتوي:
- Partner verification queue
- Documents overview
- Certificates overview
- Revocation tool
- Plans management
- Audit logs browser

**معيار قبول:** admin لا يواجه missing tables.

---

## 16) Security Layer (Minimum Production)

- Rate limiting (per route groups)
- Input sanitization
- Logging
- reCAPTCHA (production only)
- HSTS/HTTPS
- File upload validation

**معيار قبول:** لا يمكن إغراق endpoints الحساسة بسهولة.

---

## 17) Testing Suite (Mandatory قبل الإطلاق)

### 17.1 Feature Tests (Laravel)
- register/login لكل دور
- upload/translate
- request certified
- assignment parallel accept logic
- approve → generate certificate
- verify endpoint

### 17.2 Integration Tests
- PDF includes seal markers
- QR URL exists
- certificate_id unique

### 17.3 E2E (Playwright)
- full user flow
- partner accept/review
- verify

**معيار قبول:** كل الاختبارات تمر في CI.

---

## 18) Deployment (Staging ثم Production)

### 18.1 Staging
- domain routing test
- sessions test (no 419)
- file storage test
- queue/cron working

### 18.2 Production
- database postgres
- backups
- monitoring
- logs rotation
- cron jobs

**Cron**:
- expire assignments
- cleanup sandbox instances
- generate reports

---

## 19) إطلاق تدريجي (النهج الصحيح لمنصتك)

1. Public launch للـ Non-Governed
2. Partner onboarding (limited)
3. Certified workflow pilot
4. Government pilot (invite only)
5. Scale

---

## 20) تعريف "نهاية المشروع" (Definition of Done)

المنصة تعتبر مكتملة عندما:

- ✅ Non-Governed translation يعمل
- ✅ Certified workflow يعمل end-to-end
- ✅ Partner governance enforced
- ✅ No static seals
- ✅ Verification page تعمل
- ✅ Subdomains تعمل بلا 419
- ✅ Admin يمكنه التحقق/الإلغاء/review logs
- ✅ Tests pass (feature + e2e)
- ✅ Deployment stable

---

## المرجع الكامل لأوامر التنفيذ

راجع الملف المرفق للحصول على:
- أوامر Terminal كاملة (Ubuntu + Nginx + Laravel 12)
- Core Bundles (1-6) مع الأكواد الجاهزة
- Migration files
- Models
- Services
- Controllers
- Jobs & Notifications
- UI Components

---

**هذا Blueprint تنفيذي حي يتم تحديثه مع تطور المنصة.**
