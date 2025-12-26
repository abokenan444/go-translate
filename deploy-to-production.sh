#!/bin/bash

#######################################################
# 🚀 Deploy Script - Cultural Translate Platform
# نشر التحديثات الجديدة فقط على الإنتاج
#######################################################

set -e  # Stop on any error

echo "🚀 Starting deployment..."
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

PROJECT_PATH="/var/www/cultural-translate-platform"
BACKUP_PATH="/var/www/backups/$(date +%Y%m%d_%H%M%S)"

cd $PROJECT_PATH

# 1. Create backup
echo -e "${BLUE}📦 Creating backup...${NC}"
mkdir -p $BACKUP_PATH
cp -r app/Providers/Filament $BACKUP_PATH/
cp bootstrap/providers.php $BACKUP_PATH/
cp routes/web.php $BACKUP_PATH/
cp .env $BACKUP_PATH/
echo -e "${GREEN}✅ Backup created at: $BACKUP_PATH${NC}"
echo ""

# 2. Pull latest changes from GitHub
echo -e "${BLUE}📥 Pulling latest changes from GitHub...${NC}"
git fetch origin main
git pull origin main
echo -e "${GREEN}✅ Code updated from GitHub${NC}"
echo ""

# 3. Update Filament Admin Panel Provider
echo -e "${BLUE}🔧 Updating Filament Admin Panel Provider...${NC}"
cat > app/Providers/Filament/AdminPanelProvider.php << 'EOFADMIN'
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->domain(null)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
EOFADMIN
echo -e "${GREEN}✅ AdminPanelProvider updated${NC}"
echo ""

# 4. Update Super Admin Panel Provider
echo -e "${BLUE}🔧 Updating Super Admin Panel Provider...${NC}"
# Get current SuperAdminPanelProvider content and update only necessary parts
sed -i "s/->id('admin')/->id('super-admin')/g" app/Providers/Filament/SuperAdminPanelProvider.php
sed -i "s/->path('admin')/->path('super-admin')/g" app/Providers/Filament/SuperAdminPanelProvider.php
# Add domain(null) if not exists
if ! grep -q "->domain(null)" app/Providers/Filament/SuperAdminPanelProvider.php; then
    sed -i "/->path('super-admin')/a \            ->domain(null)" app/Providers/Filament/SuperAdminPanelProvider.php
fi
echo -e "${GREEN}✅ SuperAdminPanelProvider updated${NC}"
echo ""

# 5. Update bootstrap/providers.php
echo -e "${BLUE}🔧 Updating bootstrap/providers.php...${NC}"
# Check if AdminPanelProvider is registered
if ! grep -q "App\\\\Providers\\\\Filament\\\\AdminPanelProvider::class" bootstrap/providers.php; then
    # Add AdminPanelProvider before SuperAdminPanelProvider
    sed -i "/Filament\\\\FilamentServiceProvider::class/a \        App\\\\Providers\\\\Filament\\\\AdminPanelProvider::class," bootstrap/providers.php
fi
echo -e "${GREEN}✅ bootstrap/providers.php updated${NC}"
echo ""

# 6. Update routes/web.php for subdomain
echo -e "${BLUE}🔧 Updating routes/web.php for admin subdomain...${NC}"
# Check if admin subdomain route exists
if ! grep -q "Route::domain('admin.culturaltranslate.com')" routes/web.php; then
    # Add admin subdomain route at the beginning of the file after opening PHP tag
    sed -i '/^<?php/a \
\
use Illuminate\\Support\\Facades\\Route;\
\/\/ Admin Subdomain Routes\
Route::domain('\''admin.culturaltranslate.com'\'')->group(function () {\
    Route::get('\''\/'\'' , function () {\
        return redirect('\''/admin'\'');\
    });\
});\
' routes/web.php
fi
echo -e "${GREEN}✅ routes/web.php updated${NC}"
echo ""

# 7. Update .env for production
echo -e "${BLUE}🔧 Updating .env for production...${NC}"

# Update SESSION_DOMAIN if not set correctly
if ! grep -q "SESSION_DOMAIN=.culturaltranslate.com" .env; then
    if grep -q "SESSION_DOMAIN=" .env; then
        sed -i 's/SESSION_DOMAIN=.*/SESSION_DOMAIN=.culturaltranslate.com/' .env
    else
        echo "SESSION_DOMAIN=.culturaltranslate.com" >> .env
    fi
fi

# Update SANCTUM_STATEFUL_DOMAINS if not set
if ! grep -q "SANCTUM_STATEFUL_DOMAINS=" .env; then
    echo "SANCTUM_STATEFUL_DOMAINS=culturaltranslate.com,admin.culturaltranslate.com" >> .env
fi

# Ensure APP_URL is correct for production
if grep -q "APP_URL=http://localhost" .env; then
    sed -i 's|APP_URL=http://localhost.*|APP_URL=https://culturaltranslate.com|' .env
fi

echo -e "${GREEN}✅ .env updated for production${NC}"
echo ""

# 8. Clear all caches
echo -e "${BLUE}🧹 Clearing caches...${NC}"
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

# 9. Optimize for production
echo -e "${BLUE}⚡ Optimizing for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Optimized${NC}"
echo ""

# 10. Set correct permissions
echo -e "${BLUE}🔐 Setting permissions...${NC}"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# 11. Restart services
echo -e "${BLUE}🔄 Restarting services...${NC}"
if systemctl is-active --quiet php8.3-fpm; then
    systemctl restart php8.3-fpm
    echo -e "${GREEN}✅ PHP-FPM restarted${NC}"
elif systemctl is-active --quiet php8.2-fpm; then
    systemctl restart php8.2-fpm
    echo -e "${GREEN}✅ PHP-FPM restarted${NC}"
elif systemctl is-active --quiet php8.1-fpm; then
    systemctl restart php8.1-fpm
    echo -e "${GREEN}✅ PHP-FPM restarted${NC}"
fi

if systemctl is-active --quiet nginx; then
    systemctl reload nginx
    echo -e "${GREEN}✅ Nginx reloaded${NC}"
fi

if systemctl is-active --quiet apache2; then
    systemctl reload apache2
    echo -e "${GREEN}✅ Apache reloaded${NC}"
fi
echo ""

# 12. Summary
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo ""
echo -e "${BLUE}📋 What was updated:${NC}"
echo "   ✅ Filament Admin Panel Provider (separated from Super Admin)"
echo "   ✅ Super Admin Panel (id: super-admin, path: /super-admin)"
echo "   ✅ AdminPanelProvider registered in bootstrap/providers.php"
echo "   ✅ Admin subdomain routing (admin.culturaltranslate.com)"
echo "   ✅ Production .env settings (SESSION_DOMAIN, SANCTUM)"
echo "   ✅ All caches cleared and optimized"
echo ""
echo -e "${BLUE}🌐 Access Points:${NC}"
echo "   📌 Main Site: https://culturaltranslate.com"
echo "   📌 Admin Panel: https://admin.culturaltranslate.com"
echo "   📌 Admin Panel (alt): https://culturaltranslate.com/admin"
echo "   📌 Super Admin: https://culturaltranslate.com/super-admin"
echo "   📌 Emergency AI: https://culturaltranslate.com/emergency-ai-access"
echo ""
echo -e "${YELLOW}💡 Backup saved at: $BACKUP_PATH${NC}"
echo ""
