<?php
// Direct WordPress admin update script
define( 'WP_USE_THEMES', false );
require( '/home/u300002008/domains/ivory-lark-138468.hostingersite.com/public_html/wp-load.php' );

if ( ! current_user_can( 'manage_options' ) && ! defined( 'WP_CLI' ) ) {
    wp_die( 'Access denied' );
}

// Update WordPress settings to use static front page
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', 21 );  // Home page ID

// Flush rewrite rules
flush_rewrite_rules( false );

echo json_encode( array(
    'success' => true,
    'message' => 'Homepage set to static page',
    'show_on_front' => get_option( 'show_on_front' ),
    'page_on_front' => get_option( 'page_on_front' ),
) );
exit;
?>
