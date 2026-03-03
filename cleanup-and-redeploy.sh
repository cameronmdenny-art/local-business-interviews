#!/bin/bash
# Delete recommend page from database and redeploy plugin

HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"

# Use curl to call a script that deletes the page
# Since we can't directly access MySQL, we'll create a cleanup script

cat > /tmp/cleanup-recommend.php << 'PHP_EOF'
<?php
// Load WordPress
require_once('/home/u300002008/public_html/wp-load.php');

// Delete the recommend page by post_name
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_name = 'recommend' AND post_type = 'page'" );

echo "Recommend page cleaned up. It will be recreated on next site load.";
?>
PHP_EOF

# Upload cleanup script
echo "Uploading cleanup script..."
curl -s --ftp-create-dirs \
    -T /tmp/cleanup-recommend.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$HOST/public_html/wp-content/plugins/local-business-interviews/cleanup-recommend.php"

# Clean up
rm /tmp/cleanup-recommend.php

echo "Cleanup script deployed."
echo "Now redeploy the main plugin file..."
