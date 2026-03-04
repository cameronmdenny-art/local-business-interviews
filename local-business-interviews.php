<?php
/**
 * Plugin Name: Local Business Interviews
 * Description: Collects local business interview submissions, holds them for admin review, publishes interviews and directory listings upon approval.
 * Version:     1.0.3
 * Author:      Your Name
 * Text Domain: local-business-interviews
 * Domain Path: /languages
 * Updated:     2026-03-02
 */

// Cache-bust: 202603030235A

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// EARLY EXIT: If plugin is already loaded (via MU plugin), don't load again
if ( class_exists( 'LBI_CPT' ) || defined( 'LBI_PLUGIN_LOADED' ) ) {
    return;
}

// Mark that this plugin is now loading  
if ( ! defined( 'LBI_PLUGIN_LOADED' ) ) {
    define( 'LBI_PLUGIN_LOADED', true );
}

// Define constants (safely - check before defining)
if ( ! defined( 'LBI_PLUGIN_DIR' ) ) {
    define( 'LBI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'LBI_PLUGIN_VERSION' ) ) {
    define( 'LBI_PLUGIN_VERSION', '1.0.3' );
}

// Optional configuration constants (set in wp-config.php or via mu-plugin):
// define( 'LBI_API_TOKEN', 'your-shared-secret' ); // require token for REST submissions
// define( 'LBI_RECAPTCHA_SECRET', 'your-google-secret-key' ); // enable reCAPTCHA on forms
// Filters allow adjusting rate limits, upload size, etc.
// add_filter( 'lbi_max_submissions_per_hour', function(){ return 10; } );
// add_filter( 'lbi_max_upload_size', function(){ return 5 * 1024 * 1024; } );

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

// Load helpers early (used by templates)
require_once LBI_PLUGIN_DIR . 'includes/helpers.php';

// load core components
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
LBI_Assets::init(); // Load CSS and JS assets

// Load custom header injector (injects header into any theme via WordPress hooks)
require_once LBI_PLUGIN_DIR . 'includes/header-injector.php';

// Safe runtime fallback: render LBI shortcodes in page content without mutating DB content.
add_filter( 'the_content', function( $content ) {
    if ( is_admin() || ! is_singular( 'page' ) || ! is_main_query() ) {
        return $content;
    }

    if ( false === strpos( $content, '[lbi_' ) ) {
        return $content;
    }

    return do_shortcode( $content );
}, 20 );

LBI_Directory_Search::init();

// Load admin dashboard (runs only in admin)
if ( is_admin() ) {
    require_once LBI_PLUGIN_DIR . 'includes/admin-dashboard.php';
    require_once LBI_PLUGIN_DIR . 'includes/demo-admin.php';
    require_once LBI_PLUGIN_DIR . 'includes/category-setup.php';
}

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    $frontend_css_path = LBI_PLUGIN_DIR . 'assets/css/frontend.css';
    $animations_css_path = LBI_PLUGIN_DIR . 'assets/css/animations.css';
    $directory_map_js_path = LBI_PLUGIN_DIR . 'assets/js/directory-map.js';

    wp_enqueue_style(
        'lbi-frontend',
        plugins_url( 'assets/css/frontend.css', __FILE__ ),
        array(),
        file_exists( $frontend_css_path ) ? filemtime( $frontend_css_path ) : LBI_PLUGIN_VERSION
    );
    
    wp_enqueue_style(
        'lbi-animations',
        plugins_url( 'assets/css/animations.css', __FILE__ ),
        array( 'lbi-frontend' ),
        file_exists( $animations_css_path ) ? filemtime( $animations_css_path ) : LBI_PLUGIN_VERSION
    );

    if ( is_post_type_archive( 'directory' ) || is_tax( array( 'business_category', 'service_city' ) ) ) {
        wp_enqueue_style(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );

        wp_enqueue_script(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            true
        );

        wp_enqueue_script(
            'lbi-directory-map',
            plugins_url( 'assets/js/directory-map.js', __FILE__ ),
            array( 'leaflet' ),
            file_exists( $directory_map_js_path ) ? filemtime( $directory_map_js_path ) : LBI_PLUGIN_VERSION,
            true
        );
    }
} );

// Enqueue admin styles
add_action( 'admin_enqueue_scripts', function() {
    $admin_css_path = LBI_PLUGIN_DIR . 'assets/css/admin.css';

    wp_enqueue_style(
        'lbi-admin',
        plugins_url( 'assets/css/admin.css', __FILE__ ),
        array(),
        file_exists( $admin_css_path ) ? filemtime( $admin_css_path ) : LBI_PLUGIN_VERSION
    );
} );

// Register and load custom homepage template
function lbi_register_homepage_template( $templates ) {
	array_unshift( $templates, 'front-page.php' );
	return $templates;
}

// Template routing
add_filter( 'template_include', function( $template ) {
	if ( is_front_page() && ! is_admin() ) {
		$custom_template = LBI_PLUGIN_DIR . 'front-page.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}

    // Recommend page template
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

// activation / deactivation hooks
register_activation_hook( __FILE__, array( 'LBI_CPT', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'LBI_CPT', 'deactivate' ) );
