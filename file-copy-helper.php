<?php
/**
 * File Copy Helper - Run this to deploy the mu-plugins
 * 
 * Upload to /public_html/ via any method, then visit:
 * https://ivory-lark-138468.hostingersite.com/file-copy-helper.php?key=local_legends_2026
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'local_legends_2026') {
    die('Invalid key');
}

// Define source and destination
$files = [
    '/tmp/mu-cairde-header-complete.php' => dirname(__FILE__) . '/mu-cairde-header-complete.php',
    '/tmp/mu-local-legend-footer.php' => dirname(__FILE__) . '/mu-local-legend-footer.php',
];

echo '<h1>Local Legend Stories - File Copy Helper</h1>';
echo '<p>This tool will copy the mu-plugin files to their correct locations.</p>';

// Try to copy files from temp if they exist
if (isset($_GET['copy'])) {
    foreach ($files as $source => $dest) {
        if (file_exists($source)) {
            if (copy($source, $dest)) {
                echo '<p style="color: green;">✓ Copied: ' . basename($dest) . '</p>';
            } else {
                echo '<p style="color: red;">✗ Failed to copy: ' . basename($dest) . '</p>';
            }
        }
    }
}

// Check current status
echo '<h2>Current Status:</h2>';
echo '<ul>';
foreach ($files as $source => $dest) {
    $exists = file_exists($dest);
    $status = $exists ? '✓ Active' : '✗ Missing';
    $color = $exists ? 'green' : 'red';
    echo '<li style="color: ' . $color . ';">' . basename($dest) . ' - ' . $status . '</li>';
}
echo '</ul>';

// If both exist, clear cache
if (file_exists(dirname(__FILE__) . '/mu-cairde-header-complete.php') && 
    file_exists(dirname(__FILE__) . '/mu-local-legend-footer.php')) {
    echo '<h2>Files Deployed!</h2>';
    echo '<p>Clearing cache...</p>';
    // Try to clear LiteSpeed cache marker
    do_action('litespeed_purge_all');
    echo '<p style="color: green;">✓ Cache cleared. Changes should appear within 2 minutes.</p>';
    echo '<p><a href="/">Visit your site →</a></p>';
}

?>
