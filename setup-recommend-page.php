<?php
/**
 * One-time setup script to create the Recommend page
 * 
 * 1. Upload this file to your server (e.g., in the plugin directory)
 * 2. Visit: https://yoursite.com/wp-content/plugins/local-business-interviews/setup-recommend-page.php
 * 3. You should see a success message
 * 4. Delete this file from your server
 */

// Load WordPress
require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/wp-load.php' );

// Check if user is logged in as admin
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied. You must be logged in as an administrator.' );
}

// Create the page
$page_id = wp_insert_post( array(
    'post_title'   => 'Recommend a Business',
    'post_content' => '',
    'post_type'    => 'page',
    'post_status'  => 'publish',
    'post_name'    => 'recommend',
) );

if ( is_wp_error( $page_id ) ) {
    wp_die( 'Error creating page: ' . $page_id->get_error_message() );
}

// Flush rewrite rules
flush_rewrite_rules();

?>
<!DOCTYPE html>
<html>
<head>
    <title>✅ Setup Complete</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        h1 { color: #155724; }
        .next-steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin: 20px 0;
        }
        .next-steps ol { margin: 0; padding-left: 20px; }
        .next-steps li { margin: 10px 0; }
        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="success">
        <h1>✅ Setup Complete!</h1>
        <p>The "Recommend a Business" page has been successfully created.</p>
    </div>

    <div class="next-steps">
        <h2>Next Steps:</h2>
        <ol>
            <li>Visit your form: <strong><a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">
                <?php echo esc_html( home_url( '/recommend/' ) ); ?>
            </a></strong></li>
            <li>Test submitting a recommendation</li>
            <li>Go back to <code>wp-content/plugins/local-business-interviews/</code></li>
            <li><strong>Delete this file</strong> (<code>setup-recommend-page.php</code>) from the server</li>
        </ol>
    </div>

    <p style="color: #666; font-size: 13px; margin-top: 40px;">
        ℹ️ For security, please delete this setup script after you're done. You only need to run it once.
    </p>
</body>
</html>
