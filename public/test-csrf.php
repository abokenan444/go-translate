<?php
echo 'Request Scheme: ' . (['REQUEST_SCHEME'] ?? 'not set') . '<br>';
echo 'HTTPS: ' . (['HTTPS'] ?? 'not set') . '<br>';
echo 'HTTP Host: ' . (['HTTP_HOST'] ?? 'not set') . '<br>';
echo 'Server Name: ' . (['SERVER_NAME'] ?? 'not set') . '<br>';
echo 'Is Secure: ' . ((!empty(['HTTPS']) && ['HTTPS'] !== 'off') ? 'YES' : 'NO') . '<br>';
