<?php
/**
 * Plugin Name: Local Business Interviews
 * Description: Collects local business interview submissions, holds them for admin review, publishes interviews and directory listings upon approval.
 * Version:     1.0.0
 * Author:      Your Name
 * Text Domain: local-business-interviews
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'LBI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

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

// Load admin dashboard (runs only in admin)
if ( is_admin() ) {
    require_once LBI_PLUGIN_DIR . 'includes/admin-dashboard.php';
    require_once LBI_PLUGIN_DIR . 'includes/demo-admin.php';
}

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'lbi-frontend',
        plugins_url( 'assets/css/frontend.css', __FILE__ ),
        array(),
        '1.0.0'
    );
    
    wp_enqueue_style(
        'lbi-animations',
        plugins_url( 'assets/css/animations.css', __FILE__ ),
        array( 'lbi-frontend' ),
        '1.0.0'
    );
} );

// Enqueue admin styles
add_action( 'admin_enqueue_scripts', function() {
    wp_enqueue_style(
        'lbi-admin',
        plugins_url( 'assets/css/admin.css', __FILE__ ),
        array(),
        '1.0.0'
    );
} );

// Register and load custom homepage template
function lbi_register_homepage_template( $templates ) {
	array_unshift( $templates, 'front-page.php' );
	return $templates;
}
add_filter( 'template_include', function( $template ) {
	if ( is_front_page() && ! is_admin() ) {
		$custom_template = LBI_PLUGIN_DIR . 'front-page.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	return $template;
}, 1 );

// activation / deactivation hooks
register_activation_hook( __FILE__, array( 'LBI_CPT', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'LBI_CPT', 'deactivate' ) );
