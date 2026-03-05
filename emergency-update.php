<?php
// Minimal restore script - run via: curl https://site.com/wp-content/plugins/local-business-interviews/emergency-update.php

// Load WordPress minimal
define( 'WP_USE_THEMES', false );
define( 'SHORTINIT', true );
require( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

if ( ! function_exists( 'get_page_by_path' ) ) {
    die('WordPress not loaded');
}

// Update pages with shortcodes
$updates = array(
    13  => array( 'slug' => 'directory', 'content' => '[lbi_directory_form]' ),
    46  => array( 'slug' => 'recommend', 'content' => '[lbi_recommend_form]' ),
    10  => array( 'slug' => 'submit-interview', 'content' => '[lbi_interview_form]' ),
    21  => array( 'slug' => 'home', 'content' => '<p>Welcome to our Local Business Directory</p>' ),
);

echo "Starting restoration...\n";

foreach ( $updates as $post_id => $data ) {
    if ( wp_update_post( array(
        'ID'           => $post_id,
        'post_content' => $data['content']
    ) ) ) {
        echo "✓ Updated page {$data['slug']} (ID: $post_id)\n";
    }
}

// Clear cache
if ( function_exists( 'flush_rewrite_rules' ) ) {
    flush_rewrite_rules( false );
}

if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
    LiteSpeed_Cache_API::purge_all();
}

echo "✅ Done!\n";
?>
