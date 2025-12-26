# 🔄 دليل المزامنة بين الكمبيوتر والسيرفر
## Synchronization Guide - Local ↔️ Server

---

## ✅ الوضع الحالي (Current Status)

### على الكمبيوتر المحلي (Local)
- ✅ تم commit جميع التغييرات
- ✅ تم دمج التحديثات من GitHub
- ✅ تم رفع كل شيء على GitHub
- ✅ Commit الأخير: `Platform verification and migrations completed - 2025-12-26`

### على GitHub
- ✅ محدث بآخر التغييرات
- ✅ Branch: `main`

---

## 📋 خطوات المزامنة مع السيرفر

### الطريقة 1: استخدام GitHub كوسيط (موصى بها)

#### على السيرفر:
```bash
# 1. الاتصال بالسيرفر
ssh root@145.14.158.101

# 2. الانتقال لمجلد المنصة
cd /var/www/cultural-translate-platform

# 3. عمل backup قبل أي شيء
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
tar -czf /root/backups/platform_backup_$TIMESTAMP.tar.gz \
    --exclude=node_modules \
    --exclude=vendor \
    --exclude=storage/logs/* \
    .

# 4. حفظ التغييرات المحلية على السيرفر
git add -A
git stash push -m "Server changes before sync - $TIMESTAMP"

# 5. سحب التحديثات من GitHub
git pull origin main

# 6. استعادة التغييرات المحلية (إن وجدت)
git stash pop

# 7. تحديث الـ dependencies
composer install --no-interaction --optimize-autoloader

# 8. تنظيف الـ cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 9. تشغيل migrations الجديدة (إن وجدت)
php artisan migrate --force

# 10. إعادة تشغيل الخدمات
systemctl restart php8.3-fpm
systemctl restart nginx
```

---

### الطريقة 2: استخدام السكريبت الجاهز

#### 1. رفع السكريبت للسيرفر:
```powershell
# من الكمبيوتر المحلي
scp server-sync.sh root@145.14.158.101:/root/
```

#### 2. تشغيل السكريبت على السيرفر:
```bash
ssh root@145.14.158.101
chmod +x /root/server-sync.sh
/root/server-sync.sh
```

---

### الطريقة 3: المزامنة اليدوية عبر ملفات مضغوطة

#### إذا واجهت مشاكل مع Git:

##### على الكمبيوتر:
```powershell
cd C:\Users\YASSE\Downloads\culturaltranslate-dev

# إنشاء أرشيف للتغييرات فقط
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
git diff --name-only HEAD~1 HEAD | tar -czf "updates_$timestamp.tar.gz" -T -
```

##### رفع للسيرفر:
```powershell
scp "updates_$timestamp.tar.gz" root@145.14.158.101:/tmp/
```

##### على السيرفر:
```bash
cd /var/www/cultural-translate-platform
tar -xzf /tmp/updates_*.tar.gz
```

---

## 🔍 التحقق من نجاح المزامنة

### على السيرفر:
```bash
cd /var/www/cultural-translate-platform

# 1. التحقق من آخر commit
git log --oneline -1

# 2. التحقق من حالة الملفات
git status

# 3. التحقق من قاعدة البيانات
php artisan migrate:status

# 4. اختبار الموقع
curl -I https://culturaltranslate.com
```

---

## ⚠️ ملاحظات مهمة

### قبل المزامنة:
1. ✅ تأكد من عمل backup للسيرفر
2. ✅ تأكد من عدم وجود مستخدمين نشطين
3. ✅ تأكد من وجود مساحة كافية

### بعد المزامنة:
1. ✅ اختبر الموقع الرئيسي
2. ✅ اختبر لوحة التحكم
3. ✅ اختبر API
4. ✅ تحقق من logs للأخطاء

---

## 📊 الملفات المتزامنة

### تم تحديث (Updated):
- ✅ جميع Migrations (150+)
- ✅ Models
- ✅ Controllers
- ✅ Views (Dashboard tabs)
- ✅ Routes
- ✅ Public assets
- ✅ Configuration files

### تم إضافة (Added):
- ✅ PLATFORM_VERIFICATION_REPORT.md
- ✅ Fixed migrations (contacts, call_invitations)
- ✅ api-client.js updates
- ✅ DashboardController updates

---

## 🔐 بيانات الدخول للاختبار

| الدور | البريد | كلمة المرور |
|-------|---------|-------------|
| Admin | admin@culturaltranslate.com | Admin2024! |
| Test | test@example.com | password123 |

---

## 📞 في حالة المشاكل

### خطأ في Git:
```bash
# إعادة تعيين Git
cd /var/www/cultural-translate-platform
git reset --hard origin/main
```

### خطأ في Permissions:
```bash
chown -R www-data:www-data /var/www/cultural-translate-platform
chmod -R 755 /var/www/cultural-translate-platform
chmod -R 775 storage bootstrap/cache
```

### خطأ في Cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

**آخر تحديث:** 2025-12-26
**حالة المزامنة:** ✅ جاهز للتنفيذ
