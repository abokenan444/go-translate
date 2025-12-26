<?php
// Test registration API
$url = 'http://localhost:8001/register';
$testData = [
    'name' => 'Test User ' . time(),
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'account_type' => 'customer',
    'terms' => '1'
];

echo "Testing Registration...\n";
echo "Email: " . $testData['email'] . "\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Redirect URL: " . $redirectUrl . "\n";

// Extract response body
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);

if ($httpCode == 200 || $httpCode == 302) {
    echo "\n✓ Registration appears successful!\n";
    if (strpos($redirectUrl, 'dashboard') !== false) {
        echo "✓ Redirected to dashboard!\n";
    } elseif (strpos($redirectUrl, 'register') !== false) {
        echo "✗ Redirected back to register page - check for errors\n";
    }
} else {
    echo "\n✗ Registration failed\n";
    echo "Response: " . substr($body, 0, 500) . "\n";
}
