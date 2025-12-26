#!/bin/bash
# Pricing Consolidation Deployment Script
# Deploy all modified files to Cultural Translate production server

SERVER="yasse@145.14.158.101"
BASE_PATH="/var/www/cultural-translate-platform"

echo "=========================================="
echo "  Pricing Consolidation Deployment"
echo "  Server: 145.14.158.101"
echo "  Date: $(date)"
echo "=========================================="
echo ""

# Function to upload file
upload_file() {
    local file=$1
    local remote_path="${BASE_PATH}/${file}"
    
    echo "📤 Uploading: $file"
    scp "$file" "${SERVER}:${remote_path}"
    
    if [ $? -eq 0 ]; then
        echo "   ✅ SUCCESS"
    else
        echo "   ❌ FAILED"
        return 1
    fi
    echo ""
}

# Upload all files
echo "Starting file uploads..."
echo ""

upload_file "routes/web.php"
upload_file "resources/views/stripe/cancel.blade.php"
upload_file "resources/views/stripe/success.blade.php"
upload_file "resources/views/welcome.blade.php"
upload_file "resources/views/emails/welcome.blade.php"
upload_file "resources/views/docs/getting-started.blade.php"
upload_file "resources/views/dashboard/customer/index.blade.php"
upload_file "resources/views/docs/api-index.blade.php"
upload_file "resources/views/components/components/footer.blade.php"
upload_file "resources/views/components/footer.blade.php"
upload_file "resources/views/pages/gdpr.blade.php"

echo "=========================================="
echo "  Clearing Laravel Caches"
echo "=========================================="
echo ""

ssh ${SERVER} << 'ENDSSH'
cd /var/www/cultural-translate-platform

echo "🔄 Clearing route cache..."
php artisan route:clear

echo "🔄 Clearing view cache..."
php artisan view:clear

echo "🔄 Clearing config cache..."
php artisan config:clear

echo "🔄 Clearing application cache..."
php artisan cache:clear

echo ""
echo "✅ All caches cleared successfully!"
ENDSSH

echo ""
echo "=========================================="
echo "  Deployment Complete!"
echo "=========================================="
echo ""
echo "🧪 Testing URLs:"
echo "   • https://culturaltranslate.com/pricing"
echo "   • https://culturaltranslate.com/pricing-plans"
echo ""
echo "✅ Next steps:"
echo "   1. Test redirect: /pricing → /pricing-plans"
echo "   2. Verify 16 plans display correctly"
echo "   3. Test customer dashboard upgrade button"
echo "   4. Test Stripe integration"
echo ""
