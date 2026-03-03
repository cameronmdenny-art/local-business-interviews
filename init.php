<?php
/**
 * Direct database setup - Creates page via SQL query
 */

// Get WordPress directory
$wp_root = dirname( dirname( dirname( __FILE__ ) ) );

// Load WordPress
if ( ! file_exists( $wp_root . '/wp-load.php' ) ) {
    http_response_code( 500 );
    die( 'WordPress not found' );
}

require_once( $wp_root . '/wp-load.php' );

// Make sure database is accessible
global $wpdb;

// Check if page exists
$existing = $wpdb->get_var( 
    "SELECT ID FROM {$wpdb->posts} WHERE post_type='page' AND post_name='recommend' AND post_status='publish' LIMIT 1"
);

if ( $existing ) {
    echo json_encode( array(
        'success' => true,
        'status' => 'exists',
        'page_id' => $existing,
        'url' => get_permalink( $existing ),
        'message' => 'Page already exists'
    ) );
    exit;
}

// Get current time
$now = current_time( 'mysql' );

// Insert page directly
$inserted = $wpdb->insert(
    $wpdb->posts,
    array(
        'post_author'           => 1,
        'post_date'             => $now,
        'post_date_gmt'         => get_gmt_from_date( $now ),
        'post_content'          => '',
        'post_title'            => 'Recommend a Business',
        'post_excerpt'          => '',
        'post_status'           => 'publish',
        'comment_status'        => 'closed',
        'ping_status'           => 'closed',
        'post_password'         => '',
        'post_name'             => 'recommend',
        'to_ping'               => '',
        'pinged'                => '',
        'post_modified'         => $now,
        'post_modified_gmt'     => get_gmt_from_date( $now ),
        'post_content_filtered' => '',
        'post_parent'           => 0,
        'guid'                  => home_url( '/recommend/' ),
        'menu_order'            => 0,
        'post_type'             => 'page',
        'post_mime_type'        => '',
        'comment_count'         => 0,
    )
);

if ( ! $inserted ) {
    http_response_code( 500 );
    echo json_encode( array(
        'success' => false,
        'error' => $wpdb->last_error ?: 'Insert failed',
        'message' => 'Failed to create page'
    ) );
    exit;
}

// Get the inserted page ID
$page_id = $wpdb->insert_id;

// Flush rewrite rules
flush_rewrite_rules();

// Clean up - delete this file
$this_file = __FILE__;
@unlink( $this_file );

echo json_encode( array(
    'success' => true,
    'status' => 'created',
    'page_id' => $page_id,
    'url' => home_url( '/recommend/' ),
    'message' => 'Page successfully created'
) );
exit;
