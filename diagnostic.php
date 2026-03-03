<!DOCTYPE html>
<html>
<head>
    <title>Plugin Diagnostic</title>
    <style>body { font-family: monospace; padding: 20px; } .ok { color: green; } .fail { color: red; }</style>
</head>
<body>
    <h1>LBI Plugin Diagnostic</h1>
    <?php
    // Check if WordPress is loaded
    if (!defined('ABSPATH')) {
        require_once('../../../wp-load.php');
    }
    
    echo "<h2>File Status:</h2>";
    $plugin_file = __DIR__ . '/local-business-interviews.php';
    if (file_exists($plugin_file)) {
        echo "<p class='ok'>✓ Plugin file exists</p>";
        echo "<p>File size: " . filesize($plugin_file) . " bytes</p>";
        echo "<p>Last modified: " . date('Y-m-d H:i:s', filemtime($plugin_file)) . "</p>";
        
        // Check syntax
        $output = shell_exec('php -l ' . escapeshellarg($plugin_file) . ' 2>&1');
        if (strpos($output, 'No syntax errors') !== false) {
            echo "<p class='ok'>✓ No syntax errors</p>";
        } else {
            echo "<p class='fail'>✗ Syntax error: " . htmlspecialchars($output) . "</p>";
        }
    } else {
        echo "<p class='fail'>✗ Plugin file not found</p>";
    }
    
    echo "<h2>Class Status:</h2>";
    
    if (class_exists('LBI_Forms')) {
        echo "<p class='ok'>✓ LBI_Forms class loaded</p>";
        if (method_exists('LBI_Forms', 'init')) {
            echo "<p class='ok'>✓ LBI_Forms::init() method exists</p>";
        }
        if (method_exists('LBI_Forms', 'recommend_form_shortcode')) {
            echo "<p class='ok'>✓ LBI_Forms::recommend_form_shortcode() method exists</p>";
        }
    } else {
        echo "<p class='fail'>✗ LBI_Forms class NOT loaded</p>";
    }
    
    echo "<h2>Shortcode Status:</h2>";
    
    if (shortcode_exists('lbi_recommend_form')) {
        echo "<p class='ok'>✓ Shortcode registered</p>";
        
        // Try to execute it
        $output = do_shortcode('[lbi_recommend_form]');
        if (strpos($output,'[lbi_recommend_form') !== false) {
            echo "<p class='fail'>✗ Shortcode not processed (returned plain text)</p>";
        } else if (strpos($output, 'lbi-recommend-shortcode-wrap') !== false) {
            echo "<p class='ok'>✓ Shortcode executes and returns form HTML</p>";
            echo "<p>Form HTML length: " . strlen($output) . " chars</p>";
        } else {
            echo "<p class='fail'>✗ Shortcode returns unexpected output</p>";
            echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
        }
    } else {
        echo "<p class='fail'>✗ Shortcode NOT registered</p>";
    }
    
    echo "<h2>Plugin Status:</h2>";
    
    if (is_plugin_active('local-business-interviews/local-business-interviews.php')) {
        echo "<p class='ok'>✓ Plugin is active</p>";
    } else {
        echo "<p class='fail'>✗ Plugin is NOT active</p>";
    }
    
    echo "<h2>Active Plugins:</h2><ul>";
    foreach (get_option('active_plugins', []) as $plugin) {
        echo "<li>" . htmlspecialchars($plugin) . "</li>";
    }
    echo "</ul>";
    
    echo "<h2>PHP Info:</h2>";
    echo "<p>PHP Version: " . phpversion() . "</p>";
    echo "<p>OPcache Enabled: " . (function_exists('opcache_get_status') && opcache_get_status() ? 'Yes' : 'No') . "</p>";
    
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "<p class='ok'>✓ OPcache cleared</p>";
    }
    ?>
</body>
</html>
