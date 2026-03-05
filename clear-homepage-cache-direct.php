<?php
// Clear LiteSpeed Cache - Direct Method
error_reporting(0);

$success = [];
$failed = [];

// Remove cache files
$cache_file = '/public_html/wp-content/cache/8ed_';
if (@unlink($cache_file)) {
    $success[] = "Deleted old homepage cache";
}

// Try to use WordPress cache functions
if (file_exists('/public_html/wp-load.php')) {
    require_once('/public_html/wp-load.php');
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        $success[] = "Flushed WordPress object cache";
    }
    if (function_exists('litespeed_purge_all')) {
        do_action('litespeed_purge_all');
        $success[] = "Purged LiteSpeed full cache";
    }
}

echo "<!DOCTYPE html><html><body>";
echo "<h1>Cache Clear Status</h1>";
echo "<h2>Success:</h2><ul>";
foreach ($success as $s) {
    echo "<li>✅ $s</li>";
}
echo "</ul>";

if (!empty($failed)) {
    echo "<h2>Failed:</h2><ul>";
    foreach ($failed as $f) {
        echo "<li>❌ $f</li>";
    }
    echo "</ul>";
}

echo "<p><strong>Refresh the homepage now:</strong> <a href=\"https://ivory-lark-138468.hostingersite.com\">https://ivory-lark-138468.hostingersite.com</a></p>";
echo "</body></html>";
?>
