#!/bin/bash
# Quick Sync: One-command synchronization
# Usage: bash quick-sync.sh

echo "🔄 Starting Quick Sync..."
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

SERVER="root@145.14.158.101"
PLATFORM_PATH="/var/www/cultural-translate-platform"

echo -e "${YELLOW}Step 1: Backing up server...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && tar -czf /root/backups/backup_\$(date +%Y%m%d_%H%M%S).tar.gz --exclude=node_modules --exclude=vendor ."
echo -e "${GREEN}✓ Backup created${NC}"
echo ""

echo -e "${YELLOW}Step 2: Stashing server changes...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && git add -A && git stash push -m 'Auto-stash - \$(date +%Y%m%d_%H%M%S)'"
echo -e "${GREEN}✓ Changes stashed${NC}"
echo ""

echo -e "${YELLOW}Step 3: Pulling from GitHub...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && git pull origin main"
echo -e "${GREEN}✓ Updates pulled${NC}"
echo ""

echo -e "${YELLOW}Step 4: Restoring server changes...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && git stash pop || echo 'No changes to restore'"
echo -e "${GREEN}✓ Changes restored${NC}"
echo ""

echo -e "${YELLOW}Step 5: Installing dependencies...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && composer install --no-interaction --optimize-autoloader"
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

echo -e "${YELLOW}Step 6: Clearing cache...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && php artisan config:cache && php artisan route:cache && php artisan view:cache"
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

echo -e "${YELLOW}Step 7: Running migrations...${NC}"
ssh $SERVER "cd $PLATFORM_PATH && php artisan migrate --force"
echo -e "${GREEN}✓ Migrations completed${NC}"
echo ""

echo -e "${YELLOW}Step 8: Restarting services...${NC}"
ssh $SERVER "systemctl restart php8.3-fpm && systemctl restart nginx"
echo -e "${GREEN}✓ Services restarted${NC}"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ Synchronization completed successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Platform is now up to date!"
echo "Visit: https://culturaltranslate.com"
