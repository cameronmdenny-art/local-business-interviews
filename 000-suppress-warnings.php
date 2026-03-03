<?php
// Disable PHP warnings display on frontend only
// This file should be placed in wp-content/mu-plugins/ and loads before everything

if ( ! defined( 'ABSPATH' ) ) {
    // If we're this early, ABSPATH isn't defined yet
    // Just suppress all notices and warnings on frontend
    if ( php_sapi_name() !== 'cli' && 
         ( ! isset( $_SERVER['REQUEST_URI'] ) || 
           strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) === false ) ) {
        error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR );
        ini_set( 'display_errors', '0' );
    }
}
