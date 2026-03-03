<?php
/**
 * Set static homepage and disable blog posts index
 * Runs once, then deactivates itself
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );
}

// Load WordPress
require_once ABSPATH . 'wp-load.php';

// Only allow this script to run once per request
if ( defined( 'LBI_HOMEPAGE_SET' ) ) {
    exit( 'Already executed.' );
}

define( 'LBI_HOMEPAGE_SET', true );

// Set the static front page to ID 21 (Home page)
$home_page_id = 21;

// Update WordPress options
$updated = array();

// Set show_on_front to 'page' instead of 'posts'
if ( update_option( 'show_on_front', 'page' ) ) {
    $updated[] = 'show_on_front set to page';
}

// Set page_on_front to the Home page ID
if ( update_option( 'page_on_front', $home_page_id ) ) {
    $updated[] = "page_on_front set to $home_page_id";
}

// Flush rewrite rules to ensure routing works correctly
flush_rewrite_rules( false );
$updated[] = 'Rewrite rules flushed';

// Output success message
echo json_encode( array(
    'success'  => true,
    'message'  => 'Homepage configured successfully',
    'updates'  => $updated,
    'homepage' => get_home_url( null, '/' ),
) );

exit;
?>
