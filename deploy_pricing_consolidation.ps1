# Pricing Page Consolidation Deployment Script
# This script uploads all files modified to consolidate /pricing and /pricing-plans

$server = "145.14.158.101"
$username = "yasse"
$basePath = "/var/www/cultural-translate-platform"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "PRICING PAGE CONSOLIDATION DEPLOYMENT" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Files to upload
$files = @(
    "routes/web.php",
    "resources/views/stripe/cancel.blade.php",
    "resources/views/stripe/success.blade.php",
    "resources/views/welcome.blade.php",
    "resources/views/emails/welcome.blade.php",
    "resources/views/docs/getting-started.blade.php",
    "resources/views/dashboard/customer/index.blade.php",
    "resources/views/docs/api-index.blade.php",
    "resources/views/components/components/footer.blade.php",
    "resources/views/components/footer.blade.php",
    "resources/views/pages/gdpr.blade.php"
)

Write-Host "Files to upload: $($files.Count)" -ForegroundColor Yellow
Write-Host ""

# Upload each file using SCP
foreach ($file in $files) {
    $localPath = Join-Path $PSScriptRoot $file
    $remotePath = "$basePath/$($file -replace '\\', '/')"
    
    Write-Host "Uploading: $file" -ForegroundColor Cyan
    
    # Use SCP to upload
    & scp "$localPath" "${username}@${server}:${remotePath}"
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  ✓ Success" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Failed" -ForegroundColor Red
    }
    Write-Host ""
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "CACHE CLEARING" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Clear Laravel caches
$commands = @(
    "cd $basePath && php artisan route:clear",
    "cd $basePath && php artisan view:clear",
    "cd $basePath && php artisan config:clear",
    "cd $basePath && php artisan cache:clear"
)

foreach ($cmd in $commands) {
    Write-Host "Executing: $cmd" -ForegroundColor Yellow
    & ssh "${username}@${server}" "$cmd"
    Write-Host ""
}

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "DEPLOYMENT COMPLETE" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Green

Write-Host "Changes deployed:" -ForegroundColor Cyan
Write-Host "  • /pricing now redirects to /pricing-plans (301)" -ForegroundColor White
Write-Host "  • /pricing-plans fetches plans from database" -ForegroundColor White
Write-Host "  • All 10 internal links updated to use /pricing-plans" -ForegroundColor White
Write-Host "  • Stripe success/cancel pages updated" -ForegroundColor White
Write-Host "  • Customer dashboard upgrade button updated" -ForegroundColor White
Write-Host "  • Documentation pages updated" -ForegroundColor White
Write-Host "  • Footer navigation updated" -ForegroundColor White
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Test https://culturaltranslate.com/pricing-plans" -ForegroundColor White
Write-Host "  2. Test https://culturaltranslate.com/pricing (should redirect)" -ForegroundColor White
Write-Host "  3. Verify customer dashboard upgrade flows" -ForegroundColor White
Write-Host "  4. Test Stripe checkout integration" -ForegroundColor White
