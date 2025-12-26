#!/bin/bash
cd /var/www/cultural-translate-platform

echo "=== Creating backups ==="
cp app/Http/Controllers/EnterpriseSubscriptionController.php app/Http/Controllers/EnterpriseSubscriptionController.php.backup
cp app/Http/Controllers/ComplaintController.php app/Http/Controllers/ComplaintController.php.backup

echo "=== Fixing EnterpriseSubscriptionController ==="
sed -i "s/auth()->id()/auth()->user()?->id/g" app/Http/Controllers/EnterpriseSubscriptionController.php

echo "=== Fixing ComplaintController ==="
sed -i "s/auth()->id()/auth()->user()?->id/g" app/Http/Controllers/ComplaintController.php

echo "=== Updating footer ==="
if ! grep -q "NL KvK 83656480" resources/views/components/footer.blade.php; then
    sed -i '/<p>&copy; {{ date/a\            <p class="text-sm mt-2">NL KvK 83656480</p>' resources/views/components/footer.blade.php
fi

echo "=== Updating About page stats ==="
sed -i 's/<div class="text-5xl font-bold text-indigo-600 mb-2">10M+<\/div>/<div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format(App\\\\Models\\\\Translation::count()) }}+<\/div>/g' resources/views/pages/about.blade.php
sed -i 's/<div class="text-5xl font-bold text-indigo-600 mb-2">5,000+<\/div>/<div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format(App\\\\Models\\\\User::count()) }}+<\/div>/g' resources/views/pages/about.blade.php

echo "=== Fixing Blog links ==="
sed -i "s/href=\"#\"/href=\"{{ route('blog') }}\"/g" resources/views/pages/blog.blade.php

echo "=== Updating copyright years ==="
find resources/views -name '*.blade.php' -type f -exec sed -i 's/&copy; 2024/\&copy; 2025/g' {} \;

echo "=== Clearing caches ==="
php artisan cache:clear
php artisan view:clear
php artisan config:clear

echo "=== ??? All fixes applied successfully! ==="