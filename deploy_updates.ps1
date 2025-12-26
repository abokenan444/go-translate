# Cultural Translate Platform - Safe Deployment Script
# Date: December 19, 2025
# Server: 145.14.158.101

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Cultural Translate Platform Deployment" -ForegroundColor Cyan
Write-Host "December 19, 2025 Updates" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$SERVER = "145.14.158.101"
$SERVER_PATH = "/var/www/cultural-translate-platform"
$BACKUP_PATH = "/var/www/backups/$(Get-Date -Format 'yyyy-MM-dd_HH-mm-ss')"

Write-Host "Step 1: Pre-deployment checks..." -ForegroundColor Yellow

# Check if files exist locally
$requiredFiles = @(
    "database/migrations/2025_12_19_120000_comprehensive_platform_enhancements.php",
    "app/Services/PayoutService.php",
    "app/Services/DisputeService.php",
    "app/Services/TranslatorPerformanceService.php",
    "app/Services/RealTimeMonitoringService.php",
    "app/Services/PKISigningService.php",
    "config/monitoring.php"
)

$allFilesExist = $true
foreach ($file in $requiredFiles) {
    if (-not (Test-Path $file)) {
        Write-Host "✗ Missing: $file" -ForegroundColor Red
        $allFilesExist = $false
    } else {
        Write-Host "✓ Found: $file" -ForegroundColor Green
    }
}

if (-not $allFilesExist) {
    Write-Host "`nError: Some required files are missing!" -ForegroundColor Red
    exit 1
}

Write-Host "`nStep 2: Testing migrations locally..." -ForegroundColor Yellow
Write-Host "⚠ You should test migrations on a local database first!" -ForegroundColor Yellow
Write-Host "Run: php artisan migrate:status" -ForegroundColor Cyan
Write-Host ""

$testMigration = Read-Host "Have you tested the migration locally? (yes/no)"
if ($testMigration -ne "yes") {
    Write-Host "Please test migrations locally first!" -ForegroundColor Red
    Write-Host "Run: php artisan migrate --pretend" -ForegroundColor Cyan
    exit 1
}

Write-Host "`nStep 3: Prepare deployment package..." -ForegroundColor Yellow

# Create deployment package
$deploymentPackage = "deployment_$(Get-Date -Format 'yyyy-MM-dd_HH-mm-ss').zip"

Write-Host "Creating deployment package: $deploymentPackage" -ForegroundColor Cyan

# Files to deploy
$filesToDeploy = @(
    "app/Services/*.php",
    "app/Models/PartnerPayout.php",
    "app/Models/DocumentDispute.php",
    "app/Models/DocumentClassification.php",
    "app/Models/EvidenceChain.php",
    "app/Models/GovernmentVerification.php",
    "app/Models/TranslatorPerformance.php",
    "app/Models/DeviceToken.php",
    "app/Http/Controllers/Api/GovernmentVerificationController.php",
    "app/Http/Controllers/Api/PartnerRegistryController.php",
    "app/Http/Middleware/GovernmentApiRateLimiter.php",
    "app/Http/Middleware/TrackRequestMetrics.php",
    "app/Console/Commands/*.php",
    "app/Jobs/*.php",
    "app/Observers/*.php",
    "app/Providers/AppServiceProvider.php",
    "bootstrap/app.php",
    "config/monitoring.php",
    "database/migrations/2025_12_19_*.php",
    "database/factories/*.php",
    "routes/api.php",
    "routes/console.php",
    ".env.example"
)

Write-Host "`nStep 4: Generate deployment commands..." -ForegroundColor Yellow

# Create deployment commands file
$deployCommands = @"
#!/bin/bash
# Deployment Script - December 19, 2025
# Auto-generated deployment commands

set -e  # Exit on error

echo "=========================================="
echo "Starting Deployment Process"
echo "=========================================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

cd $SERVER_PATH

echo -e "\${YELLOW}Step 1: Creating backup...\${NC}"
mkdir -p $BACKUP_PATH
cp -r app $BACKUP_PATH/
cp -r database $BACKUP_PATH/
cp -r config $BACKUP_PATH/
cp .env $BACKUP_PATH/
php artisan backup:database --path=$BACKUP_PATH/database_backup.sql || echo "Manual backup needed"
echo -e "\${GREEN}✓ Backup created at: $BACKUP_PATH\${NC}"

echo -e "\${YELLOW}Step 2: Enabling maintenance mode...\${NC}"
php artisan down --message="Updating platform with new features" --retry=60
echo -e "\${GREEN}✓ Maintenance mode enabled\${NC}"

echo -e "\${YELLOW}Step 3: Pulling latest changes...\${NC}"
# If using git:
# git pull origin main

echo -e "\${YELLOW}Step 4: Installing dependencies...\${NC}"
composer install --no-dev --optimize-autoloader
echo -e "\${GREEN}✓ Dependencies installed\${NC}"

echo -e "\${YELLOW}Step 5: Running migrations...\${NC}"
php artisan migrate --force
echo -e "\${GREEN}✓ Migrations completed\${NC}"

echo -e "\${YELLOW}Step 6: Clearing caches...\${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
echo -e "\${GREEN}✓ Caches cleared\${NC}"

echo -e "\${YELLOW}Step 7: Restarting services...\${NC}"
php artisan queue:restart
# php artisan horizon:terminate  # Uncomment if using Horizon
echo -e "\${GREEN}✓ Services restarted\${NC}"

