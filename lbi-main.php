<?php
/**
 * LBI Plugin Stub - loads main functionality
 * This file ensures no double-loading issues
 * Version: 1.0.3
 */

// Mark plugin as loaded BEFORE any other code
if ( ! defined( 'LBI_PLUGIN_LOADED' ) ) {
    define( 'LBI_PLUGIN_LOADED', true );
}

// Define constants safely
if ( ! defined( 'LBI_PLUGIN_DIR' ) ) {
    define( 'LBI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LBI_PLUGIN_VERSION' ) ) {
    define( 'LBI_PLUGIN_VERSION', '1.0.3' );
}

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load the main functionality from a separate file to avoid caching issues
require_once LBI_PLUGIN_DIR . 'includes/main-plugin.php';
?>
