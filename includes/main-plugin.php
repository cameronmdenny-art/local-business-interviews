<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'LBI_PLUGIN_DIR' ) ) {
    return;
}

$plugin_entry_file = LBI_PLUGIN_DIR . 'lbi-main.php';

// Autoload LBI_* classes
spl_autoload_register( function ( $class ) {
    if ( 0 !== strpos( $class, 'LBI_' ) ) {
        return;
    }

    $file = str_replace( '_', '-', strtolower( substr( $class, 4 ) ) );
    $path = LBI_PLUGIN_DIR . "includes/$file.php";

    if ( file_exists( $path ) ) {
        require_once $path;
    }
} );

require_once LBI_PLUGIN_DIR . 'includes/helpers.php';
require_once LBI_PLUGIN_DIR . 'includes/cpt.php';
require_once LBI_PLUGIN_DIR . 'includes/taxonomies.php';
require_once LBI_PLUGIN_DIR . 'includes/meta.php';
require_once LBI_PLUGIN_DIR . 'includes/admin.php';
require_once LBI_PLUGIN_DIR . 'includes/shortcodes.php';
require_once LBI_PLUGIN_DIR . 'includes/forms.php';
require_once LBI_PLUGIN_DIR . 'includes/rest.php';
require_once LBI_PLUGIN_DIR . 'includes/schema.php';
require_once LBI_PLUGIN_DIR . 'includes/security.php';
require_once LBI_PLUGIN_DIR . 'includes/emails.php';
require_once LBI_PLUGIN_DIR . 'includes/assets.php';
require_once LBI_PLUGIN_DIR . 'includes/directory-search.php';
require_once LBI_PLUGIN_DIR . 'includes/header-injector.php';

LBI_CPT::init();
LBI_Taxonomies::init();
LBI_Meta::init();
LBI_Admin::init();
LBI_Shortcodes::init();
LBI_Forms::init();
LBI_REST::init();
LBI_Schema::init();
LBI_Security::init();
LBI_Emails::init();
LBI_Assets::init();
LBI_Directory_Search::init();

if ( is_admin() ) {
    require_once LBI_PLUGIN_DIR . 'includes/admin-dashboard.php';
    require_once LBI_PLUGIN_DIR . 'includes/demo-admin.php';
    require_once LBI_PLUGIN_DIR . 'includes/category-setup.php';
}

add_filter( 'the_content', function( $content ) {
    if ( is_admin() || ! is_singular( 'page' ) || ! is_main_query() ) {
        return $content;
    }

    if ( false === strpos( $content, '[lbi_' ) ) {
        return $content;
    }

    return do_shortcode( $content );
}, 20 );

add_action( 'wp_enqueue_scripts', function() use ( $plugin_entry_file ) {
    $frontend_css_path = LBI_PLUGIN_DIR . 'assets/css/frontend.css';
    $animations_css_path = LBI_PLUGIN_DIR . 'assets/css/animations.css';
    $directory_map_js_path = LBI_PLUGIN_DIR . 'assets/js/directory-map.js';

    wp_enqueue_style(
        'lbi-frontend',
        plugins_url( 'assets/css/frontend.css', $plugin_entry_file ),
        array(),
        file_exists( $frontend_css_path ) ? filemtime( $frontend_css_path ) : ( defined( 'LBI_PLUGIN_VERSION' ) ? LBI_PLUGIN_VERSION : '1.0.3' )
    );

    wp_enqueue_style(
        'lbi-animations',
        plugins_url( 'assets/css/animations.css', $plugin_entry_file ),
        array( 'lbi-frontend' ),
        file_exists( $animations_css_path ) ? filemtime( $animations_css_path ) : ( defined( 'LBI_PLUGIN_VERSION' ) ? LBI_PLUGIN_VERSION : '1.0.3' )
    );

    if ( is_post_type_archive( 'directory' ) || is_tax( array( 'business_category', 'service_city' ) ) ) {
        wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
        wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
        wp_enqueue_script(
            'lbi-directory-map',
            plugins_url( 'assets/js/directory-map.js', $plugin_entry_file ),
            array( 'leaflet' ),
            file_exists( $directory_map_js_path ) ? filemtime( $directory_map_js_path ) : ( defined( 'LBI_PLUGIN_VERSION' ) ? LBI_PLUGIN_VERSION : '1.0.3' ),
            true
        );
    }
} );

add_action( 'admin_enqueue_scripts', function() use ( $plugin_entry_file ) {
    $admin_css_path = LBI_PLUGIN_DIR . 'assets/css/admin.css';

    wp_enqueue_style(
        'lbi-admin',
        plugins_url( 'assets/css/admin.css', $plugin_entry_file ),
        array(),
        file_exists( $admin_css_path ) ? filemtime( $admin_css_path ) : ( defined( 'LBI_PLUGIN_VERSION' ) ? LBI_PLUGIN_VERSION : '1.0.3' )
    );
} );

add_filter( 'template_include', function( $template ) {
    if ( is_page() && ! is_admin() ) {
        $post = get_post( get_the_ID() );
        if ( $post && ( $post->post_name === 'recommend' || $post->post_name === 'recommend-a-business' ) ) {
            $recommend_template = LBI_PLUGIN_DIR . 'page-recommend.php';
            if ( file_exists( $recommend_template ) ) {
                return $recommend_template;
            }
        }
    }

    if ( is_singular( 'directory' ) ) {
        $single_template = LBI_PLUGIN_DIR . 'single-directory.php';
        if ( file_exists( $single_template ) ) {
            return $single_template;
        }
    }

    if ( is_post_type_archive( 'directory' ) || is_tax( array( 'business_category', 'service_city' ) ) ) {
        $archive_template = LBI_PLUGIN_DIR . 'archive-directory.php';
        if ( file_exists( $archive_template ) ) {
            return $archive_template;
        }
    }

    return $template;
}, 1 );
