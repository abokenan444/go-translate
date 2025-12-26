#!/bin/bash

# Script to apply fixes directly on server
cd /var/www/cultural-translate-platform

echo "=== Applying fixes to CulturalTranslate Platform ==="

# Backup current files
echo "Creating backup..."
cp app/Http/Controllers/EnterpriseSubscriptionController.php app/Http/Controllers/EnterpriseSubscriptionController.php.backup
cp app/Http/Controllers/ComplaintController.php app/Http/Controllers/ComplaintController.php.backup
cp resources/views/components/footer.blade.php resources/views/components/footer.blade.php.backup
cp resources/views/pages/about.blade.php resources/views/pages/about.blade.php.backup

echo "Backup created!"

# Fix 1: EnterpriseSubscriptionController - Replace auth()->id() with auth()->user()?->id
echo "Fixing EnterpriseSubscriptionController..."
sed -i "s/auth()->id()/auth()->user()?->id/g" app/Http/Controllers/EnterpriseSubscriptionController.php

# Fix 2: ComplaintController - Replace auth()->id() with auth()->user()?->id  
echo "Fixing ComplaintController..."
sed -i "s/auth()->id()/auth()->user()?->id/g" app/Http/Controllers/ComplaintController.php

# Fix 3: Update footer with NL KvK number
echo "Updating footer..."
sed -i '/<p>&copy; {{ date/a\            <p class="text-sm mt-2">NL KvK 83656480</p>' resources/views/components/footer.blade.php

# Fix 4: Make About page stats dynamic
echo "Updating About page with dynamic stats..."
sed -i 's/<div class="text-5xl font-bold text-indigo-600 mb-2">10M+<\/div>/<div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format(App\\Models\\Translation::count()) }}+<\/div>/g' resources/views/pages/about.blade.php
sed -i 's/<div class="text-5xl font-bold text-indigo-600 mb-2">5,000+<\/div>/<div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format(App\\Models\\User::count()) }}+<\/div>/g' resources/views/pages/about.blade.php

# Fix 5: Update Blog links from # to route('blog')
echo "Fixing Blog links..."
sed -i 's/href="#"/href="{{ route('\''blog'\'') }}"/g' resources/views/pages/blog.blade.php

# Fix 6: Update copyright year to 2025 and add NL KvK
echo "Updating copyright years..."
find resources/views -name "*.blade.php" -type f -exec sed -i 's/&copy; 2024/\&copy; 2025/g' {} \;
find resources/views -name "*.blade.php" -type f -exec grep -l "&copy; 2025" {} \; | while read file; do
    if ! grep -q "NL KvK 83656480" "$file"; then
        sed -i '/&copy; 2025/a\            <p class="text-sm mt-2">NL KvK 83656480</p>' "$file"
    fi
done

# Clear caches
echo "Clearing caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo "=== All fixes applied successfully! ==="
echo ""
echo "Changes made:"
echo "✅ Fixed Enterprise Controller auth errors"
echo "✅ Fixed Complaint Controller auth errors"  
echo "✅ Added NL KvK 83656480 to footer"
echo "✅ Made About page statistics dynamic"
echo "✅ Fixed Blog placeholder links"
echo "✅ Updated all copyright years to 2025"
echo ""
echo "Backup files saved with .backup extension"
