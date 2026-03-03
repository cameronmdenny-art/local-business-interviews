<?php
/**
 * Emergency plugin folder delete script
 * Upload this to wp-content/ and visit it in browser
 */

// Delete directory recursively
function delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}

// Run the deletion
$plugin_dir = __DIR__ . '/plugins/local-business-interviews';
$deleted = false;

if (file_exists($plugin_dir)) {
    $deleted = delete_directory($plugin_dir);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Plugin Folder Delete</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; max-width: 600px; margin: 0 auto; }
        .success { color: #00a32a; font-size: 18px; font-weight: 600; }
        .error { color: #d63638; font-size: 18px; font-weight: 600; }
        .info { background: #f0f0f1; padding: 20px; border-radius: 4px; margin: 20px 0; }
        .button { display: inline-block; background: #2271b1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 3px; margin-top: 20px; }
        .button:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>Plugin Folder Delete</h1>
    
    <?php if ($deleted): ?>
        <p class="success">✅ Plugin folder successfully deleted!</p>
        <div class="info">
            <h3>Next Steps:</h3>
            <ol>
                <li>Go to WordPress Admin → Plugins → Add New</li>
                <li>Click "Upload Plugin"</li>
                <li>Select local-business-interviews.zip from your Desktop</li>
                <li>Click "Install Now"</li>
                <li>Click "Activate Plugin"</li>
            </ol>
            <p><strong>Then delete this file (delete-lbi-plugin.php) from the server.</strong></p>
        </div>
        <a href="../wp-admin/plugin-install.php" class="button">Go to WordPress Plugins</a>
    <?php elseif (!file_exists($plugin_dir)): ?>
        <p class="success">✅ Plugin folder doesn't exist (already deleted)</p>
        <div class="info">
            <p>You can now install the plugin via ZIP upload in WordPress admin.</p>
        </div>
        <a href="../wp-admin/plugin-install.php" class="button">Go to WordPress Plugins</a>
    <?php else: ?>
        <p class="error">✗ Failed to delete plugin folder</p>
        <p>Path: <?php echo esc_html($plugin_dir); ?></p>
        <p>The folder might have permission issues. Contact your hosting support.</p>
    <?php endif; ?>
    
</body>
</html>
