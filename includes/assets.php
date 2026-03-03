<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Assets - Handle plugin assets (CSS, JS)
 */
class LBI_Assets {
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
    }

    /**
     * Enqueue frontend assets
     */
    public static function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url( dirname( __FILE__ ) );
        $version = LBI_PLUGIN_VERSION;

        // Enqueue premium frontend CSS
        wp_enqueue_style(
            'lbi-frontend',
            $plugin_url . 'assets/css/frontend.css',
            array(),
            $version,
            'all'
        );

        // Enqueue premium animations CSS
        wp_enqueue_style(
            'lbi-animations',
            $plugin_url . 'assets/css/animations-premium.css',
            array( 'lbi-frontend' ), // Load after frontend.css
            $version,
            'all'
        );

        // Enqueue frontend JavaScript if needed
        if ( file_exists( dirname( dirname( __FILE__ ) ) . '/assets/js/frontend.js' ) ) {
            wp_enqueue_script(
                'lbi-frontend',
                $plugin_url . 'assets/js/frontend.js',
                array( 'jquery' ),
                $version,
                true // Load in footer
            );

            // Localize script with AJAX URL and nonce
            wp_localize_script( 'lbi-frontend', 'lbiAjax', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'lbi_ajax_nonce' ),
            ));
        }
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets( $hook ) {
        $plugin_url = plugin_dir_url( dirname( __FILE__ ) );
        $version = LBI_PLUGIN_VERSION;

        // Only load on our admin pages
        $admin_pages = array(
            'toplevel_page_lbi-dashboard',
            'local-business-interviews_page_lbi-settings',
            'local-business-interviews_page_lbi-demo-data',
        );

        if ( ! in_array( $hook, $admin_pages ) && get_post_type() !== 'directory' && get_post_type() !== 'interview' ) {
            return;
        }

        // Enqueue admin CSS if it exists
        if ( file_exists( dirname( dirname( __FILE__ ) ) . '/assets/css/admin.css' ) ) {
            wp_enqueue_style(
                'lbi-admin',
                $plugin_url . 'assets/css/admin.css',
                array(),
                $version,
                'all'
            );
        }

        // Enqueue admin JavaScript if it exists
        if ( file_exists( dirname( dirname( __FILE__ ) ) . '/assets/js/admin.js' ) ) {
            wp_enqueue_script(
                'lbi-admin',
                $plugin_url . 'assets/js/admin.js',
                array( 'jquery' ),
                $version,
                true
            );

            // Localize script
            wp_localize_script( 'lbi-admin', 'lbiAdmin', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'lbi_admin_nonce' ),
            ));
        }
    }

    /**
     * Get plugin URL
     */
    public static function get_plugin_url() {
        return plugin_dir_url( dirname( __FILE__ ) );
    }

    /**
     * Get plugin path
     */
    public static function get_plugin_path() {
        return plugin_dir_path( dirname( __FILE__ ) );
    }
}
