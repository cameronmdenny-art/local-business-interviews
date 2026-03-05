<?php
/**
 * Quick diagnostic script to check MU plugin status
 * Upload this to public_html and visit it to see what's happening
 */

// Check if MU plugins directory exists
$mu_plugins_dir = dirname(__FILE__) . '/wp-content/mu-plugins/';
echo "<h2>MU Plugins Directory Status</h2>";
echo "<p><strong>Path:</strong> $mu_plugins_dir</p>";
echo "<p><strong>Exists:</strong> " . (is_dir($mu_plugins_dir) ? 'Yes' : 'No') . "</p>";

if (is_dir($mu_plugins_dir)) {
    echo "<h3>Files in mu-plugins:</h3>";
    $files = scandir($mu_plugins_dir);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $size = filesize($mu_plugins_dir . $file);
            echo "<li>$file (" . $size . " bytes)</li>";
        }
    }
    echo "</ul>";
    
    // Check if our specific file exists
    $our_plugin = $mu_plugins_dir . 'cairde-header-injector.php';
    if (file_exists($our_plugin)) {
        echo "<h3>cairde-header-injector.php contents (first 500 chars):</h3>";
        echo "<pre>" . htmlspecialchars(substr(file_get_contents($our_plugin), 0, 500)) . "</pre>";
    }
}

// Check if WordPress is loaded
if (file_exists(dirname(__FILE__) . '/wp-load.php')) {
    require_once(dirname(__FILE__) . '/wp-load.php');
    echo "<h2>WordPress Info</h2>";
    echo "<p><strong>WP Version:</strong> " . get_bloginfo('version') . "</p>";
    echo "<p><strong>Site URL:</strong> " . get_bloginfo('url') . "</p>";
    echo "<p><strong>Is Front Page:</strong> " . (is_front_page() ? 'Yes' : 'No') . "</p>";
}

// Self-delete after 60 seconds
if (time() > filemtime(__FILE__) + 60) {
    @unlink(__FILE__);
    echo "<p><em>This file will self-delete in 60 seconds.</em></p>";
}
?>
