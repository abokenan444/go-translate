# Deploy Enhancements to Production Server
# Server: 145.14.158.101

$SERVER = "root@145.14.158.101"
$REMOTE_PATH = "/var/www/cultural-translate-platform"
$BACKUP_DIR = "/var/www/backups/$(Get-Date -Format 'yyyy-MM-dd_HH-mm-ss')"

Write-Host "================================" -ForegroundColor Cyan
Write-Host "Deploying Platform Enhancements" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Create backup on server
Write-Host "[1/8] Creating backup on server..." -ForegroundColor Yellow
ssh $SERVER @"
mkdir -p $BACKUP_DIR
cd $REMOTE_PATH
tar -czf $BACKUP_DIR/database_backup.tar.gz database/
tar -czf $BACKUP_DIR/app_backup.tar.gz app/
tar -czf $BACKUP_DIR/config_backup.tar.gz config/
echo 'Backup created at: $BACKUP_DIR'
"@

if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Backup failed!" -ForegroundColor Red
    exit 1
}
Write-Host "[OK] Backup created successfully" -ForegroundColor Green
Write-Host ""

# Step 2: Upload new files
Write-Host "[2/8] Uploading new files to server..." -ForegroundColor Yellow

# Upload Services
scp -r app/Services/TranslatorPerformanceService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp -r app/Services/PayoutService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp -r app/Services/DisputeService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp -r app/Services/PKISigningService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp -r app/Services/RealTimeMonitoringService.php "${SERVER}:${REMOTE_PATH}/app/Services/"

# Upload Controllers
scp -r app/Http/Controllers/Api/GovernmentVerificationController.php "${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Api/"
scp -r app/Http/Controllers/Api/PartnerRegistryController.php "${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Api/"

# Upload Models
scp -r app/Models/PartnerPayout.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/DocumentDispute.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/DocumentClassification.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/EvidenceChain.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/GovernmentVerification.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/TranslatorPerformance.php "${SERVER}:${REMOTE_PATH}/app/Models/"
scp -r app/Models/DeviceToken.php "${SERVER}:${REMOTE_PATH}/app/Models/"

# Upload Middleware
scp -r app/Http/Middleware/GovernmentApiRateLimiter.php "${SERVER}:${REMOTE_PATH}/app/Http/Middleware/"
scp -r app/Http/Middleware/TrackRequestMetrics.php "${SERVER}:${REMOTE_PATH}/app/Http/Middleware/"

# Upload Commands
scp -r app/Console/Commands/UpdateAllTranslatorPerformance.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp -r app/Console/Commands/ProcessPayouts.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp -r app/Console/Commands/PurgeExpiredDocuments.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp -r app/Console/Commands/CleanupOldEvidenceChain.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"

# Upload Jobs
scp -r app/Jobs/UpdateTranslatorPerformanceJob.php "${SERVER}:${REMOTE_PATH}/app/Jobs/"
scp -r app/Jobs/CollectSystemMetricsJob.php "${SERVER}:${REMOTE_PATH}/app/Jobs/"

# Upload Observers
scp -r app/Observers/DocumentObserver.php "${SERVER}:${REMOTE_PATH}/app/Observers/"

# Upload Migration
scp -r database/migrations/2025_12_19_120000_comprehensive_platform_enhancements.php "${SERVER}:${REMOTE_PATH}/database/migrations/"

# Upload Factories
scp -r database/factories/PartnerFactory.php "${SERVER}:${REMOTE_PATH}/database/factories/"
scp -r database/factories/DocumentFactory.php "${SERVER}:${REMOTE_PATH}/database/factories/"

# Upload Configuration
scp -r config/monitoring.php "${SERVER}:${REMOTE_PATH}/config/"

# Upload Updated Files
scp -r bootstrap/app.php "${SERVER}:${REMOTE_PATH}/bootstrap/"
scp -r routes/api.php "${SERVER}:${REMOTE_PATH}/routes/"
scp -r routes/console.php "${SERVER}:${REMOTE_PATH}/routes/"
scp -r app/Providers/AppServiceProvider.php "${SERVER}:${REMOTE_PATH}/app/Providers/"
scp -r .env.example "${SERVER}:${REMOTE_PATH}/"

Write-Host "✓ Files uploaded successfully" -ForegroundColor Green
Write-Host ""

# Step 3: Set permissions
Write-Host "[3/8] Setting file permissions..." -ForegroundColor Yellow
ssh $SERVER @"
cd $REMOTE_PATH
chown -R www-data:www-data app/ database/ config/ bootstrap/ routes/
chmod -R 755 app/ database/ config/ bootstrap/ routes/
"@
Write-Host "[OK] Permissions set" -ForegroundColor Green
Write-Host ""

# Step 4: Run composer install (if needed)
Write-Host "[4/8] Checking composer dependencies..." -ForegroundColor Yellow
ssh $SERVER @"
cd $REMOTE_PATH
composer install --no-dev --optimize-autoloader
"@
Write-Host "[OK] Composer dependencies checked" -ForegroundColor Green
Write-Host ""

# Step 5: Clear caches
Write-Host "[5/8] Clearing caches..." -ForegroundColor Yellow
ssh $SERVER @"
cd $REMOTE_PATH
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
"@
Write-Host "[OK] Caches cleared" -ForegroundColor Green
Write-Host ""

# Step 6: Run migrations
Write-Host "[6/8] Running database migrations..." -ForegroundColor Yellow
Write-Host "[WARN] This will modify the database!" -ForegroundColor Yellow
$confirm = Read-Host "Continue? (yes/no)"

if ($confirm -eq "yes") {
    ssh $SERVER @"
cd $REMOTE_PATH
php artisan migrate --force
"@
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] Migrations completed successfully" -ForegroundColor Green
    } else {
        Write-Host "[ERROR] Migration failed! Rolling back..." -ForegroundColor Red
        ssh $SERVER @"
cd $REMOTE_PATH
php artisan migrate:rollback --step=1
"@
        exit 1
    }
} else {
    Write-Host "[WARN] Migrations skipped" -ForegroundColor Yellow
}
Write-Host ""

# Step 7: Restart services
Write-Host "[7/8] Restarting services..." -ForegroundColor Yellow
ssh $SERVER @"
cd $REMOTE_PATH
php artisan queue:restart
supervisorctl restart all
systemctl reload php8.3-fpm
"@
Write-Host "✓ Services restarted" -ForegroundColor Green
Write-Host ""

# Step 8: Verify deployment
Write-Host "[8/8] Verifying deployment..." -ForegroundColor Yellow
ssh $SERVER @"
cd $REMOTE_PATH
php artisan --version
php artisan route:list | grep -E 'government|partners/registry'
"@
Write-Host "[OK] Verification complete" -ForegroundColor Green
Write-Host ""

# Final summary
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Deployment Summary" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host "[OK] Backup created at: $BACKUP_DIR" -ForegroundColor Green
Write-Host "[OK] Files uploaded successfully" -ForegroundColor Green
Write-Host "[OK] Database migrations completed" -ForegroundColor Green
Write-Host "[OK] Services restarted" -ForegroundColor Green
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "1. Test API endpoints: /api/government/*, /api/partners/registry" -ForegroundColor White
Write-Host "2. Monitor logs: tail -f /var/www/cultural-translate-platform/storage/logs/laravel.log" -ForegroundColor White
Write-Host "3. Check system health: curl http://145.14.158.101/api/v1/health" -ForegroundColor White
Write-Host ""
Write-Host "Deployment completed successfully!" -ForegroundColor Green
