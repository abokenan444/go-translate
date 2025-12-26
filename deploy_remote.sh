#!/bin/bash

# Remote Deployment Script
# This script runs on the server to deploy the uploaded package

APP_DIR="/var/www/cultural-translate-platform"
BACKUP_DIR="/var/www/backups/cultural-translate_$(date +%Y%m%d_%H%M%S)"

echo "Starting deployment..."

# 1. Create backup
if [ -d "$APP_DIR" ]; then
    echo "Creating backup at $BACKUP_DIR..."
    mkdir -p $(dirname $BACKUP_DIR)
    cp -r $APP_DIR $BACKUP_DIR
fi

# 2. Extract new files
echo "Extracting files..."
mkdir -p $APP_DIR
unzip -o /var/www/cultural-translate-platform/deploy_package.zip -d $APP_DIR

# 3. Restore Storage & Env
echo "Restoring storage and .env..."
if [ -d "$BACKUP_DIR/storage" ]; then
    cp -r $BACKUP_DIR/storage $APP_DIR/
else
    # Create storage structure if not exists
    mkdir -p $APP_DIR/storage/app/public
    mkdir -p $APP_DIR/storage/framework/cache/data
    mkdir -p $APP_DIR/storage/framework/sessions
    mkdir -p $APP_DIR/storage/framework/testing
    mkdir -p $APP_DIR/storage/framework/views
    mkdir -p $APP_DIR/storage/logs
fi

if [ -f "$BACKUP_DIR/.env" ]; then
    cp $BACKUP_DIR/.env $APP_DIR/
else
    if [ -f "$APP_DIR/.env.example" ]; then
        cp $APP_DIR/.env.example $APP_DIR/.env
    fi
fi

# Ensure bootstrap/cache exists
mkdir -p $APP_DIR/bootstrap/cache

# 4. Set permissions
echo "Setting permissions..."
chown -R www-data:www-data $APP_DIR
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# 4. Install dependencies
echo "Installing dependencies..."
cd $APP_DIR
composer install --no-dev --optimize-autoloader

# 5. Run migrations
echo "Running migrations..."
php artisan migrate --force

# 6. Optimize
echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Restart Queue
echo "Restarting queue..."
php artisan queue:restart

echo "Deployment completed successfully!"
