<?php
session_start();
echo 'Session ID: ' . session_id() . '<br>';
echo 'Session Data: ';
print_r($_SESSION);
echo '<br>Cookies: ';
print_r($_COOKIE);
echo '<br>HTTPS: ' . ($_SERVER['HTTPS'] ?? 'not set');
echo '<br>X-Forwarded-Proto: ' . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set');
