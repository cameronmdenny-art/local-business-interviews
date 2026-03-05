<?php
/**
 * Plugin Name: Local Legend Master Override
 * Description: Ensures Local Legend Stories branding displays (disables all competing plugins)
 * Version: 1.0
 */

// Kill all Cairde output early
add_action( 'plugins_loaded', function() {
    // Remove competing headers/footers
    remove_all_actions( 'wp_head' );
    remove_all_actions( 'wp_body_open' );
    remove_all_filters( 'the_content' );
    remove_all_filters( 'wp_footer' );
}, 1 );

// Ensure Local Legend Stories header shows in wp_head
add_action( 'wp_head', function() {
    ?>
    <style id="local-legend-force-header-styles">
        /* Hide all Cairde branding */
        [class*="cairde"] { display: none !important; }
        [class*="luxury"] { display: none !important; }
        
        /* Ensure Local Legend header is visible */
        .local-legend-header { display: block !important; }
        .local-legend-footer { display: block !important; }
        
        body.has-local-legend-header { padding-top: 100px !important; }
    </style>
    <?php
}, 2 );

// Disable any Cairde form output
add_filter( 'wp_footer', function( $content ) { return ''; }, 1 );
