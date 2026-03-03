#!/bin/bash
# Force delete and recreate the recommend page

HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"

# Create a script that forcefully deletes and recreates the page
cat > /tmp/force-recreate-recommend.php << 'EOF'
<?php
// Load WordPress with full initialization
require_once(__DIR__ . '/../../../../wp-load.php');

global $wpdb;

// Force delete any existing recommend pages
$wpdb->query("DELETE FROM " . $wpdb->posts . " WHERE post_name = 'recommend' AND post_type = 'page'");

// Create fresh page with shortcode
$inserted = wp_insert_post(array(
    'post_type'    => 'page',
    'post_status'  => 'publish',
    'post_title'   => 'Recommend a Business',
    'post_content' => '[lbi_recommend_form]',
    'post_name'    => 'recommend',
    'post_author'  => 1,
));

if (is_wp_error($inserted)) {
    echo json_encode(['success' => false, 'error' => $inserted->get_error_message()]);
} else {
    flush_rewrite_rules(false);
    echo json_encode(['success' => true, 'page_id' => $inserted, 'url' => get_permalink($inserted)]);
}
?>
EOF

echo "Uploading force-recreation script..."
curl -s --ftp-create-dirs \
    -T /tmp/force-recreate-recommend.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$HOST/public_html/wp-content/plugins/local-business-interviews/force-recreate-recommend.php"

rm /tmp/force-recreate-recommend.php

echo "✅ Script deployed"
echo "The page will be recreated when you access: https://ivory-lark-138468.hostingersite.com/wp-content/plugins/local-business-interviews/force-recreate-recommend.php"
