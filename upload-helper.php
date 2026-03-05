<?php
/**
 * Simple Plugin Uploader
 * Upload cairde-header.php to your server
 * Usage: Run this in your browser at your hosting account
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plugin'])) {
    $target_dir = dirname(__FILE__) . '/wp-content/plugins/cairde-header/';
    
    // Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $target_file = $target_dir . 'cairde-header.php';
    
    if (move_uploaded_file($_FILES['plugin']['tmp_name'], $target_file)) {
        echo '<div style="background:#4caf50;color:white;padding:20px;margin:20px;border-radius:5px;">';
        echo '<h2>✅ SUCCESS!</h2>';
        echo '<p>Plugin uploaded to: ' . $target_file . '</p>';
        echo '<p><strong>Next step:</strong> Go to WordPress admin and activate the plugin</p>';
        echo '<p><a href="/wp-admin/plugins.php" style="color:white;text-decoration:underline;font-weight:bold;">Go to Plugins</a></p>';
        echo '</div>';
    } else {
        echo '<div style="background:#f44336;color:white;padding:20px;margin:20px;border-radius:5px;">';
        echo '<h2>❌ ERROR</h2>';
        echo '<p>Failed to upload file. Check permissions.</p>';
        echo '</div>';
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Cairde Header Plugin</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .container { background: #f5f5f5; padding: 30px; border-radius: 8px; }
        h1 { color: #333; }
        form { background: white; padding: 20px; border-radius: 5px; }
        input[type="file"] { padding: 10px; margin: 10px 0; }
        button { background: #2196F3; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0b7dda; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Upload Cairde Header Plugin</h1>
        
        <div class="info">
            <strong>Instructions:</strong>
            <ol>
                <li>Click "Choose File" and select: <code>cairde-header.php</code></li>
                <li>Click "Upload"</li>
                <li>Go to WordPress Plugins and activate it</li>
                <li>Visit your homepage!</li>
            </ol>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="plugin" accept=".php" required>
            <button type="submit">📤 Upload Plugin</button>
        </form>
    </div>
</body>
</html>
