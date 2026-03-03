<?php
/**
 * Plugin Name: LBI Force Activate
 * Description: Forces the Local Business Interviews plugin to load and registers shortcodes
 * Version: 1.1
 */

// Suppress PHP warnings on frontend (keep errors visible in WP admin)
if ( ! is_admin() && ! defined( 'WP_CLI' ) ) {
    @ini_set( 'display_errors', 0 );
    @error_reporting( E_ERROR | E_PARSE );
}

// Mark plugin as loading FIRST to prevent WordPress from loading it again
if ( ! defined( 'LBI_PLUGIN_LOADED' ) ) {
    define( 'LBI_PLUGIN_LOADED', true );
}

// Define main plugin path
$main_plugin = WP_PLUGIN_DIR . '/local-business-interviews/local-business-interviews.php';

if ( file_exists( $main_plugin ) && ! class_exists( 'LBI_Forms' ) ) {
    // First, check for syntax errors
    $syntax_check = shell_exec( 'php -l ' . escapeshellarg( $main_plugin ) . ' 2>&1' );
    
    if ( strpos( $syntax_check, 'No syntax errors' ) === false ) {
        add_action( 'admin_notices', function() use ( $syntax_check ) {
            echo '<div class="error"><p><strong>LBI Plugin Error:</strong> Syntax error in plugin file:<br><code>' . esc_html( $syntax_check ) . '</code></p></div>';
        } );
        return;
    }
    
    // Load the main plugin file silently
    require_once $main_plugin;
} elseif ( ! file_exists( $main_plugin ) ) {
    add_action( 'admin_notices', function() use ( $main_plugin ) {
        echo '<div class="error"><p><strong>LBI Plugin Error:</strong> Main plugin file not found at: <code>' . esc_html( $main_plugin ) . '</code></p></div>';
    } );
}

// Add a filter to process the shortcode on the recommend page even if it's not rendering
add_filter( 'the_content', function( $content ) {
    if ( ! is_page() || is_admin() ) {
        return $content;
    }
    
    $post = get_post();
    if ( ! $post || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return $content;
    }
    
    // Check if shortcode exists
    if ( strpos( $content, '[lbi_recommend_form]' ) !== false ) {
        // Force do_shortcode
        $content = do_shortcode( $content );
        
        // If still has shortcode (means it wasn't registered), manually render
        if ( class_exists( 'LBI_Forms' ) && strpos( $content, '[lbi_recommend_form]' ) !== false ) {
            try {
                ob_start();
                LBI_Forms::recommend_form_shortcode( array() );
                $form_html = ob_get_clean();
                $content = str_replace( '[lbi_recommend_form]', $form_html, $content );
            } catch ( Exception $e ) {
                $content = str_replace( '[lbi_recommend_form]', 
                    '<div class="error">Form render error: ' . esc_html( $e->getMessage() ) . '</div>',  
                    $content );
            }
        }
    }
    
    return $content;
}, 99 );
