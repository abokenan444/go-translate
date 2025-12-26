<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Mobile Contacts ===\n";
foreach (App\Models\MobileContact::all() as $c) {
    echo "ID: {$c->id} | user_id: {$c->user_id} | contact_user_id: " . ($c->contact_user_id ?? 'NULL') . " | name: {$c->name}\n";
}

echo "\n=== Mobile Notifications ===\n";
foreach (App\Models\MobileNotification::orderBy('id', 'desc')->take(10)->get() as $n) {
    echo "ID: {$n->id} | user_id: {$n->user_id} | type: {$n->type} | read: " . ($n->read_at ? 'yes' : 'no') . "\n";
    if ($n->type === 'incoming_call') {
        echo "   Data: " . json_encode($n->data) . "\n";
    }
}

echo "\n=== Users ===\n";
foreach (App\Models\User::all() as $u) {
    echo "ID: {$u->id} | name: {$u->name} | email: {$u->email}\n";
}
