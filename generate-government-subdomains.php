#!/usr/bin/env php
<?php

/**
 * Government Subdomain Generator Script
 * 
 * Generates Nginx/Apache configuration or /etc/hosts entries
 * for all country-specific government subdomains
 * 
 * Patterns supported:
 * - {country}-gov.culturaltranslate.com
 * - gov-{country}.culturaltranslate.com
 * 
 * Usage:
 * php generate-government-subdomains.php --type=nginx
 * php generate-government-subdomains.php --type=apache
 * php generate-government-subdomains.php --type=hosts
 * php generate-government-subdomains.php --type=list
 */

// All countries (ISO 3166-1 alpha-2)
$countries = [
    // G20 Countries
    'US' => 'United States',
    'CA' => 'Canada',
    'MX' => 'Mexico',
    'BR' => 'Brazil',
    'AR' => 'Argentina',
    'GB' => 'United Kingdom',
    'DE' => 'Germany',
    'FR' => 'France',
    'IT' => 'Italy',
    'ES' => 'Spain',
    'RU' => 'Russia',
    'TR' => 'Turkey',
    'SA' => 'Saudi Arabia',
    'ZA' => 'South Africa',
    'IN' => 'India',
    'CN' => 'China',
    'JP' => 'Japan',
    'KR' => 'South Korea',
    'ID' => 'Indonesia',
    'AU' => 'Australia',
    
    // Middle East & North Africa
    'AE' => 'United Arab Emirates',
    'QA' => 'Qatar',
    'KW' => 'Kuwait',
    'BH' => 'Bahrain',
    'OM' => 'Oman',
    'JO' => 'Jordan',
    'LB' => 'Lebanon',
    'EG' => 'Egypt',
    'MA' => 'Morocco',
    'TN' => 'Tunisia',
    'DZ' => 'Algeria',
    'IQ' => 'Iraq',
    'SY' => 'Syria',
    'YE' => 'Yemen',
    'PS' => 'Palestine',
    'IL' => 'Israel',
    
    // Europe
    'NL' => 'Netherlands',
    'BE' => 'Belgium',
    'CH' => 'Switzerland',
    'AT' => 'Austria',
    'SE' => 'Sweden',
    'NO' => 'Norway',
    'DK' => 'Denmark',
    'FI' => 'Finland',
    'PL' => 'Poland',
    'CZ' => 'Czech Republic',
    'GR' => 'Greece',
    'PT' => 'Portugal',
    'IE' => 'Ireland',
    
    // Asia Pacific
    'SG' => 'Singapore',
    'MY' => 'Malaysia',
    'TH' => 'Thailand',
    'VN' => 'Vietnam',
    'PH' => 'Philippines',
    'NZ' => 'New Zealand',
    'PK' => 'Pakistan',
    'BD' => 'Bangladesh',
    'LK' => 'Sri Lanka',
    
    // Africa
    'NG' => 'Nigeria',
    'KE' => 'Kenya',
    'GH' => 'Ghana',
    'ET' => 'Ethiopia',
    
    // Add more as needed...
];

// Parse command line arguments
$options = getopt('', ['type:']);
$type = $options['type'] ?? 'list';

// Base domain
$baseDomain = 'culturaltranslate.com';
$documentRoot = '/var/www/culturaltranslate-dev/public'; // Adjust as needed

echo "=================================================\n";
echo "Government Subdomain Generator for CulturalTranslate\n";
echo "=================================================\n";
echo "Total countries: " . count($countries) . "\n";
echo "Output type: {$type}\n";
echo "=================================================\n\n";

switch ($type) {
    case 'nginx':
        generateNginxConfig($countries, $baseDomain, $documentRoot);
        break;
        
    case 'apache':
        generateApacheConfig($countries, $baseDomain, $documentRoot);
        break;
        
    case 'hosts':
        generateHostsFile($countries, $baseDomain);
        break;
        
    case 'list':
    default:
        generateList($countries, $baseDomain);
        break;
}

function generateNginxConfig(array $countries, string $baseDomain, string $documentRoot): void
{
    echo "# Nginx Configuration for Government Subdomains\n";
    echo "# Add this to your Nginx server block or create separate files\n\n";
    
    foreach ($countries as $code => $name) {
        $code = strtolower($code);
        $subdomain1 = "{$code}-gov.{$baseDomain}";
        $subdomain2 = "gov-{$code}.{$baseDomain}";
        
        echo "# {$name}\n";
        echo "server {\n";
        echo "    listen 80;\n";
        echo "    listen [::]:80;\n";
        echo "    server_name {$subdomain1} {$subdomain2};\n";
        echo "    root {$documentRoot};\n";
        echo "    index index.php;\n\n";
        echo "    location / {\n";
        echo "        try_files \$uri \$uri/ /index.php?\$query_string;\n";
        echo "    }\n\n";
        echo "    location ~ \\.php$ {\n";
        echo "        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;\n";
        echo "        fastcgi_index index.php;\n";
        echo "        include fastcgi_params;\n";
        echo "        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n";
        echo "    }\n";
        echo "}\n\n";
    }
}

function generateApacheConfig(array $countries, string $baseDomain, string $documentRoot): void
{
    echo "# Apache Configuration for Government Subdomains\n";
    echo "# Add this to your Apache virtual hosts configuration\n\n";
    
    foreach ($countries as $code => $name) {
        $code = strtolower($code);
        $subdomain1 = "{$code}-gov.{$baseDomain}";
        $subdomain2 = "gov-{$code}.{$baseDomain}";
        
        echo "# {$name}\n";
        echo "<VirtualHost *:80>\n";
        echo "    ServerName {$subdomain1}\n";
        echo "    ServerAlias {$subdomain2}\n";
        echo "    DocumentRoot {$documentRoot}\n\n";
        echo "    <Directory {$documentRoot}>\n";
        echo "        AllowOverride All\n";
        echo "        Require all granted\n";
        echo "    </Directory>\n\n";
        echo "    ErrorLog \${APACHE_LOG_DIR}/{$code}-gov-error.log\n";
        echo "    CustomLog \${APACHE_LOG_DIR}/{$code}-gov-access.log combined\n";
        echo "</VirtualHost>\n\n";
    }
}

function generateHostsFile(array $countries, string $baseDomain): void
{
    echo "# /etc/hosts entries for local development\n";
    echo "# Add these lines to your /etc/hosts file (or C:\\Windows\\System32\\drivers\\etc\\hosts on Windows)\n\n";
    
    foreach ($countries as $code => $name) {
        $code = strtolower($code);
        echo "127.0.0.1    {$code}-gov.{$baseDomain}\n";
        echo "127.0.0.1    gov-{$code}.{$baseDomain}\n";
    }
    
    echo "\n# IPv6\n";
    foreach ($countries as $code => $name) {
        $code = strtolower($code);
        echo "::1          {$code}-gov.{$baseDomain}\n";
        echo "::1          gov-{$code}.{$baseDomain}\n";
    }
}

function generateList(array $countries, string $baseDomain): void
{
    echo "List of Government Subdomains:\n\n";
    
    foreach ($countries as $code => $name) {
        $code_lower = strtolower($code);
        echo str_pad($name, 30) . " | {$code_lower}-gov.{$baseDomain} | gov-{$code_lower}.{$baseDomain}\n";
    }
    
    echo "\n\nTotal subdomains: " . (count($countries) * 2) . " (2 patterns per country)\n";
}

echo "\n=================================================\n";
echo "Generation Complete!\n";
echo "=================================================\n";
