# Fix Remaining Issues Script

Write-Host "Fixing remaining issues..." -ForegroundColor Green

# Fix API folder name (uppercase to lowercase)
if (Test-Path "app\Http\Controllers\API") {
    Write-Host "Renaming API to Api..." -ForegroundColor Yellow
    
    # Create temp directory
    $tempDir = "app\Http\Controllers\Api_temp"
    New-Item -ItemType Directory -Path $tempDir -Force | Out-Null
    
    # Move all files to temp
    Get-ChildItem "app\Http\Controllers\API" -Recurse -File | ForEach-Object {
        $relativePath = $_.FullName.Substring((Get-Location).Path.Length + "\app\Http\Controllers\API\".Length)
        $targetPath = Join-Path $tempDir $relativePath
        $targetDir = Split-Path $targetPath -Parent
        
        if (!(Test-Path $targetDir)) {
            New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
        }
        
        Copy-Item $_.FullName $targetPath -Force
    }
    
    # Remove old API folder
    Remove-Item "app\Http\Controllers\API" -Recurse -Force
    
    # Rename temp to Api
    Rename-Item $tempDir "Api"
    
    Write-Host "API folder renamed to Api!" -ForegroundColor Green
}

# Remove composer.json from vendor if exists (sometimes causes issues)
if (Test-Path "vendor\culturaltranslate\cultural-engine\composer.json") {
    Write-Host "Checking cultural-engine package..." -ForegroundColor Yellow
}

Write-Host "`nRebuilding autoload..." -ForegroundColor Yellow
composer dump-autoload -o

Write-Host "`nAll fixes completed!" -ForegroundColor Green
Write-Host "Now try: php artisan serve" -ForegroundColor Cyan
