#!/bin/bash

# ========================================
# Real-Time Voice Translation Module Installer
# Cultural Translate Platform
# ========================================

set -e  # Exit on error

echo "========================================="
echo "Real-Time Voice Translation Installer"
echo "========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root${NC}"
    exit 1
fi

# Get project path
PROJECT_PATH="/var/www/cultural-translate-platform"

if [ ! -d "$PROJECT_PATH" ]; then
    echo -e "${RED}Project directory not found: $PROJECT_PATH${NC}"
    exit 1
fi

cd "$PROJECT_PATH"

echo -e "${GREEN}✓${NC} Project found: $PROJECT_PATH"
echo ""

# ========================================
# 1. Check Dependencies
# ========================================

echo "Checking dependencies..."

# Check PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP not found${NC}"
    exit 1
fi
echo -e "${GREEN}✓${NC} PHP $(php -v | head -n 1 | cut -d ' ' -f 2)"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}✗ Composer not found${NC}"
    exit 1
fi
echo -e "${GREEN}✓${NC} Composer installed"

# Check Laravel
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ Laravel project not found${NC}"
    exit 1
fi
echo -e "${GREEN}✓${NC} Laravel project detected"

echo ""

# ========================================
# 2. Install Required Packages
# ========================================

echo "Installing required packages..."

# Check if openai-php/client is installed
if ! composer show openai-php/client &> /dev/null; then
    echo "Installing openai-php/client..."
    COMPOSER_ALLOW_SUPERUSER=1 composer require openai-php/client --no-interaction
    echo -e "${GREEN}✓${NC} openai-php/client installed"
else
    echo -e "${GREEN}✓${NC} openai-php/client already installed"
fi

# Check if laravel/sanctum is installed
if ! composer show laravel/sanctum &> /dev/null; then
    echo "Installing laravel/sanctum..."
    COMPOSER_ALLOW_SUPERUSER=1 composer require laravel/sanctum --no-interaction
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    echo -e "${GREEN}✓${NC} Laravel Sanctum installed"
else
    echo -e "${GREEN}✓${NC} Laravel Sanctum already installed"
fi

echo ""

# ========================================
# 3. Run Migrations
# ========================================

echo "Running migrations..."

if php artisan migrate --force; then
    echo -e "${GREEN}✓${NC} Migrations completed"
else
    echo -e "${YELLOW}⚠${NC} Some migrations may have already been run"
fi

echo ""

# ========================================
# 4. Create Storage Link
# ========================================

echo "Creating storage link..."

if [ -L "public/storage" ]; then
    echo -e "${GREEN}✓${NC} Storage link already exists"
else
    php artisan storage:link
    echo -e "${GREEN}✓${NC} Storage link created"
fi

echo ""

# ========================================
# 5. Create Required Directories
# ========================================

echo "Creating required directories..."

mkdir -p storage/app/public/realtime
chmod -R 775 storage/app/public/realtime
chown -R www-data:www-data storage/app/public/realtime

echo -e "${GREEN}✓${NC} Realtime storage directory created"

echo ""

# ========================================
# 6. Check Environment Variables
# ========================================

echo "Checking environment variables..."

# Check OpenAI API Key
if grep -q "^OPENAI_API_KEY=sk-" .env; then
    echo -e "${GREEN}✓${NC} OpenAI API Key is set"
else
    echo -e "${YELLOW}⚠${NC} OpenAI API Key not found in .env"
    echo "Please add: OPENAI_API_KEY=sk-your-key-here"
fi

# Check Broadcast Driver
if grep -q "^BROADCAST_DRIVER=" .env; then
    BROADCAST_DRIVER=$(grep "^BROADCAST_DRIVER=" .env | cut -d '=' -f 2)
    echo -e "${GREEN}✓${NC} Broadcast driver: $BROADCAST_DRIVER"
    
    if [ "$BROADCAST_DRIVER" = "log" ]; then
        echo -e "${YELLOW}⚠${NC} Broadcasting is disabled (using log driver)"
        echo "To enable WebSockets, set BROADCAST_DRIVER=pusher or install Laravel WebSockets"
    fi
else
    echo -e "${YELLOW}⚠${NC} BROADCAST_DRIVER not set in .env"
fi

echo ""

# ========================================
# 7. Clear Cache
# ========================================

echo "Clearing cache..."

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo -e "${GREEN}✓${NC} Cache cleared"

echo ""

# ========================================
# 8. Set Permissions
# ========================================

echo "Setting permissions..."

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo -e "${GREEN}✓${NC} Permissions set"

echo ""

# ========================================
# 9. Verify Installation
# ========================================

echo "Verifying installation..."

# Check if routes exist
if php artisan route:list | grep -q "realtime/meeting"; then
    echo -e "${GREEN}✓${NC} Real-Time routes registered"
else
    echo -e "${RED}✗${NC} Real-Time routes not found"
fi

# Check if models exist
if [ -f "app/Models/RealTimeSession.php" ]; then
    echo -e "${GREEN}✓${NC} RealTimeSession model exists"
else
    echo -e "${RED}✗${NC} RealTimeSession model not found"
fi

# Check if views exist
if [ -f "resources/views/realtime/meeting.blade.php" ]; then
    echo -e "${GREEN}✓${NC} Meeting view exists"
else
    echo -e "${RED}✗${NC} Meeting view not found"
fi

# Check if Filament resources exist
if [ -f "app/Filament/Admin/Resources/RealTimeSessionResource.php" ]; then
    echo -e "${GREEN}✓${NC} Filament resources exist"
else
    echo -e "${RED}✗${NC} Filament resources not found"
fi

echo ""

# ========================================
# 10. Installation Summary
# ========================================

echo "========================================="
echo "Installation Summary"
echo "========================================="
echo ""

echo -e "${GREEN}✓${NC} Real-Time Voice Translation Module installed successfully!"
echo ""

echo "Next steps:"
echo ""
echo "1. Verify OpenAI API Key in .env:"
echo "   OPENAI_API_KEY=sk-your-key-here"
echo ""
echo "2. (Optional) Enable WebSockets for real-time updates:"
echo "   - Option A: Use Pusher (add credentials to .env)"
echo "   - Option B: Install Laravel WebSockets"
echo ""
echo "3. Access the module:"
echo "   - Admin Panel: https://admin.culturaltranslate.com/admin/real-time-sessions"
echo "   - API Endpoint: POST /api/realtime/sessions"
echo ""
echo "4. Test the module:"
echo "   - Create a session via API"
echo "   - Open meeting page: /realtime/meeting/{publicId}"
echo "   - Upload audio and get translation"
echo ""

echo "========================================="
echo "Installation Complete!"
echo "========================================="
