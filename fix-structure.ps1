# Fix Project Structure Script
# This script fixes the duplicate folder structure issue

Write-Host "Starting structure fix..." -ForegroundColor Green

# Fix Models
if (Test-Path "app/Models/Models") {
    Write-Host "Fixing Models folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Models/Models" -File | ForEach-Object {
        Move-Item $_.FullName "app/Models/" -Force
    }
    Remove-Item "app/Models/Models" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Models fixed!" -ForegroundColor Green
}

# Fix Services
if (Test-Path "app/Services/Services") {
    Write-Host "Fixing Services folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Services/Services" -File | ForEach-Object {
        Move-Item $_.FullName "app/Services/" -Force
    }
    Remove-Item "app/Services/Services" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Services fixed!" -ForegroundColor Green
}

# Fix Controllers
if (Test-Path "app/Http/Controllers/Controllers") {
    Write-Host "Fixing Controllers folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Http/Controllers/Controllers" -Recurse -File | ForEach-Object {
        $relativePath = $_.FullName.Replace((Get-Location).Path + "\app\Http\Controllers\Controllers\", "")
        $targetPath = "app/Http/Controllers/$relativePath"
        $targetDir = Split-Path $targetPath -Parent
        
        if (!(Test-Path $targetDir)) {
            New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
        }
        
        Move-Item $_.FullName $targetPath -Force
    }
    Remove-Item "app/Http/Controllers/Controllers" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Controllers fixed!" -ForegroundColor Green
}

# Fix Middleware
if (Test-Path "app/Http/Middleware/Middleware") {
    Write-Host "Fixing Middleware folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Http/Middleware/Middleware" -File | ForEach-Object {
        Move-Item $_.FullName "app/Http/Middleware/" -Force
    }
    Remove-Item "app/Http/Middleware/Middleware" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Middleware fixed!" -ForegroundColor Green
}

# Fix Jobs
if (Test-Path "app/Jobs/Jobs") {
    Write-Host "Fixing Jobs folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Jobs/Jobs" -File | ForEach-Object {
        Move-Item $_.FullName "app/Jobs/" -Force
    }
    Remove-Item "app/Jobs/Jobs" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Jobs fixed!" -ForegroundColor Green
}

# Fix Mail
if (Test-Path "app/Mail/Mail") {
    Write-Host "Fixing Mail folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Mail/Mail" -File | ForEach-Object {
        Move-Item $_.FullName "app/Mail/" -Force
    }
    Remove-Item "app/Mail/Mail" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Mail fixed!" -ForegroundColor Green
}

# Fix Filament Resources
if (Test-Path "app/Filament/Admin/Resources/Resources") {
    Write-Host "Fixing Filament Resources folder..." -ForegroundColor Yellow
    Get-ChildItem "app/Filament/Admin/Resources/Resources" -Recurse -File | ForEach-Object {
        $relativePath = $_.FullName.Replace((Get-Location).Path + "\app\Filament\Admin\Resources\Resources\", "")
        $targetPath = "app/Filament/Admin/Resources/$relativePath"
        $targetDir = Split-Path $targetPath -Parent
        
        if (!(Test-Path $targetDir)) {
            New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
        }
        
        # Check if file already exists in target
        if (Test-Path $targetPath) {
            Write-Host "  Skipping $relativePath (already exists)" -ForegroundColor Gray
        } else {
            Move-Item $_.FullName $targetPath -Force
        }
    }
    Remove-Item "app/Filament/Admin/Resources/Resources" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "Filament Resources fixed!" -ForegroundColor Green
}

Write-Host "`nRebuilding autoload..." -ForegroundColor Yellow
composer dump-autoload

Write-Host "`nStructure fix completed!" -ForegroundColor Green
Write-Host "You can now run: php artisan serve" -ForegroundColor Cyan
