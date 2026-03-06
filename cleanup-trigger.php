<?php
/**
 * Direct Cleanup Trigger
 * Access this file via: /wp-content/plugins/local-business-interviews/cleanup-trigger.php?key=cleanup
 */

// Load WordPress
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

// Security check - verify key
$key = isset( $_GET['key'] ) ? $_GET['key'] : '';

if ( $key !== 'cleanup' ) {
    die( 'Access denied' );
}

// Check user is logged in and is an admin
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    die( 'You must be logged in as an admin to run this cleanup.' );
}

// Get all directory posts
$posts = get_posts( array(
    'post_type'      => 'directory',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'numberposts'    => -1,
) );

echo '<h2>Directory Cleanup</h2>';
echo '<p>Found ' . count( $posts ) . ' directory listings.</p>';
echo '<p>Deleting...</p>';
echo '<ul>';

$deleted = 0;

foreach ( $posts as $post ) {
    $title = $post->post_title;
    $result = wp_delete_post( $post->ID, true );
    
    if ( $result ) {
        echo '<li>✓ Deleted: ' . esc_html( $title ) . '</li>';
        $deleted++;
    } else {
        echo '<li>✗ Failed: ' . esc_html( $title ) . '</li>';
    }
}

echo '</ul>';
echo '<p><strong>Total deleted: ' . $deleted . ' listings</strong></p>';
echo '<p style="color: green;"><strong>✅ Cleanup complete! Your directory is now empty.</strong></p>';
echo '<p><a href="/directory/">View your empty directory →</a></p>';

// Mark cleanup as done so auto-cleanup won't run
update_option( 'lbi_cleanup_done_2026_03_05', true );
update_option( 'lbi_cleanup_count_2026_03_05', $deleted );

?>
