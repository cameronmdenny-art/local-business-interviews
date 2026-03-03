<?php
/**
 * Direct Form Rendering Fix
 * Add this to functions.php or use as a standalone include
 * 
 * This PERMANENTL Y replaces the shortcode with actual form HTML
 * in the page post_content so shortcode processing issues don't matter
 */

function lbi_fix_recommend_form_rendering() {
    // Find recommend page
    $args = array(
        'post_type' => 'page',
        'name' => 'recommend', // Try exact match first
        'posts_per_page' => 1,
    );
    $posts = get_posts( $args );
    
    // If not found, try alternate slug
    if ( empty( $posts ) ) {
        $args['name'] = 'recommend-a-business';
        $posts = get_posts( $args );
    }
    
    if ( empty( $posts ) ) {
        return 'Recommend page not found';
    }
    
    $page = $posts[0];
    
    // Check if it already has the rendered form
    if ( strpos( $page->post_content, 'lbi-recommend-shortcode-wrap' ) !== false ) {
        return 'Page already has rendered form. No action needed.';
    }
    
    // If it has the shortcode, render it and update the page
    if ( strpos( $page->post_content, '[lbi_recommend_form]' ) !== false ) {
        // Call the shortcode directly
        $form_html = do_shortcode( '[lbi_recommend_form]' );
        
        // Update the page content
        wp_update_post( array(
            'ID'           => $page->ID,
            'post_content' => $form_html,
        ) );
        
        return 'Page updated with rendered form!';
    }
    
    return 'Shortcode not found on page';
}

// Use this in admin or schedule it
if ( is_admin() && current_user_can( 'manage_options' ) && isset( $_GET['lbi_fix_recommend'] ) ) {
    $result = lbi_fix_recommend_form_rendering();
    wp_die( 'Result: ' . esc_html( $result ) );
}
