<?php
// Quick test script for government portals
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n========================================\n";
echo "Government Portals Test\n";
echo "========================================\n\n";

$totalPortals = App\Models\GovernmentPortal::count();
echo "✓ Total Government Portals: {$totalPortals}\n\n";

echo "Sample Portals:\n";
echo str_repeat("-", 60) . "\n";
printf("%-6s %-25s %-15s %-8s\n", "Code", "Country", "Slug", "Active");
echo str_repeat("-", 60) . "\n";

App\Models\GovernmentPortal::orderBy('country_name')
    ->limit(20)
    ->get()
    ->each(function($portal) {
        printf("%-6s %-25s %-15s %-8s\n", 
            $portal->country_code,
            substr($portal->country_name, 0, 25),
            $portal->portal_slug,
            $portal->is_active ? 'Yes' : 'No'
        );
    });

echo str_repeat("-", 60) . "\n\n";

// Check Middle East portals
$middleEastPortals = App\Models\GovernmentPortal::whereIn('country_code', ['AE', 'SA', 'EG', 'JO', 'KW', 'QA'])
    ->get();

echo "Middle East Portals:\n";
foreach ($middleEastPortals as $portal) {
    echo "  • {$portal->country_code}: {$portal->portal_url}\n";
}

echo "\n";

// Check European portals
$europeanPortals = App\Models\GovernmentPortal::whereIn('country_code', ['NL', 'DE', 'FR', 'UK', 'ES', 'IT'])
    ->get();

echo "European Portals:\n";
foreach ($europeanPortals as $portal) {
    echo "  • {$portal->country_code}: {$portal->portal_url}\n";
}

echo "\n========================================\n";
echo "Test completed successfully!\n";
echo "========================================\n\n";
