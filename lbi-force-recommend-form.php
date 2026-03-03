<?php
/**
 * Must-Use Plugin: Render Recommend Form Directly
 * 
 * This bypasses all WordPress shortcode/template processing
 * and outputs the form HTML directly on page load
 */

// Hook into wp_footer with priority 1 (very early)
add_action( 'wp_footer', function() {
    global $post;
    
    // Only on recommend page
    if ( ! is_page() || is_admin() ) {
        return;
    }
    
    if ( ! $post || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return;
    }
    
    // Output JavaScript to replace the shortcode text with a message
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var entryContent = document.querySelector('.entry-content');
        if (entryContent && entryContent.innerHTML.includes('[lbi_recommend_form]')) {
            // Mark that the form wasn't rendered
            console.log('Form shortcode not processed - initiating direct rendering');
            // If we got here, the must-use plugin will handle it server-side
        }
    });
    </script>
    <?php
}, 1 );

// More direct: modify the post before rendering
add_action( 'the_post', function( $post ) {
    if ( is_admin() ) {
        return;
    }
    
    // Only recommend page
    if ( ! is_page() || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return;
    }
    
    // If post has shortcode, try to process it
    if ( strpos( $post->post_content, '[lbi_recommend_form]' ) !== false ) {
        // First, ensure the forms class is available
        if ( ! class_exists( 'LBI_Forms' ) ) {
            $plugin_file = dirname( __DIR__ ) . '/local-business-interviews/includes/forms.php';
            if ( file_exists( $plugin_file ) ) {
                include_once $plugin_file;
            }
        }
        
        // If the class is now available, use it
        if ( class_exists( 'LBI_Forms' ) ) {
            $form_output = LBI_Forms::recommend_form_shortcode( array() );
            
            // If we got output, save it
            if ( ! empty( $form_output ) ) {
                // Update in-memory post
                $post->post_content = $form_output;
                
                // Also update database
                wp_update_post( array(
                    'ID'           => $post->ID,
                    'post_content' => $form_output,
                ) );
            }
        }
    }
}, 20 );

?>
