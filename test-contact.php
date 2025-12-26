#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ContactMessage;

$message = ContactMessage::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Subject',
    'type' => 'question',
    'message' => 'This is a test message from CLI',
    'status' => 'new',
    'priority' => 'medium',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'CLI Test',
]);

echo "Created contact message with ID: " . $message->id . "\n";
echo "Message count: " . ContactMessage::count() . "\n";
