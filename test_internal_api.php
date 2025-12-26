<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$jsonData = json_encode([
    'text' => 'Hello world',
    'source' => 'en',
    'target' => 'ar'
]);

// Create internal request with JSON body
$request = Illuminate\Http\Request::create(
    '/api/v1/translate',
    'POST',
    [], // query params
    [], // cookies
    [], // files
    [
        'HTTP_ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json'
    ],
    $jsonData // content body
);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
