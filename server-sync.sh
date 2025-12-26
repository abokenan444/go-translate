#!/bin/bash
# Server-side sync script
# Run this on the server to pull latest changes

set -e

PLATFORM_PATH="/var/www/cultural-translate-platform"
BACKUP_DIR="/root/backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

echo "=========================================="
echo "CulturalTranslate Platform Update Script"
echo "=========================================="
echo ""

# Step 1: Create backup
echo "[1/6] Creating backup..."
mkdir -p "$BACKUP_DIR"
cd "$PLATFORM_PATH"
tar -czf "$BACKUP_DIR/platform_backup_$TIMESTAMP.tar.gz" \
    --exclude=node_modules \
    --exclude=vendor \
    --exclude=storage/logs/* \
    --exclude=storage/framework/cache/* \
    --exclude=storage/framework/sessions/* \
    --exclude=.git \
    .
echo "✓ Backup created: $BACKUP_DIR/platform_backup_$TIMESTAMP.tar.gz"

# Step 2: Check current status
echo ""
echo "[2/6] Checking current status..."
git status --short

# Step 3: Stash local changes
echo ""
echo "[3/6] Stashing local changes (if any)..."
git add -A
git stash push -m "Auto-stash before sync - $TIMESTAMP"

# Step 4: Pull from GitHub
echo ""
echo "[4/6] Pulling latest from GitHub..."
git pull origin main --no-rebase

# Step 5: Re-apply stashed changes
echo ""
echo "[5/6] Re-applying local changes..."
git stash pop || echo "No stashed changes to apply"

# Step 6: Update dependencies and clear cache
echo ""
echo "[6/6] Updating platform..."
composer install --no-interaction --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo ""
echo "=========================================="
echo "✓ Platform updated successfully!"
echo "=========================================="
echo ""
echo "Database migrations status:"
php artisan migrate:status | head -10
echo ""
echo "To run migrations: php artisan migrate --force"
echo "To restart services: systemctl restart php8.3-fpm nginx"
