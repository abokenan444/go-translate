<?php

$data = [
    'name' => 'Test User',
    'email' => 'test' . rand(1000, 9999) . '@example.com',
    'password' => 'password123'
];

$ch = curl_init('http://192.168.2.5:8001/api/mobile/auth/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

$result = json_decode($response, true);
if (isset($result['success']) && $result['success']) {
    echo "\n✓ Registration successful!\n";
    echo "Email: " . $result['user']['email'] . "\n";
    echo "Token: " . substr($result['token'], 0, 20) . "...\n";
} else {
    echo "\n✗ Registration failed!\n";
    if (isset($result['errors'])) {
        print_r($result['errors']);
    }
}
