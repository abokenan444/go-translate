# 🚀 تعليمات النشر على السيرفر

## الطريقة السريعة (نقرة واحدة)

اتصل بالسيرفر ونفذ:

```bash
ssh root@145.14.158.101
cd /var/www/cultural-translate-platform
curl -O https://raw.githubusercontent.com/abokenan444/go-translate/main/deploy-to-production.sh
chmod +x deploy-to-production.sh
./deploy-to-production.sh
```

## أو يدوياً:

### 1. اتصل بالسيرفر
```bash
ssh root@145.14.158.101
cd /var/www/cultural-translate-platform
```

### 2. سحب التحديثات من GitHub
```bash
git pull origin main
```

### 3. تنفيذ سكريبت النشر
```bash
bash deploy-to-production.sh
```

## ما الذي يفعله السكريبت؟

✅ **نسخة احتياطية تلقائية** لجميع الملفات المهمة
✅ **سحب آخر تحديثات** من GitHub
✅ **تحديث Filament Panels** (فصل Admin عن Super Admin)
✅ **تسجيل AdminPanelProvider** في bootstrap/providers.php
✅ **تفعيل الدومين الفرعي** admin.culturaltranslate.com
✅ **تحديث .env** للإنتاج (SESSION_DOMAIN, SANCTUM)
✅ **تنظيف الكاش** وتحسين الأداء
✅ **ضبط الصلاحيات** بشكل صحيح
✅ **إعادة تشغيل الخدمات** (PHP-FPM, Nginx/Apache)

## بعد النشر

### الروابط النشطة:
- 🌐 **الموقع الرئيسي:** https://culturaltranslate.com
- 👤 **لوحة الإدارة:** https://admin.culturaltranslate.com
- 🔧 **لوحة الإدارة (بديل):** https://culturaltranslate.com/admin
- 👑 **السوبر أدمن:** https://culturaltranslate.com/super-admin
- 🤖 **Emergency AI:** https://culturaltranslate.com/emergency-ai-access

### النسخة الاحتياطية:
السكريبت يحفظ نسخة احتياطية تلقائياً في:
```
/var/www/backups/YYYYMMDD_HHMMSS/
```

## استرجاع النسخة الاحتياطية (إذا لزم الأمر)

```bash
# عرض النسخ الاحتياطية
ls -lt /var/www/backups/

# استرجاع نسخة معينة
BACKUP_DATE="20241130_094500"  # غير التاريخ حسب الحاجة
cp -r /var/www/backups/$BACKUP_DATE/* /var/www/cultural-translate-platform/
cd /var/www/cultural-translate-platform
php artisan optimize:clear
systemctl restart php8.3-fpm
```

## التحقق من نجاح النشر

```bash
# 1. التحقق من الروابط
curl -I https://admin.culturaltranslate.com
curl -I https://culturaltranslate.com/admin
curl -I https://culturaltranslate.com/super-admin

# 2. التحقق من المسارات
cd /var/www/cultural-translate-platform
php artisan route:list | grep admin

# 3. التحقق من الخدمات
systemctl status php8.3-fpm
systemctl status nginx
```

## إعدادات DNS (إذا لم تكن مفعلة)

أضف سجل DNS في لوحة إدارة الدومين:
```
Type: A
Host: admin
Value: 145.14.158.101
TTL: 3600
```

## إعداد SSL للدومين الفرعي

```bash
certbot --nginx -d culturaltranslate.com -d admin.culturaltranslate.com
```

## دعم

إذا واجهت أي مشكلة:
1. تحقق من اللوجز: `tail -f /var/www/cultural-translate-platform/storage/logs/laravel.log`
2. تحقق من Nginx: `tail -f /var/log/nginx/error.log`
3. استرجع النسخة الاحتياطية من `/var/www/backups/`
