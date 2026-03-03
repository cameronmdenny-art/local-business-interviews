<?php
/**
 * Auto-Setup: Create Recommend Page
 * Self-executing, self-destructing WordPress setup script
 * 
 * This script:
 * 1. Loads WordPress  
 * 2. Creates the "Recommend a Business" page
 * 3. Verifies creation
 * 4. Deletes itself from the server
 */

// Prevent direct file access outside WordPress
if ( ! defined( 'ABSPATH' ) ) {
    // Load WordPress
    $wp_root = dirname( dirname( dirname( __FILE__ ) ) );
    
    if ( ! file_exists( $wp_root . '/wp-load.php' ) ) {
        die( json_encode( array( 'error' => 'WordPress not found' ) ) );
    }
    
    require_once( $wp_root . '/wp-load.php' );
}

header( 'Content-Type: application/json' );

// Start output buffer to capture errors
ob_start();

try {
    // Safety check: page should be empty on request
    if ( empty( $_GET['init'] ) ) {
        throw new Exception( 'Missing initialization parameter' );
    }

    // Verify WordPress is loaded
    if ( ! function_exists( 'wp_insert_post' ) ) {
        throw new Exception( 'WordPress not properly loaded' );
    }

    // Check if page already exists
    $existing = get_posts( array(
        'post_type'      => 'page',
        'post_name'      => 'recommend',
        'numberposts'    => 1,
        'post_status'    => array( 'publish', 'draft', 'pending' ),
    ) );

    $result = array();

    if ( ! empty( $existing ) ) {
        $result['status'] = 'exists';
        $result['message'] = 'Page already exists';
        $result['page_id'] = $existing[0]->ID;
    } else {
        // Create the page
        $page_id = wp_insert_post( array(
            'post_title'   => 'Recommend a Business',
            'post_content' => '',
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_name'    => 'recommend',
        ), true );

        if ( is_wp_error( $page_id ) ) {
            throw new Exception( 'Failed to create page: ' . $page_id->get_error_message() );
        }

        // Flush rewrite rules
        flush_rewrite_rules();

        // Verify page was created
        $verify = get_post( $page_id );
        if ( ! $verify ) {
            throw new Exception( 'Page creation verification failed' );
        }

        $result['status'] = 'created';
        $result['message'] = 'Page successfully created';
        $result['page_id'] = $page_id;
        $result['url'] = get_permalink( $page_id );
    }

    // Try to delete this file
    $this_file = __FILE__;
    if ( file_exists( $this_file ) && is_writable( dirname( $this_file ) ) ) {
        @unlink( $this_file );
        $result['script_deleted'] = true;
    } else {
        $result['script_deleted'] = false;
        $result['script_path'] = $this_file;
        $result['note'] = 'Please manually delete: ' . $this_file;
    }

    $result['success'] = true;

} catch ( Exception $e ) {
    // Clear output buffer to prevent garbage in JSON
    ob_end_clean();
    
    http_response_code( 500 );
    echo json_encode( array(
        'success' => false,
        'error'   => $e->getMessage(),
        'trace'   => WP_DEBUG ? $e->getTraceAsString() : null,
    ) );
    exit;
}

// Clear output buffer and return JSON
ob_end_clean();
echo json_encode( $result );
exit;
