<?php
/**
 * Direct Database Update Script
 * This updates WordPress pages directly in the database
 */

// Path to WordPress  
$wp_path = '/home/u894563484/domains/ivory-lark-138468.hostingersite.com';

// Load WordPress core
require_once( $wp_path . '/wp-load.php' );

// Update pages  
$pages_to_update = array(
    // ID => Content
    13  => '[lbi_directory_form]',  // directory page
    46  => '[lbi_recommend_form]',  // recommend page  
    10  => '[lbi_interview_form]',  // submit-interview page
    21  => '<p>Welcome</p>',        // home page
);

echo "=== PAGE CONTENT UPDATE ===\n\n";

global $wpdb;

foreach ( $pages_to_update as $page_id => $content ) {
    $result = $wpdb->update(
        $wpdb->posts,
        array( 'post_content' => $content ),
        array( 'ID' => $page_id ),
        array( '%s' ),
        array( '%d' )
    );
    
    if ( $result !== false ) {
        echo "✓ Updated page $page_id\n";
    } else {
        echo "✗ Failed to update page $page_id: " . $wpdb->last_error . "\n";
    }
}

// Flush cache
if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
    echo "✓ Flushed WordPress cache\n";
}

if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
    LiteSpeed_Cache_API::purge_all();
    echo "✓ Purged LiteSpeed cache\n";
}

echo "\n✅ UPDATE COMPLETE\n";
?>
