<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'js/*', 'css/*', 'government/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [
        '/^https:\/\/(?:[a-z0-9-]+\.)?culturaltranslate\.com$/',
        '/^https:\/\/government\.culturaltranslate\.com$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Length', 'X-JSON'],

    'max_age' => 86400,

    'supports_credentials' => true,

];
