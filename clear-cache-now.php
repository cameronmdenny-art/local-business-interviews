<?php
// Simple cache buster for LiteSpeed
if ( defined( 'LITESPEED_INSTANCE_ID' ) ) {
    do_action( 'litespeed_purge_all' );
}

// WordPress cache clear
if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
}

// LiteSpeed specific
if ( function_exists( 'litespeed_purge_all' ) ) {
    litespeed_purge_all();  
}

echo "Cache purged at " . date( 'Y-m-d H:i:s' );
?>
