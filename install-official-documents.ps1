################################################################################
# Official Documents Translation System - Installation Script (Windows)
################################################################################
# This script will:
# 1. Install required packages
# 2. Create necessary directories
# 3. Add environment variables
# 4. Create seal image template
# 5. Run migrations
# 6. Set permissions
################################################################################

# Ensure script stops on errors
$ErrorActionPreference = "Stop"

# Colors for output
function Write-Success {
    param([string]$Message)
    Write-Host "✓ $Message" -ForegroundColor Green
}

function Write-Error-Custom {
    param([string]$Message)
    Write-Host "✗ $Message" -ForegroundColor Red
}

function Write-Info {
    param([string]$Message)
    Write-Host "ℹ $Message" -ForegroundColor Yellow
}

# Header
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "  Official Documents System Installation" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

# Check if we're in the project root
if (-not (Test-Path "artisan")) {
    Write-Error-Custom "Error: This script must be run from the Laravel project root directory"
    exit 1
}

Write-Info "Starting installation..."
Write-Host ""

################################################################################
# Step 1: Install Required Packages
################################################################################
Write-Host "Step 1: Installing required packages..." -ForegroundColor Cyan

try {
    & composer require simplesoftwareio/simple-qrcode:^4.2
    Write-Success "QR Code package installed successfully"
}
catch {
    Write-Error-Custom "Failed to install QR Code package: $_"
    exit 1
}

Write-Host ""

################################################################################
# Step 2: Create Necessary Directories
################################################################################
Write-Host "Step 2: Creating necessary directories..." -ForegroundColor Cyan

$directories = @(
    "storage\app\private\stamps",
    "storage\app\private\documents\original",
    "storage\app\private\documents\translated",
    "storage\app\private\documents\certified",
    "storage\app\private\qrcodes"
)

foreach ($dir in $directories) {
    try {
        if (-not (Test-Path $dir)) {
            New-Item -ItemType Directory -Path $dir -Force | Out-Null
            Write-Success "Created directory: $dir"
        }
        else {
            Write-Info "Directory already exists: $dir"
        }
    }
    catch {
        Write-Error-Custom "Failed to create directory: $dir"
        exit 1
    }
}

Write-Host ""

################################################################################
# Step 3: Add Environment Variables
################################################################################
Write-Host "Step 3: Adding environment variables..." -ForegroundColor Cyan

$envFile = ".env"
if (-not (Test-Path $envFile)) {
    Write-Error-Custom ".env file not found. Please copy .env.example to .env first"
    exit 1
}

# Check if official docs config already exists
$envContent = Get-Content $envFile -Raw
if ($envContent -match "OFFICIAL_DOCS_ENABLED") {
    Write-Info "Official documents configuration already exists in .env"
}
else {
    $envAddition = @"

# ==========================================
# Official Documents Configuration
# ==========================================
OFFICIAL_DOCS_ENABLED=true
OFFICIAL_DOCS_DEFAULT_PRICE=10.00
OFFICIAL_DOCS_CURRENCY=EUR
OFFICIAL_DOCS_REQUIRE_REVIEW=false
OFFICIAL_DOCS_QR_ENABLED=true

# Certificate Issuer Information
OFFICIAL_DOCS_ISSUER_NAME="Cultural Translate"
OFFICIAL_DOCS_ISSUER_LOCATION="Amsterdam, Netherlands"
OFFICIAL_DOCS_ISSUER_LICENSE="CT-2024-TRANSLATE-001"
OFFICIAL_DOCS_ISSUER_CONTACT="certificates@culturaltranslate.com"

# Stripe Configuration (if not already set)
# STRIPE_KEY=your_stripe_publishable_key
# STRIPE_SECRET=your_stripe_secret_key
"@
    
    Add-Content -Path $envFile -Value $envAddition
    Write-Success "Environment variables added to .env"
}

Write-Host ""

################################################################################
# Step 4: Create Seal Image Template (SVG)
################################################################################
Write-Host "Step 4: Creating seal image template..." -ForegroundColor Cyan

$sealFile = "storage\app\private\stamps\master-seal.svg"

