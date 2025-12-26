<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    '/api/v1/translate',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
    json_encode(['text' => 'Hello world', 'source' => 'en', 'target' => 'ar', 'tone' => 'formal'])
);

$response = $kernel->handle($request);
echo $response->getContent();
