# CulturalTranslate Mobile App - Build Script
# ==========================================
# هذا السكريبت يقوم ببناء التطبيق لنظامي Android و iOS

Write-Host "======================================" -ForegroundColor Cyan
Write-Host "  CulturalTranslate App Builder" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

# التأكد من وجود Flutter
$flutterPath = Get-Command flutter -ErrorAction SilentlyContinue
if (-not $flutterPath) {
    Write-Host "ERROR: Flutter not found in PATH" -ForegroundColor Red
    exit 1
}

# الانتقال إلى مجلد التطبيق
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptDir

Write-Host "[1/5] Cleaning previous builds..." -ForegroundColor Yellow
flutter clean

Write-Host "[2/5] Getting dependencies..." -ForegroundColor Yellow
flutter pub get

Write-Host "[3/5] Checking for issues..." -ForegroundColor Yellow
flutter analyze --no-fatal-infos

# إنشاء keystore إذا لم يكن موجوداً
$keystorePath = "android\keystore\culturaltranslate-release.jks"
if (-not (Test-Path $keystorePath)) {
    Write-Host "[3.5/5] Creating release keystore..." -ForegroundColor Yellow
    
    # التحقق من وجود keytool
    $keytool = Get-Command keytool -ErrorAction SilentlyContinue
    if ($keytool) {
        keytool -genkey -v -keystore $keystorePath `
            -storetype JKS `
            -keyalg RSA `
            -keysize 2048 `
            -validity 10000 `
            -alias culturaltranslate `
            -storepass culturaltranslate2025 `
            -keypass culturaltranslate2025 `
            -dname "CN=CulturalTranslate, OU=Mobile, O=CulturalTranslate, L=Berlin, ST=Berlin, C=DE"
        
        Write-Host "Keystore created successfully!" -ForegroundColor Green
    } else {
        Write-Host "WARNING: keytool not found. Building with debug signature." -ForegroundColor Yellow
    }
}

# بناء Android APK
Write-Host "" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "[4/5] Building Android APK..." -ForegroundColor Yellow
Write-Host "======================================" -ForegroundColor Cyan

flutter build apk --release --split-per-abi

if ($LASTEXITCODE -eq 0) {
    Write-Host "Android APK built successfully!" -ForegroundColor Green
    Write-Host "Location: build\app\outputs\flutter-apk\" -ForegroundColor Cyan
    
    # نسخ APK إلى مجلد مخصص
    $outputDir = "build\release"
    New-Item -ItemType Directory -Force -Path $outputDir | Out-Null
    
    Copy-Item "build\app\outputs\flutter-apk\app-arm64-v8a-release.apk" "$outputDir\CulturalTranslate-arm64.apk" -Force
    Copy-Item "build\app\outputs\flutter-apk\app-armeabi-v7a-release.apk" "$outputDir\CulturalTranslate-arm32.apk" -Force
    Copy-Item "build\app\outputs\flutter-apk\app-x86_64-release.apk" "$outputDir\CulturalTranslate-x86_64.apk" -Force
    
    Write-Host "APKs copied to: $outputDir" -ForegroundColor Green
} else {
    Write-Host "Android APK build failed!" -ForegroundColor Red
}

# بناء Android App Bundle (للنشر على Play Store)
Write-Host "" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "[4.5/5] Building Android App Bundle..." -ForegroundColor Yellow
Write-Host "======================================" -ForegroundColor Cyan

flutter build appbundle --release

if ($LASTEXITCODE -eq 0) {
    Write-Host "Android App Bundle built successfully!" -ForegroundColor Green
    Copy-Item "build\app\outputs\bundle\release\app-release.aab" "$outputDir\CulturalTranslate.aab" -Force
    Write-Host "AAB copied to: $outputDir\CulturalTranslate.aab" -ForegroundColor Green
} else {
    Write-Host "Android App Bundle build failed!" -ForegroundColor Red
}

# محاولة بناء iOS (يتطلب macOS)
Write-Host "" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "[5/5] iOS Build..." -ForegroundColor Yellow
Write-Host "======================================" -ForegroundColor Cyan

if ($IsWindows -or $env:OS -match "Windows") {
    Write-Host "iOS build requires macOS. Skipping..." -ForegroundColor Yellow
    Write-Host "" -ForegroundColor Cyan
    Write-Host "To build for iOS on macOS, run:" -ForegroundColor Cyan
    Write-Host "  flutter build ios --release" -ForegroundColor White
    Write-Host "  flutter build ipa --release" -ForegroundColor White
} else {
    flutter build ios --release --no-codesign
    if ($LASTEXITCODE -eq 0) {
        Write-Host "iOS build completed successfully!" -ForegroundColor Green
    }
}

Write-Host "" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "  Build Complete!" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Output files:" -ForegroundColor White
Write-Host "  Android APK (arm64): $outputDir\CulturalTranslate-arm64.apk" -ForegroundColor Cyan
Write-Host "  Android APK (arm32): $outputDir\CulturalTranslate-arm32.apk" -ForegroundColor Cyan
Write-Host "  Android AAB: $outputDir\CulturalTranslate.aab" -ForegroundColor Cyan
Write-Host ""