$sealContent = @'
<?xml version="1.0" encoding="UTF-8"?>
<svg width="800" height="800" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
  <!-- Background Circle -->
  <circle cx="400" cy="400" r="380" fill="none" stroke="#1e40af" stroke-width="8"/>
  <circle cx="400" cy="400" r="360" fill="none" stroke="#1e40af" stroke-width="4"/>
  
  <!-- Outer Text (Top) -->
  <path id="curve-top" d="M 150,400 A 250,250 0 0,1 650,400" fill="none"/>
  <text font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="#1e40af">
    <textPath href="#curve-top" startOffset="50%" text-anchor="middle">
      CULTURAL TRANSLATE
    </textPath>
  </text>
  
  <!-- Outer Text (Bottom) -->
  <path id="curve-bottom" d="M 150,400 A 250,250 0 0,0 650,400" fill="none"/>
  <text font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="#1e40af">
    <textPath href="#curve-bottom" startOffset="50%" text-anchor="middle">
      CERTIFIED TRANSLATION
    </textPath>
  </text>
  
  <!-- Center Star -->
  <polygon points="400,250 420,310 485,310 430,350 450,410 400,370 350,410 370,350 315,310 380,310" 
           fill="#1e40af" opacity="0.2"/>
  
  <!-- Center Text -->
  <text x="400" y="390" font-family="Georgia, serif" font-size="48" font-weight="bold" 
        fill="#1e40af" text-anchor="middle">OFFICIAL</text>
  <text x="400" y="440" font-family="Georgia, serif" font-size="36" font-weight="normal" 
        fill="#1e40af" text-anchor="middle">DOCUMENT</text>
  
  <!-- Year -->
  <text x="400" y="520" font-family="Arial, sans-serif" font-size="32" font-weight="bold" 
        fill="#1e40af" text-anchor="middle">2024</text>
  
  <!-- Decorative Elements -->
  <circle cx="400" cy="400" r="200" fill="none" stroke="#1e40af" stroke-width="2" opacity="0.3"/>
  
  <!-- Corner Stars -->
  <polygon points="400,100 405,115 420,115 408,125 412,140 400,130 388,140 392,125 380,115 395,115" 
           fill="#1e40af" opacity="0.4"/>
  <polygon points="400,700 405,685 420,685 408,675 412,660 400,670 388,660 392,675 380,685 395,685" 
           fill="#1e40af" opacity="0.4"/>
  <polygon points="100,400 115,395 115,380 125,392 140,388 130,400 140,412 125,408 115,420 115,405" 
           fill="#1e40af" opacity="0.4"/>
  <polygon points="700,400 685,395 685,380 675,392 660,388 670,400 660,412 675,408 685,420 685,405" 
           fill="#1e40af" opacity="0.4"/>
</svg>
'@

Set-Content -Path $sealFile -Value $sealContent -Encoding UTF8
Write-Success "Seal image template created: $sealFile"
Write-Host ""

################################################################################
# Step 5: Run Migrations
################################################################################
Write-Host "Step 5: Running database migrations..." -ForegroundColor Cyan

try {
    & php artisan migrate --path=database/migrations/2025_12_08_160000_create_official_documents_tables.php
    Write-Success "Database migrations completed successfully"
}
catch {
    Write-Error-Custom "Failed to run migrations. Please check your database configuration."
    Write-Info "You can run migrations manually with: php artisan migrate"
}

Write-Host ""

################################################################################
# Step 6: Clear Cache
################################################################################
Write-Host "Step 6: Clearing application cache..." -ForegroundColor Cyan

try {
    & php artisan config:clear
    & php artisan cache:clear
    & php artisan route:clear
    Write-Success "Cache cleared successfully"
}
catch {
    Write-Info "Some cache clearing commands failed, but installation can continue"
}

Write-Host ""

################################################################################
# Installation Complete
################################################################################
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "  Installation Complete!" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""
Write-Success "The Official Documents Translation System has been installed successfully!"
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "----------"
Write-Host "1. Configure your Stripe API keys in .env (if not already done)"
Write-Host "2. Test the system by visiting: /official-documents"
Write-Host "3. Configure queue worker for processing translations:"
Write-Host "   php artisan queue:work"
Write-Host ""
Write-Host "4. (Optional) Create Filament admin resources:"
Write-Host "   php artisan make:filament-resource OfficialDocument"
Write-Host ""
Write-Host "Documentation:" -ForegroundColor Yellow
Write-Host "-------------"
Write-Host "- Implementation Guide: OFFICIAL_DOCUMENTS_IMPLEMENTATION_GUIDE.md"
Write-Host "- Required Changes Checklist: REQUIRED_CHANGES_CHECKLIST.md"
Write-Host ""
Write-Host "For production deployment, make sure to:" -ForegroundColor Yellow
Write-Host "- Set up a queue worker (Task Scheduler on Windows)"
Write-Host "- Configure proper backup for document storage"
Write-Host "- Set up SSL certificate for secure payments"
Write-Host "- Replace the seal template with professional design"
Write-Host ""
Write-Success "Happy translating! 🌍📄"
Write-Host ""
