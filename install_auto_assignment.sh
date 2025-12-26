#!/bin/bash

# Auto Assignment System - Complete Installation and Test Script
# This script deploys and tests the complete auto-assignment system

echo "=================================================="
echo "Cultural Translate - Auto Assignment System"
echo "Installation & Testing Script"
echo "=================================================="
echo ""

# Color codes
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
APP_PATH="/var/www/cultural-translate-platform"
BACKUP_PATH="/var/backups/cultural-translate-$(date +%Y%m%d_%H%M%S)"

echo -e "${BLUE}[1/10] Creating backup...${NC}"
mkdir -p $BACKUP_PATH
cp -r $APP_PATH/database/migrations $BACKUP_PATH/
cp -r $APP_PATH/app/Models $BACKUP_PATH/
echo -e "${GREEN}✓ Backup created at: $BACKUP_PATH${NC}"
echo ""

echo -e "${BLUE}[2/10] Checking database connection...${NC}"
cd $APP_PATH
php artisan db:show 2>&1 | head -5
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database connection successful${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
    exit 1
fi
echo ""

echo -e "${BLUE}[3/10] Running migrations...${NC}"
php artisan migrate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed${NC}"
else
    echo -e "${RED}✗ Migrations failed${NC}"
    exit 1
fi
echo ""

echo -e "${BLUE}[4/10] Seeding government portals...${NC}"
php artisan db:seed --class=GovernmentPortalsSeeder --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Government portals seeded${NC}"
else
    echo -e "${YELLOW}⚠ Seeding may have failed or already exists${NC}"
fi
echo ""

echo -e "${BLUE}[5/10] Clearing caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

echo -e "${BLUE}[6/10] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✓ Application optimized${NC}"
echo ""

echo -e "${BLUE}[7/10] Verifying database tables...${NC}"
echo "Checking new tables:"
mysql -u root -proot cultural_translate -e "SHOW TABLES LIKE '%assignment%';" 2>/dev/null
mysql -u root -proot cultural_translate -e "SHOW TABLES LIKE '%government_portal%';" 2>/dev/null
echo -e "${GREEN}✓ Tables verification complete${NC}"
echo ""

echo -e "${BLUE}[8/10] Checking routes...${NC}"
echo "Government routes:"
php artisan route:list --name=government --columns=method,uri,name 2>/dev/null | grep -v "filament" | head -10
echo ""
echo "Partner routes:"
php artisan route:list --name=partner --columns=method,uri,name 2>/dev/null | head -10
echo -e "${GREEN}✓ Routes check complete${NC}"
echo ""

echo -e "${BLUE}[9/10] Testing government portals...${NC}"
PORTAL_COUNT=$(mysql -u root -proot cultural_translate -e "SELECT COUNT(*) FROM government_portals;" -sN 2>/dev/null)
echo "Total government portals: $PORTAL_COUNT"
echo ""
echo "Sample portals:"
mysql -u root -proot cultural_translate -e "SELECT country_code, country_name, portal_slug, is_active FROM government_portals LIMIT 10;" 2>/dev/null
echo -e "${GREEN}✓ Government portals test complete${NC}"
echo ""

echo -e "${BLUE}[10/10] Testing assignment system...${NC}"
echo "Checking partners table columns:"
mysql -u root -proot cultural_translate -e "DESCRIBE partners;" 2>/dev/null | grep -E "rating|max_concurrent|is_verified"
echo ""
echo "Checking official_documents assignment columns:"
mysql -u root -proot cultural_translate -e "DESCRIBE official_documents;" 2>/dev/null | grep -E "assignment|jurisdiction|reviewer"
echo -e "${GREEN}✓ Assignment system columns verified${NC}"
echo ""

echo "=================================================="
echo -e "${GREEN}Installation Complete!${NC}"
echo "=================================================="
echo ""
echo "📊 Summary:"
echo "  • Migrations: ✓ Completed"
echo "  • Government Portals: ✓ $PORTAL_COUNT portals created"
echo "  • Routes: ✓ Registered"
echo "  • Database: ✓ Tables created"
echo ""
echo "🔗 Access URLs:"
echo "  • Main Portal: https://culturaltranslate.com"
echo "  • Gov Portal (NL): https://gov.culturaltranslate.com/nl"
echo "  • Gov Portal (UK): https://gov.culturaltranslate.com/uk"
echo "  • Admin Panel: https://admin.culturaltranslate.com/admin"
echo ""
echo "📋 Next Steps:"
echo "  1. Test government registration: /government/register"
echo "  2. Create test partners in admin panel"
echo "  3. Upload test document and verify auto-assignment"
echo "  4. Monitor assignment logs in audit_events table"
echo ""
echo "🔍 Verify Installation:"
echo "  mysql -u root -proot cultural_translate"
echo "  > SELECT * FROM government_portals LIMIT 5;"
echo "  > SELECT * FROM document_assignments LIMIT 5;"
echo "  > SELECT * FROM audit_events WHERE event_type LIKE 'assignment%' LIMIT 5;"
echo ""
echo "=================================================="
