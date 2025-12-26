<?php

// Update admin password

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = 'admin@culturaltranslate.com';
$password = 'Yasser-591983';

echo "Updating Admin Password\n";
echo "=======================\n\n";

$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: {$email}\n";
    exit(1);
}

echo "Found user:\n";
echo "ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Role: {$user->role}\n\n";

// Update password
$user->password = \Hash::make($password);
$user->save();

echo "✅ Password updated successfully!\n";
echo "Email: {$email}\n";
echo "New Password: {$password}\n\n";

echo "You can now login at: https://admin.culturaltranslate.com/admin\n";
