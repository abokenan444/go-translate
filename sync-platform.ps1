# PowerShell Script لمزامنة المنصة مع السيرفر
# Server Sync Script for CulturalTranslate Platform

$SERVER = "root@145.14.158.101"
$SERVER_PATH = "/var/www/cultural-translate-platform"
$LOCAL_PATH = "C:\Users\YASSE\Downloads\culturaltranslate-dev"

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Platform Synchronization Script" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Backup server first
Write-Host "[1/5] Creating server backup..." -ForegroundColor Yellow
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
ssh $SERVER "cd $SERVER_PATH && tar -czf /root/backups/platform_backup_$timestamp.tar.gz --exclude=node_modules --exclude=vendor --exclude=storage/logs --exclude=storage/framework/cache --exclude=storage/framework/sessions ."
Write-Host "✓ Backup created" -ForegroundColor Green

# Step 2: Pull latest changes from server to local (dry-run first)
Write-Host ""
Write-Host "[2/5] Checking changes on server..." -ForegroundColor Yellow
ssh $SERVER "cd $SERVER_PATH && git status --short" | Out-Host

# Step 3: Commit server changes if any
Write-Host ""
Write-Host "[3/5] Committing server changes..." -ForegroundColor Yellow
ssh $SERVER "cd $SERVER_PATH && git add -A && git commit -m 'Server updates - $timestamp' || echo 'No changes to commit'"

# Step 4: Push local changes to GitHub
Write-Host ""
Write-Host "[4/5] Ensuring local changes are on GitHub..." -ForegroundColor Yellow
cd $LOCAL_PATH
git push origin main
Write-Host "✓ Local changes pushed to GitHub" -ForegroundColor Green

# Step 5: Pull from GitHub on server
Write-Host ""
Write-Host "[5/5] Pulling latest from GitHub to server..." -ForegroundColor Yellow
ssh $SERVER "cd $SERVER_PATH && git pull origin main"
Write-Host "✓ Server updated from GitHub" -ForegroundColor Green

Write-Host ""
Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Synchronization completed!" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Run migrations on server if needed" -ForegroundColor White
Write-Host "2. Clear cache on server" -ForegroundColor White
Write-Host "3. Restart services if needed" -ForegroundColor White
