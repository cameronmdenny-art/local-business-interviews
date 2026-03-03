#!/bin/bash

# Deploy recommend page setup as must-use plugin
set -e

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"
REMOTE_PATH="/public_html/wp-content/mu-plugins"

# Create the must-use plugin file
cat > /tmp/lbi-recommend-setup.php << 'PLUGIN_EOF'
<?php
/**
 * Plugin Name: LBI Recommend Page Setup
 * Description: Auto-creates the recommend page if it doesn't exist
 */

// Auto-create recommend page on WordPress init
add_action(
    'init',
    function() {
        // Only run if recommend page doesn't exist
        if ( get_page_by_path( 'recommend', OBJECT, 'page' ) ) {
            return;
        }

        // Create the recommend page
        wp_insert_post(
            array(
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_title'   => 'Recommend a Business',
                'post_content' => '[lbi_recommend_form]',
                'post_author'  => 1,
                'post_name'    => 'recommend',
            ),
            false
        );

        // Flush rewrite rules
        flush_rewrite_rules( false );
    },
    1
);
PLUGIN_EOF

echo "📦 Uploading must-use plugin via FTP..."

# Create remote directory and upload file
curl -s --ftp-create-dirs \
    -T /tmp/lbi-recommend-setup.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST${REMOTE_PATH}/lbi-recommend-setup.php"

# Clean up
rm /tmp/lbi-recommend-setup.php

echo "✅ Must-use plugin deployed!"
echo "The recommend page will be created automatically when you load your site."
echo ""
echo "🔗 Check: https://ivory-lark-138468.hostingersite.com/recommend/"
