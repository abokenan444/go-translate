# Script to upload fixed files to server
# التعديلات التي تم إجراؤها:
# 1. إصلاح أخطاء Enterprise Controller (auth()->id() -> auth()->user()?->id)
# 2. جعل أرقام العملاء في About ديناميكية
# 3. إصلاح روابط Blog من # إلى route('blog')
# 4. توحيد سنة حقوق النشر إلى 2025
# 5. إضافة رقم التسجيل NL KvK 83656480

$server = "root@culturaltranslate.com"
$remotePath = "/var/www/html"

Write-Host "=== رفع الإصلاحات إلى السيرفر ===" -ForegroundColor Green

# Controllers
Write-Host "`nرفع Controllers..." -ForegroundColor Yellow
scp "go-translate-main\app\Http\Controllers\EnterpriseSubscriptionController.php" "${server}:${remotePath}/app/Http/Controllers/"
scp "go-translate-main\app\Http\Controllers\ComplaintController.php" "${server}:${remotePath}/app/Http/Controllers/"

# Views - Components
Write-Host "`nرفع Components..." -ForegroundColor Yellow
scp "resources\views\components\footer.blade.php" "${server}:${remotePath}/resources/views/components/"

# Views - Pages
Write-Host "`nرفع Pages..." -ForegroundColor Yellow
scp "resources\views\pages\about.blade.php" "${server}:${remotePath}/resources/views/pages/"
scp "resources\views\pages\blog.blade.php" "${server}:${remotePath}/resources/views/pages/"
scp "resources\views\pages\contact.blade.php" "${server}:${remotePath}/resources/views/pages/"

# Views - Root
Write-Host "`nرفع Root Views..." -ForegroundColor Yellow
scp "resources\views\landing_premium.blade.php" "${server}:${remotePath}/resources/views/"
scp "resources\views\pricing-new.blade.php" "${server}:${remotePath}/resources/views/"

# Views - go-translate-main subfolder
Write-Host "`nرفع Duplicate Views..." -ForegroundColor Yellow
scp "go-translate-main\resources\views\complaints.blade.php" "${server}:${remotePath}/resources/views/"
scp "go-translate-main\resources\views\landing_premium.blade.php" "${server}:${remotePath}/resources/views/"
scp "go-translate-main\resources\views\pricing-new.blade.php" "${server}:${remotePath}/resources/views/"
scp "go-translate-main\resources\views\pages\contact.blade.php" "${server}:${remotePath}/resources/views/pages/"

Write-Host "`n=== تم رفع جميع الملفات بنجاح! ===" -ForegroundColor Green
Write-Host "`nقم بتشغيل الأوامر التالية على السيرفر:" -ForegroundColor Cyan
Write-Host "ssh ${server}" -ForegroundColor White
Write-Host "cd ${remotePath}" -ForegroundColor White
Write-Host "php artisan cache:clear" -ForegroundColor White
Write-Host "php artisan view:clear" -ForegroundColor White
Write-Host "php artisan config:clear" -ForegroundColor White