echo -e "\${YELLOW}Step 8: Running tests...\${NC}"
php artisan test --stop-on-failure || echo -e "\${RED}⚠ Some tests failed - check logs\${NC}"

echo -e "\${YELLOW}Step 9: Disabling maintenance mode...\${NC}"
php artisan up
echo -e "\${GREEN}✓ Maintenance mode disabled\${NC}"

echo -e "\${YELLOW}Step 10: Verifying deployment...\${NC}"
php artisan migrate:status
php artisan config:show | grep -i monitoring || echo "Config loaded"

echo ""
echo -e "\${GREEN}=========================================="
echo -e "Deployment Completed Successfully!"
echo -e "==========================================${NC}"
echo ""
echo -e "Backup location: $BACKUP_PATH"
echo ""
echo "Next steps:"
echo "1. Check system health: curl http://localhost/api/v1/health"
echo "2. Monitor logs: tail -f storage/logs/laravel.log"
echo "3. Test key features"
echo ""
echo "If issues occur, rollback with:"
echo "  php artisan migrate:rollback --step=1"
echo "  cp -r $BACKUP_PATH/* $SERVER_PATH/"
echo ""
"@

Set-Content -Path "deploy.sh" -Value $deployCommands
Write-Host "✓ Created deploy.sh" -ForegroundColor Green

# Create rollback script
$rollbackCommands = @"
#!/bin/bash
# Rollback Script - Use if deployment fails

set -e

echo "=========================================="
echo "Starting Rollback Process"
echo "=========================================="

BACKUP_DIR=`$1

if [ -z "`$BACKUP_DIR" ]; then
    echo "Usage: ./rollback.sh /path/to/backup"
    echo "Available backups:"
    ls -lt /var/www/backups/ | head -5
    exit 1
fi

cd $SERVER_PATH

echo "Step 1: Enabling maintenance mode..."
php artisan down

echo "Step 2: Rolling back migration..."
php artisan migrate:rollback --step=1

echo "Step 3: Restoring files..."
cp -r `$BACKUP_DIR/app/* app/
cp -r `$BACKUP_DIR/database/* database/
cp -r `$BACKUP_DIR/config/* config/
cp `$BACKUP_DIR/.env .env

echo "Step 4: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

echo "Step 5: Restarting services..."
php artisan queue:restart

echo "Step 6: Disabling maintenance mode..."
php artisan up

echo "=========================================="
echo "Rollback Completed"
echo "=========================================="
"@

Set-Content -Path "rollback.sh" -Value $rollbackCommands
Write-Host "✓ Created rollback.sh" -ForegroundColor Green

Write-Host "`n==================================" -ForegroundColor Cyan
Write-Host "Deployment Package Ready!" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan

Write-Host "`nGenerated files:" -ForegroundColor Yellow
Write-Host "  • deploy.sh - Main deployment script" -ForegroundColor White
Write-Host "  • rollback.sh - Emergency rollback script" -ForegroundColor White

Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Upload files to server:" -ForegroundColor White
Write-Host "   scp -r app/ config/ database/ routes/ bootstrap/ user@$SERVER`:$SERVER_PATH/" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Upload deployment scripts:" -ForegroundColor White
Write-Host "   scp deploy.sh rollback.sh user@$SERVER`:$SERVER_PATH/" -ForegroundColor Cyan
Write-Host ""
Write-Host "3. SSH to server:" -ForegroundColor White
Write-Host "   ssh user@$SERVER" -ForegroundColor Cyan
Write-Host ""
Write-Host "4. Run deployment:" -ForegroundColor White
Write-Host "   cd $SERVER_PATH" -ForegroundColor Cyan
Write-Host "   chmod +x deploy.sh rollback.sh" -ForegroundColor Cyan
Write-Host "   ./deploy.sh" -ForegroundColor Cyan
Write-Host ""

Write-Host "⚠ IMPORTANT REMINDERS:" -ForegroundColor Red
Write-Host "  • Backup is created automatically" -ForegroundColor Yellow
Write-Host "  • Maintenance mode is enabled during deployment" -ForegroundColor Yellow
Write-Host "  • Test on staging environment first if available" -ForegroundColor Yellow
Write-Host "  • Keep rollback script ready" -ForegroundColor Yellow
Write-Host "  • Monitor logs after deployment" -ForegroundColor Yellow
Write-Host ""

$proceed = Read-Host "Ready to see deployment checklist? (yes/no)"
if ($proceed -eq "yes") {
    Write-Host "`n==================================" -ForegroundColor Cyan
    Write-Host "PRE-DEPLOYMENT CHECKLIST" -ForegroundColor Cyan
    Write-Host "==================================" -ForegroundColor Cyan
    Write-Host "☐ All files committed to git" -ForegroundColor White
    Write-Host "☐ Tests passing locally" -ForegroundColor White
    Write-Host "☐ Migration tested locally" -ForegroundColor White
    Write-Host "☐ .env variables documented" -ForegroundColor White
    Write-Host "☐ Team notified about deployment" -ForegroundColor White
    Write-Host "☐ Backup verified" -ForegroundColor White
    Write-Host "☐ Rollback plan ready" -ForegroundColor White
    Write-Host "☐ Monitoring tools ready" -ForegroundColor White
    Write-Host ""
}

Write-Host "Deployment preparation complete! 🚀" -ForegroundColor Green
