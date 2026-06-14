<?php
header('Content-Type: text/plain');

if (function_exists('opcache_reset')) {
    $reset = opcache_reset();
    echo "OPCache reset status: " . ($reset ? "true" : "false") . "\n";
} else {
    echo "opcache_reset() is not available.\n";
}

$migrationDir = dirname(__DIR__) . '/database/migrations';
if (file_exists($migrationDir)) {
    $invalidated = 0;
    foreach (scandir($migrationDir) as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $migrationDir . '/' . $file;
        if (function_exists('opcache_invalidate')) {
            if (@opcache_invalidate($path, true)) {
                $invalidated++;
                echo "Invalidated migration: $file\n";
            }
        }
    }
    echo "Total migrations invalidated: $invalidated\n";
}

// Invalidate routes/web.php and routes/api.php
$webRoutes = dirname(__DIR__) . '/routes/web.php';
$apiRoutes = dirname(__DIR__) . '/routes/api.php';
if (function_exists('opcache_invalidate')) {
    opcache_invalidate($webRoutes, true);
    opcache_invalidate($apiRoutes, true);
    echo "Invalidated routes files.\n";
}
