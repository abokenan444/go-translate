# Simple deployment script
$SERVER = "root@145.14.158.101"
$REMOTE_PATH = "/var/www/cultural-translate-platform"

Write-Host "Starting deployment..." -ForegroundColor Cyan

# Step 1: Create backup
Write-Host "`n[1/6] Creating backup..." -ForegroundColor Yellow
$BACKUP_CMD = @"
mkdir -p /var/www/backups/$(date +%Y%m%d_%H%M%S)
cd $REMOTE_PATH
tar -czf /var/www/backups/$(date +%Y%m%d_%H%M%S)/backup.tar.gz app/ database/ config/
"@
ssh $SERVER $BACKUP_CMD
Write-Host "Backup complete" -ForegroundColor Green

# Step 2: Upload files
Write-Host "`n[2/6] Uploading files..." -ForegroundColor Yellow

# Create directories if they don't exist
ssh $SERVER "mkdir -p $REMOTE_PATH/app/Services $REMOTE_PATH/app/Models $REMOTE_PATH/app/Http/Controllers/Api $REMOTE_PATH/app/Http/Middleware $REMOTE_PATH/app/Console/Commands $REMOTE_PATH/app/Jobs $REMOTE_PATH/app/Observers"

# Upload all new files
scp app/Services/TranslatorPerformanceService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp app/Services/PayoutService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp app/Services/DisputeService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp app/Services/PKISigningService.php "${SERVER}:${REMOTE_PATH}/app/Services/"
scp app/Services/RealTimeMonitoringService.php "${SERVER}:${REMOTE_PATH}/app/Services/"

scp app/Http/Controllers/Api/GovernmentVerificationController.php "${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Api/"
scp app/Http/Controllers/Api/PartnerRegistryController.php "${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Api/"

scp app/Models/*.php "${SERVER}:${REMOTE_PATH}/app/Models/" 2>$null

scp app/Http/Middleware/GovernmentApiRateLimiter.php "${SERVER}:${REMOTE_PATH}/app/Http/Middleware/"
scp app/Http/Middleware/TrackRequestMetrics.php "${SERVER}:${REMOTE_PATH}/app/Http/Middleware/"

scp app/Console/Commands/UpdateAllTranslatorPerformance.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp app/Console/Commands/ProcessPayouts.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp app/Console/Commands/PurgeExpiredDocuments.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"
scp app/Console/Commands/CleanupOldEvidenceChain.php "${SERVER}:${REMOTE_PATH}/app/Console/Commands/"

scp app/Jobs/UpdateTranslatorPerformanceJob.php "${SERVER}:${REMOTE_PATH}/app/Jobs/"
scp app/Jobs/CollectSystemMetricsJob.php "${SERVER}:${REMOTE_PATH}/app/Jobs/"

scp app/Observers/DocumentObserver.php "${SERVER}:${REMOTE_PATH}/app/Observers/"

scp database/migrations/2025_12_19_120000_comprehensive_platform_enhancements.php "${SERVER}:${REMOTE_PATH}/database/migrations/"

scp config/monitoring.php "${SERVER}:${REMOTE_PATH}/config/"

scp bootstrap/app.php "${SERVER}:${REMOTE_PATH}/bootstrap/"
scp routes/api.php "${SERVER}:${REMOTE_PATH}/routes/"
scp routes/console.php "${SERVER}:${REMOTE_PATH}/routes/"
scp app/Providers/AppServiceProvider.php "${SERVER}:${REMOTE_PATH}/app/Providers/"

Write-Host "Files uploaded" -ForegroundColor Green

# Step 3: Set permissions
Write-Host "`n[3/6] Setting permissions..." -ForegroundColor Yellow
ssh $SERVER "cd $REMOTE_PATH && chown -R www-data:www-data app/ database/ config/ bootstrap/ routes/ && chmod -R 755 app/ database/ config/ bootstrap/ routes/"
Write-Host "Permissions set" -ForegroundColor Green

# Step 4: Clear caches
Write-Host "`n[4/6] Clearing caches..." -ForegroundColor Yellow
ssh $SERVER "cd $REMOTE_PATH && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear"
Write-Host "Caches cleared" -ForegroundColor Green

# Step 5: Run migrations
Write-Host "`n[5/6] Running migrations..." -ForegroundColor Yellow
Write-Host "This will modify the database. Continue? (yes/no): " -NoNewline -ForegroundColor Red
$confirm = Read-Host

if ($confirm -eq "yes") {
    ssh $SERVER "cd $REMOTE_PATH && php artisan migrate --force"
    Write-Host "Migrations completed" -ForegroundColor Green
} else {
    Write-Host "Migrations skipped" -ForegroundColor Yellow
}

# Step 6: Restart services
Write-Host "`n[6/6] Restarting services..." -ForegroundColor Yellow
ssh $SERVER "cd $REMOTE_PATH && php artisan queue:restart"
Write-Host "Services restarted" -ForegroundColor Green

Write-Host "`n================================" -ForegroundColor Cyan
Write-Host "Deployment completed!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host "`nTest the new APIs:"
Write-Host "- Government API: curl http://145.14.158.101/api/government/stats"
Write-Host "- Partner Registry: curl http://145.14.158.101/api/partners/registry"
Write-Host "- System Health: curl http://145.14.158.101/api/v1/health"
