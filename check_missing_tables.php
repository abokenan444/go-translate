<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$resources_tables = [
    "cultures" => "Cultures",
    "translation_types" => "TranslationTypes", 
    "payment_gateways" => "PaymentGateways",
    "plan_comparisons" => "PlanComparisons",
    "coupons" => "Coupons",
    "plan_features" => "PlanFeatures",
    "footer_links" => "FooterLinks",
    "services" => "Services",
    "service_features" => "ServiceFeatures",
    "feature_categories" => "FeatureCategories",
    "use_cases" => "UseCases",
    "site_settings" => "SiteSettings",
    "seo_settings" => "SeoSettings",
    "theme_settings" => "ThemeSettings",
    "blog_posts" => "BlogPosts",
    "testimonials" => "Testimonials",
    "faqs" => "Faqs",
    "features" => "Features",
];

$existing = Schema::getTableListing();
$missing = [];

foreach ($resources_tables as $table => $resource) {
    if (!in_array("main.$table", $existing) && !in_array($table, $existing)) {
        $missing[] = $table;
        echo "MISSING: $table (Resource: $resource)\n";
    }
}

echo "\n\nTotal missing tables: " . count($missing) . "\n";
echo "Missing tables: " . implode(", ", $missing) . "\n";
