<?php

return [
    'active_key_id' => env('MEM_KEY_ACTIVE_ID', 'v1'),
    'keys' => [
        'v1' => env('MEM_KEY_V1'),
        'v2' => env('MEM_KEY_V2'),
    ],
    // Algorithm settings
    'cipher' => 'aes-256-gcm',
];
