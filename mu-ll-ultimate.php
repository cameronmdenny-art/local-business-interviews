<?php
/**
 * Plugin Name: Local Legend Ultimate Override
 * Description: Simple direct HTML inline injection
 * Version: 4.0
**/ 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add inline header HTML and CSS directly to wp_head
add_action( 'wp_head', function() {
    $home = home_url( '/' );
    $logo = home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' );
    
    echo <<<'HTML'
<style>
body { padding-top: 120px !important; margin: 0; }
.site-header, header.site-header, nav.wp-block-navigation, .wp-block-navigation, .wp-block-template-part header { display: none !important; }
.ll-h { position: fixed; top: 0; left: 0; right: 0; z-index: 999999; background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%); backdrop-filter: blur(30px); border-bottom: 2px solid rgba(191, 166, 115, 0.4); padding: 1.5rem 2rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
.ll-h > div { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 2rem; }
.ll-logo { display: flex; gap: 1.5rem; align-items: center; }
.ll-logo img { height: 50px; width: auto; }
.ll-text h1 { font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0; }
.ll-text h1 span { color: #bfa673; }
.ll-text p { font-size: 0.8rem; color: #bfa673; font-weight: 700; text-transform: uppercase; margin: 0.4rem 0 0 0; letter-spacing: 1px; }
.ll-nav { display: flex; gap: 1rem; justify-content: flex-end; }
.ll-nav a { color: #fff; text-decoration: none; padding: 0.8rem 1.3rem; border-radius: 6px; transition: all 0.3s; font-weight: 600; border: 1.5px solid transparent; }
.ll-nav a:hover { color: #bfa673; border-color: rgba(191, 166, 115, 0.6); background: rgba(191, 166, 115, 0.15); }
.ll-nav a.cta { background: linear-gradient(135deg, rgba(191, 166, 115, 0.3) 0%, rgba(212, 193, 151, 0.2) 100%); border-color: rgba(191, 166, 115, 0.5); color: #bfa673; }
@media (max-width: 900px) { .ll-h > div { flex-direction: column; } .ll-nav { width: 100%; justify-content: center; } .ll-text h1 { font-size: 1.5rem; } }
</style>
<header class="ll-h"><div><div class="ll-logo"><a href="HTML;
    
    echo esc_url( $home );
    
    echo '"><img src="';
    echo esc_url( $logo );
    echo '" alt="Local Legend Stories"></a><div class="ll-text"><h1>Local Legend<span>Stories</span></h1><p>Celebrating Local Business</p></div></div><nav class="ll-nav"><a href="';
    echo esc_url( $home . 'directory/' );
    echo '">Directory</a><a href="';
    echo esc_url( $home . 'submit-interview/' );
    echo '">Submit Interview</a><a href="';
    echo esc_url( $home . 'recommend/' );
    echo '" class="cta">Recommend</a></nav></div></header>';
}, 0 );

// Also add footer (much simpler)
add_action( 'wp_footer', function() {
    $home = home_url( '/' );
    echo '<style>.ll-f { background: linear-gradient(180deg, rgba(26, 26, 26, 0.99) 0%, rgba(20, 20, 20, 0.99) 100%); border-top: 2px solid rgba(191, 166, 115, 0.3); padding: 3rem 2rem; color: #ccc; text-align: center; margin-top: 4rem; } .ll-f a { color: #bfa673; text-decoration: none; } </style>';
    echo '<footer class="ll-f"><p>&copy; ' . date( 'Y' ) . ' Local Legend Stories | <a href="' . esc_url( $home ) . '">Home</a></p></footer>';
}, 5 );
