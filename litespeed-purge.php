<?php
/**
 * LiteSpeed Cache Purge Script
 * This script will purge the entire LiteSpeed cache
 */

// Don't cache this script itself
header('X-LiteSpeed-Cache-Control: no-cache');
header('Cache-Control: no-cache, no-store, must-revalidate');

echo "<h1>LiteSpeed Cache Purge</h1>";

// Method 1: Try LiteSpeed Cache plugin function
if (file_exists(__DIR__ . '/wp-load.php')) {
    require_once(__DIR__ . '/wp-load.php');
    
    echo "<p>WordPress loaded successfully.</p>";
    
    // Check if LiteSpeed Cache plugin is active
    if (class_exists('LiteSpeed\Purge')) {
        echo "<p>✅ LiteSpeed Cache plugin found!</p>";
        try {
            // Purge all
            do_action('litespeed_purge_all');
            echo "<p>✅ Cache purged via litespeed_purge_all action</p>";
        } catch (Exception $e) {
            echo "<p>⚠️  Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>⚠️  LiteSpeed Cache plugin not found</p>";
    }
} else {
    echo "<p>❌ Could not load WordPress</p>";
}

// Method 2: Send purge header anyway
header('X-LiteSpeed-Purge: *');
echo "<p>✅ X-LiteSpeed-Purge: * header sent</p>";

echo "<hr>";
echo "<p><a href='/'>View Homepage</a> (wait 5 seconds after purge)</p>";
echo "<p><em>Refreshing page in 3 seconds...</em></p>";
echo "<script>setTimeout(() => window.location.href='/?fresh=" . time() . "', 3000);</script>";
?>
