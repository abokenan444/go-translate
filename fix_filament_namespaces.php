<?php
// Fix namespaces for Filament Admin Resources

$files = [
    'app/Filament/Admin/Resources/GovernmentVerificationResource.php',
    'app/Filament/Admin/Resources/GovernmentVerificationResource/Pages/ListGovernmentVerifications.php',
    'app/Filament/Admin/Resources/GovernmentVerificationResource/Pages/ViewGovernmentVerification.php',
    'app/Filament/Admin/Resources/GovernmentVerificationResource/Pages/EditGovernmentVerification.php',
    'app/Filament/Admin/Resources/SubscriptionPlanResource.php',
    'app/Filament/Admin/Resources/SubscriptionPlanResource/Pages/ListSubscriptionPlans.php',
    'app/Filament/Admin/Resources/SubscriptionPlanResource/Pages/CreateSubscriptionPlan.php',
    'app/Filament/Admin/Resources/SubscriptionPlanResource/Pages/EditSubscriptionPlan.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace(
            'App\\Filament\\Resources',
            'App\\Filament\\Admin\\Resources',
            $content
        );
        file_put_contents($file, $content);
        echo "Fixed: $file\n";
    } else {
        echo "Not found: $file\n";
    }
}

echo "\nDone!\n";
