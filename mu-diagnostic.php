<?php
// Diagnostic mu-plugin - write to file to test if it's loading
$log_file = '/home/u300002008/domains/ivory-lark-138468.hostingersite.com/public_html/mu-plugin-test.log';
file_put_contents( $log_file, "MU-plugin loaded at " . date( 'Y-m-d H:i:s' ) . "\n", FILE_APPEND );

// Try to add something visible
add_action( 'admin_notices', function() {
    file_put_contents( '/home/u300002008/domains/ivory-lark-138468.hostingersite.com/public_html/mu-plugin-test.log', "Admin hook fired\n", FILE_APPEND );
});

add_action( 'wp_head', function() {
    file_put_contents( '/home/u300002008/domains/ivory-lark-138468.hostingersite.com/public_html/mu-plugin-test.log', "wp_head hook fired\n", FILE_APPEND );
    echo "<!-- MU-PLUGIN WP_HEAD FIRED -->";
});

add_action( 'wp_footer', function() {
    file_put_contents( '/home/u300002008/domains/ivory-lark-138468.hostingersite.com/public_html/mu-plugin-test.log', "wp_footer hook fired\n", FILE_APPEND );
    echo "<!-- MU-PLUGIN WP_FOOTER FIRED -->";
});
