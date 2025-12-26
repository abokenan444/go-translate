<?php
// Update app/Http/Kernel.php to add internal.admin middleware alias

$kernelPath = __DIR__ . '/app/Http/Kernel.php';

if (!file_exists($kernelPath)) {
    echo "❌ Kernel.php not found at: $kernelPath\n";
    exit(1);
}

$content = file_get_contents($kernelPath);

// Check if already added
if (strpos($content, "'internal.admin'") !== false) {
    echo "✅ Middleware alias already exists\n";
    exit(0);
}

// Find $middlewareAliases and add the entry
$pattern = '/protected\s+\$middlewareAliases\s*=\s*\[/';

if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
    $insertPos = $matches[0][1] + strlen($matches[0][0]);
    
    $middlewareEntry = "\n        'internal.admin' => \\App\\Http\\Middleware\\EnsureInternalAdmin::class,";
    
    $before = substr($content, 0, $insertPos);
    $after = substr($content, $insertPos);
    
    $newContent = $before . $middlewareEntry . $after;
    
    file_put_contents($kernelPath, $newContent);
    echo "✅ Middleware alias added successfully\n";
} else {
    echo "⚠️ Could not find \$middlewareAliases in Kernel.php\n";
    echo "Please add manually:\n";
    echo "'internal.admin' => \\App\\Http\\Middleware\\EnsureInternalAdmin::class,\n";
}
