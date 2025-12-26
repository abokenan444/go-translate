<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

$smtp = App\\Models\\SmtpSetting::find(1);
if ($smtp) {
    $smtp->password = 'Shatha-1992';
    $smtp->save();
    echo 'Password updated: ' . $smtp->username;
} else {
    echo 'Not found';
}
