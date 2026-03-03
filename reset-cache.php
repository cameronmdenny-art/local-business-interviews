<?php
// Hardcore cache clear - force PHP to reload everything
if ( function_exists( 'opcache_reset' ) ) {
    opcache_reset();
    echo "✅ Opcache reset called";
} else {
    echo "⚠️ Opcache not available or already disabled";
}

// Also try to invalidate apc cache if it exists
if ( function_exists( 'apc_clear_cache' ) ) {
    apc_clear_cache();
    echo " + APC cleared";
}

// Also clear file stat cache
clearstatcache( true );

// Set the right error reporting to immediately show the problem
@ini_set( 'display_errors', 0 );
@error_reporting( 0 );
?>
