<?php
/**
 * Plugin Name: LBI Force Activate
 * Description: Loads LBI plugin only when it is not active; keeps recommend-page shortcode fallback.
 * Version: 1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'lbi_main_plugin_is_active' ) ) {
    function lbi_main_plugin_is_active() {
        $plugin_file    = 'local-business-interviews/local-business-interviews.php';
        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( in_array( $plugin_file, $active_plugins, true ) ) {
            return true;
        }

        if ( is_multisite() ) {
            $network_active = (array) get_site_option( 'active_sitewide_plugins', array() );
            if ( isset( $network_active[ $plugin_file ] ) ) {
                return true;
            }
        }

        return false;
    }
}

$main_plugin = WP_PLUGIN_DIR . '/local-business-interviews/local-business-interviews.php';

if ( ! lbi_main_plugin_is_active() && file_exists( $main_plugin ) && ! class_exists( 'LBI_Forms' ) ) {
    require_once $main_plugin;
}

add_filter( 'the_content', function( $content ) {
    if ( ! is_page() || is_admin() ) {
        return $content;
    }

    $post = get_post();
    if ( ! $post || ( $post->post_name !== 'recommend' && $post->post_name !== 'recommend-a-business' ) ) {
        return $content;
    }

    if ( strpos( $content, '[lbi_recommend_form]' ) !== false ) {
        $content = do_shortcode( $content );

        if ( class_exists( 'LBI_Forms' ) && strpos( $content, '[lbi_recommend_form]' ) !== false ) {
            ob_start();
            LBI_Forms::recommend_form_shortcode( array() );
            $form_html = ob_get_clean();
            $content   = str_replace( '[lbi_recommend_form]', $form_html, $content );
        }
    }

    return $content;
}, 99 );
