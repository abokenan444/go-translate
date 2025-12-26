<?php

// Minimal router for PHP built-in server.
// Usage: php -S 127.0.0.1:8001 server.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');

// Serve existing files directly.
$file = __DIR__ . '/public' . $uri;
if ($uri !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/public/index.php';
