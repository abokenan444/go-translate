#!/bin/bash

################################################################################
# Official Documents Translation System - Installation Script
################################################################################
# This script will:
# 1. Install required packages
# 2. Create necessary directories
# 3. Add environment variables
# 4. Create seal image template
# 5. Run migrations
# 6. Set permissions
################################################################################

set -e  # Exit on any error

echo "============================================="
echo "  Official Documents System Installation"
echo "============================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if we're in the project root
if [ ! -f "artisan" ]; then
    print_error "Error: This script must be run from the Laravel project root directory"
    exit 1
fi

print_info "Starting installation..."
echo ""

################################################################################
# Step 1: Install Required Packages
################################################################################
echo "Step 1: Installing required packages..."

if composer require simplesoftwareio/simple-qrcode:^4.2; then
    print_success "QR Code package installed successfully"
else
    print_error "Failed to install QR Code package"
    exit 1
fi

echo ""

################################################################################
# Step 2: Create Necessary Directories
################################################################################
echo "Step 2: Creating necessary directories..."

DIRECTORIES=(
    "storage/app/private/stamps"
    "storage/app/private/documents/original"
    "storage/app/private/documents/translated"
    "storage/app/private/documents/certified"
    "storage/app/private/qrcodes"
)

for dir in "${DIRECTORIES[@]}"; do
    if mkdir -p "$dir"; then
        print_success "Created directory: $dir"
    else
        print_error "Failed to create directory: $dir"
        exit 1
    fi
done

echo ""

################################################################################
# Step 3: Add Environment Variables
################################################################################
echo "Step 3: Adding environment variables..."

ENV_FILE=".env"
if [ ! -f "$ENV_FILE" ]; then
    print_error ".env file not found. Please copy .env.example to .env first"
    exit 1
fi

# Check if official docs config already exists
if grep -q "OFFICIAL_DOCS_ENABLED" "$ENV_FILE"; then
    print_info "Official documents configuration already exists in .env"
else
    cat >> "$ENV_FILE" << 'EOF'

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
EOF
    print_success "Environment variables added to .env"
fi

echo ""

################################################################################
# Step 4: Create Seal Image Template (SVG)
################################################################################
echo "Step 4: Creating seal image template..."

SEAL_FILE="storage/app/private/stamps/master-seal.svg"

cat > "$SEAL_FILE" << 'EOF'
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
EOF

print_success "Seal image template created: $SEAL_FILE"
echo ""

################################################################################
# Step 5: Run Migrations
################################################################################
echo "Step 5: Running database migrations..."

if php artisan migrate --path=database/migrations/2025_12_08_160000_create_official_documents_tables.php; then
    print_success "Database migrations completed successfully"
else
    print_error "Failed to run migrations. Please check your database configuration."
    print_info "You can run migrations manually with: php artisan migrate"
fi

echo ""

################################################################################
# Step 6: Set Proper Permissions
################################################################################
echo "Step 6: Setting directory permissions..."

if chmod -R 775 storage/app/private; then
    print_success "Permissions set successfully"
else
    print_info "Could not set permissions automatically. Please run: chmod -R 775 storage/app/private"
fi

echo ""

################################################################################
# Step 7: Clear Cache
################################################################################
echo "Step 7: Clearing application cache..."

php artisan config:clear
php artisan cache:clear
php artisan route:clear

print_success "Cache cleared successfully"
echo ""

################################################################################
# Installation Complete
################################################################################
echo "============================================="
echo "  Installation Complete!"
echo "============================================="
echo ""
print_success "The Official Documents Translation System has been installed successfully!"
echo ""
echo "Next Steps:"
echo "----------"
echo "1. Configure your Stripe API keys in .env (if not already done)"
echo "2. Test the system by visiting: /official-documents"
echo "3. Configure queue worker for processing translations:"
echo "   php artisan queue:work"
echo ""
echo "4. (Optional) Create Filament admin resources:"
echo "   php artisan make:filament-resource OfficialDocument"
echo ""
echo "Documentation:"
echo "-------------"
echo "- Implementation Guide: OFFICIAL_DOCUMENTS_IMPLEMENTATION_GUIDE.md"
echo "- Required Changes Checklist: REQUIRED_CHANGES_CHECKLIST.md"
echo ""
echo "For production deployment, make sure to:"
echo "- Set up a queue worker with Supervisor"
echo "- Configure proper backup for document storage"
echo "- Set up SSL certificate for secure payments"
echo "- Replace the seal template with professional design"
echo ""
print_success "Happy translating! 🌍📄"
echo ""
