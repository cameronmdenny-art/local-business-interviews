<?php
/**
 * Immediate Directory Cleanup
 * Deletes all test/demo directory listings when plugin loads
 * Auto-disables after running once
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Cleanup_Now {
    
    public static function maybe_cleanup() {
        // Check if cleanup has already been done
        $cleanup_done = get_option( 'lbi_cleanup_done_2026_03_05' );
        
        if ( $cleanup_done ) {
            return; // Already cleaned
        }
        
        // Get all directory posts
        global $wpdb;
        $posts = get_posts( array(
            'post_type'      => 'directory',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'numberposts'    => -1,
        ) );

        if ( empty( $posts ) ) {
            // No posts to delete
            update_option( 'lbi_cleanup_done_2026_03_05', true );
            return;
        }

        $deleted = 0;

        foreach ( $posts as $post ) {
            // Delete all post meta
            $wpdb->delete( $wpdb->postmeta, array( 'post_id' => $post->ID ) );
            
            // Delete all term relationships  
            $wpdb->delete( $wpdb->term_relationships, array( 'object_id' => $post->ID ) );
            
            // Delete the post
            wp_delete_post( $post->ID, true );
            
            $deleted++;
        }

        // Mark cleanup as done so it doesn't run again
        update_option( 'lbi_cleanup_done_2026_03_05', true );
        
        // Log the cleanup
        update_option( 'lbi_cleanup_count_2026_03_05', $deleted );
    }
}

// Run cleanup on admin_init (only in WordPress)
add_action( 'admin_init', array( 'LBI_Cleanup_Now', 'maybe_cleanup' ), 1 );
