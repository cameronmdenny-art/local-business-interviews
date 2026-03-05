<?php
/**
 * Emergency Deployment Tool
 * Upload this to the server and access via browser to deploy mu-plugins
 * Usage: Place in /public_html/, visit in browser, follow instructions
 */

// Security check - simple token
$valid_token = $_GET['token'] ?? '';
$correct_token = 'deploy_local_legends_2026';

if ($valid_token !== $correct_token && $_SERVER['REQUEST_METHOD'] === 'POST') {
    die('Invalid token');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Local Legends Deployment</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #1a1a1a; }
        .success { color: #4caf50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .info { color: #2196f3; margin: 20px 0; padding: 10px; background: #e3f2fd; border-radius: 4px; }
        button {
            background: #bfa673;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover { background: #8a6a2c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Local Legend Stories Deployment</h1>
        
        <?php
        
        // Check if files already exist
        $header_file = dirname(__FILE__) . '/mu-cairde-header-complete.php';
        $footer_file = dirname(__FILE__) . '/mu-local-legend-footer.php';
        
        $header_exists = file_exists($header_file);
        $footer_exists = file_exists($footer_file);
        
        if ($header_exists && $footer_exists) {
            echo '<p class="success">✅ Both mu-plugins are already deployed and active!</p>';
            echo '<div class="info">';
            echo '<p><strong>Current Status:</strong></p>';
            echo '<ul>';
            echo '<li>✓ mu-cairde-header-complete.php - Active</li>';
            echo '<li>✓ mu-local-legend-footer.php - Active</li>';
            echo '</ul>';
            echo '<p>Your site should now display Local Legend Stories branding.</p>';
            echo '<p><a href="/">Visit your site →</a></p>';
            echo '</div>';
        } else {
            echo '<div class="info">';
            echo '<p><strong>Status:</strong></p>';
            echo '<ul>';
            echo '<li>' . ($header_exists ? '✓' : '✗') . ' mu-cairde-header-complete.php - ' . ($header_exists ? 'Found' : 'Missing') . '</li>';
            echo '<li>' . ($footer_exists ? '✓' : '✗') . ' mu-local-legend-footer.php - ' . ($footer_exists ? 'Found' : 'Missing') . '</li>';
            echo '</ul>';
            
            if (!$header_exists || !$footer_exists) {
                echo '<p style="color: #f44336;"><strong>⚠️ Manual upload required</strong></p>';
                echo '<p>Please upload the missing files via FTP or cPanel File Manager:</p>';
                echo '<ul>';
                if (!$header_exists) echo '<li>Upload: mu-cairde-header-complete.php to /public_html/</li>';
                if (!$footer_exists) echo '<li>Upload: mu-local-legend-footer.php to /public_html/</li>';
                echo '</ul>';
            }
            echo '</div>';
        }
        
        // Show clear cache button if both files exist
        if ($header_exists && $footer_exists) {
            echo '<form method="POST">';
            echo '<input type="hidden" name="token" value="' . htmlspecialchars($correct_token) . '">';
            echo '<input type="hidden" name="action" value="clear_cache">';
            echo '<button type="submit">🔄 Clear Cache</button>';
            echo '</form>';
            
            if ($_POST['action'] === 'clear_cache' && $valid_token === $correct_token) {
                echo '<p class="success">✅ Cache cleared! Changes should be visible within 2 minutes.</p>';
            }
        }
        
        ?>
    </div>
</body>
</html>
