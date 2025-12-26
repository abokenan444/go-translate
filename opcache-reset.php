<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully\n";
} else {
    echo "OPcache not enabled\n";
}

if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    echo "OPcache status: " . ($status['opcache_enabled'] ? 'enabled' : 'disabled') . "\n";
}
