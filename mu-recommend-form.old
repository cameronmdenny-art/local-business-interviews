<?php
/**
 * Must-Use Plugin: Ensure Recommend Form Displays
 * 
 * This runs before normal plugins and ensures the recommend form
 * always renders on the /recommend/ page, regardless of template issues.
 */

// Hook into the page rendering process as early as possible
add_filter( 'wp_footer', function() {
    // Only run on the recommend page
    if ( ! is_page() || is_admin() ) {
        return;
    }
    
    $post = get_post( get_the_ID() );
    if ( ! $post || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return;
    }
    
    // This page should display the form - check if it already has  
    // If the form is showing as shortcode text, replace it
    ob_start();
    // We'll output JavaScript to fix it
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find the shortcode text and replace with a message
        var content = document.querySelector('[class*="entry-content"]');
        if (content) {
            var text = content.innerText;
            if (text.includes('[lbi_recommend_form]')) {
                console.log('Shortcode not processing - form should render via template');
            }
        }
    });
    </script>
    <?php
}, 999 );

// More direct approach: hook into template_include with high priority
// This is the absolute final say on which template gets used
add_filter( 'template_include', function( $template ) {
    if ( is_admin() || ! is_page( ) ) {
        return $template;
    }
    
    $post = get_post( get_the_ID() );
    if ( ! $post ) {
        return $template;
    }
    
    // Check both possible slug variations
    if ( $post->post_name === 'recommend' || $post->post_name === 'recommend-a-business' ) {
        $custom = dirname( __FILE__ ) . '/page-recommend.php';
        if ( file_exists( $custom ) ) {
            return $custom;
        }
    }
    
    return $template;
}, 999 ); // Priority 999 = last, after all other filters
