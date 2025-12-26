<?php
// Test legal pages directly
require __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Legal Views ===\n\n";

$views = [
    'legal.privacy' => 'Privacy Policy',
    'legal.sla' => 'SLA',
    'legal.disclaimer' => 'Legal Disclaimer',
    'legal.terms' => 'Terms of Service',
    'legal.pricing' => 'Pricing',
];

foreach ($views as $view => $name) {
    try {
        $content = view($view)->render();
        $size = strlen($content);
        echo "✅ $name ($view): " . round($size/1024, 2) . " KB\n";
    } catch (\Exception $e) {
        echo "❌ $name ($view): " . $e->getMessage() . "\n";
    }
}

echo "\n=== Routes Test ===\n\n";
$routes = [
    '/privacy-policy' => 'legal.privacy',
    '/sla' => 'legal.sla',
    '/legal-disclaimer' => 'legal.disclaimer',
    '/terms' => 'legal.terms',
    '/pricing-plans' => 'legal.pricing',
];

foreach ($routes as $uri => $name) {
    $route = app('router')->getRoutes()->match(
        \Illuminate\Http\Request::create($uri, 'GET')
    );
    echo "Route: $uri => " . $route->getName() . "\n";
}
