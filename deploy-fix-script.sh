#!/bin/bash

# Direct Database Fix for Recommend Form
# This updates the page post_content with rendered form HTML  

echo "🔧 Preparing direct form rendering fix..."

cat > /tmp/fix-recommend-form.php << 'PHPEOF'
<?php
/**
 * Direct Fix: Render and save recommend form HTML
 * 
 * This determines if WordPress is properly installed and
 * directly updates the recommend page with rendered form
 */

// Try to load WordPress
$wp_load = dirname(__FILE__) . '/wp-load.php';

// Find WordPress by checking common paths
$search_paths = [
    dirname(__FILE__) . '/wp-load.php',
    dirname(dirname(__FILE__)) . '/wp-load.php',
    '/home/u300002008/public_html/wp-load.php',
];

$found = false;
foreach ($search_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $found = true;
        break;
    }
}

if (!$found) {
    die('Error: WordPress wp-load.php not found. Cannot proceed with form rendering.');
}

// Now we have WordPress loaded
echo "✅ WordPress loaded\n";

// Find recommend page
$page = get_page_by_path('recommend');
if (!$page) {
    $page = get_page_by_path('recommend-a-business');
}

if (!$page) {
    die('❌ Recommend page not found in database\n');
}

echo "✅ Found recommend page (ID: " . $page->ID . ")\n";

// Check if it already has the form
if (strpos($page->post_content, 'lbi-recommend-shortcode-wrap') !== false) {
    echo "✅ Form already rendered\n";
    exit(0);
}

// If it has the shortcode, process it
if (strpos($page->post_content, '[lbi_recommend_form]') !== false) {
    echo "📝 Shortcode found, processing...\n";
    
    // Process the shortcode
    $form_html = do_shortcode($page->post_content);
    
    if (empty($form_html)) {
        die('❌ Failed to process shortcode\n');
    }
    
    // Update the page
    $result = wp_update_post([
        'ID'           => $page->ID,
        'post_content' => $form_html,
    ]);
    
    if (is_wp_error($result)) {
        die('❌ Failed to update post: ' . $result->get_error_message() . "\n");
    }
    
    echo "✅ Page updated with form HTML\n";
    echo "✅ Fix complete!\n";
} else {
    echo "⚠️  Shortcode [lbi_recommend_form] not found on page\n";
}

?>
PHPEOF

echo "📤 Uploading fix script to server..."

# Upload to a temporary location on the server
curl -s --ftp-create-dirs \
     -T /tmp/fix-recommend-form.php \
     -u "u300002008.ivory-lark-138468.hostingersite.com:VinU9Y8kmNr!2l*0" \
     "ftp://185.164.108.209/public_html/fix-recommend-form.php"

echo "✅ Script uploaded"
echo "👉 Visit this URL to execute: https://ivory-lark-138468.hostingersite.com/fix-recommend-form.php"
echo ""
echo "⚠️  IMPORTANT: Delete /fix-recommend-form.php from server after running it!"

# Clean up
rm /tmp/fix-recommend-form.php
