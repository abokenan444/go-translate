#!/usr/bin/env pwsh
# CulturalTranslate Deployment Script
# Server: 145.14.158.101
# Path: /var/www/cultural-translate-platform

$serverIP = "145.14.158.101"
$serverUser = "root"
$serverPath = "/var/www/cultural-translate-platform"
$localPath = "c:\Users\YASSE\Downloads\culturaltranslate-dev"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   CulturalTranslate Deployment Script  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# Files and folders to sync
$foldersToSync = @(
    "app",
    "config",
    "database/migrations",
    "database/seeders",
    "resources/views",
    "routes",
    "public/js",
    "public/css"
)

$filesToSync = @(
    "composer.json",
    "package.json"
)

Write-Host "`n[1/5] Preparing files for deployment..." -ForegroundColor Yellow

# Create a temporary directory for deployment
$tempDir = "$env:TEMP\ct_deploy_$(Get-Date -Format 'yyyyMMddHHmmss')"
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

foreach ($folder in $foldersToSync) {
    $source = Join-Path $localPath $folder
    $dest = Join-Path $tempDir $folder
    if (Test-Path $source) {
        Copy-Item -Path $source -Destination $dest -Recurse -Force
        Write-Host "  Copied: $folder" -ForegroundColor Green
    }
}

foreach ($file in $filesToSync) {
    $source = Join-Path $localPath $file
    $dest = Join-Path $tempDir $file
    if (Test-Path $source) {
        Copy-Item -Path $source -Destination $dest -Force
        Write-Host "  Copied: $file" -ForegroundColor Green
    }
}

Write-Host "`n[2/5] Files prepared at: $tempDir" -ForegroundColor Green
Write-Host "`nPlease use your preferred method to upload files to the server."
Write-Host "Target: $serverUser@$serverIP`:$serverPath"

Write-Host "`n[3/5] Server commands to run after upload:" -ForegroundColor Yellow
Write-Host @"

# SSH to server
ssh $serverUser@$serverIP

# Navigate to project
cd $serverPath

# Update permissions
chown -R www-data:www-data .
chmod -R 755 storage bootstrap/cache

# Install dependencies (if composer.json changed)
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
systemctl restart php8.3-fpm
systemctl restart nginx

"@

Write-Host "`nDeployment preparation complete!" -ForegroundColor Cyan
