<?php
// Force LiteSpeed cache purge
header('X-LiteSpeed-Cache-Control: public, max-age=0');
header('X-LiteSpeed-Purge: *');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('X-LiteSpeed-Cache-Vary: 1');

// Also disable opcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Clear file cache
clearstatcache(true);

echo "✅ Cache purged!";
exit;
?>
