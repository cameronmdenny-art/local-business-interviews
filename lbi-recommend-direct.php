<?php
/**
 * Must-Use Plugin: Direct Recommend Form Rendering
 * 
 * Place this in /wp-content/mu-plugins/lbi-recommend-direct.php
 * This bypasses all template and shortcode processing issues.
 */

// Run this very early, before theme rendering
add_filter( 'the_post', function( $post ) {
    // Only target the recommend page
    if ( is_admin() || ! is_main_query() ) {
        return $post;
    }
    
    if ( ! is_page() || get_the_ID() !== $post->ID ) {
        return $post;
    }
    
    // Check if this is the recommend page
    if ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) {
        return $post;
    }
    
    // If the content contains the shortcode, leave it - the the_content filter will handle it
    // But ensure do_shortcode is definitely applied
    return $post;
} );

// Make absolutely sure the shortcode is processed
add_filter( 'the_content', function( $content ) {
    if ( ! is_page() || is_admin() ) {
        return $content;
    }
    
    $post = get_post();
    if ( ! $post || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return $content;
    }
    
    // If content contains [lbi_recommend_form], ensure it's processed
    if ( strpos( $content, '[lbi_recommend_form]' ) !== false ) {
        // Remove any wpautop that might have wrapped the shortcode in <p> tags
        $content = preg_replace( '/<p>\s*\[lbi_recommend_form\]\s*<\/p>/i', '[lbi_recommend_form]', $content );
        
        // Now process it
        $content = do_shortcode( $content );
    }
    
    return $content;
}, 5 ); // Very early priority

?>
