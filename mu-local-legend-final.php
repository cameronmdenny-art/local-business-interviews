<?php
/**
 * Plugin Name: Local Legend Complete Override - OUTPUT BUFFER
 * Description: Uses output buffering to inject Local Legend header/footer
 * Version: 3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LocalLegendComplete {
    public static function init() {
        // Start output buffering
        add_action( 'template_redirect', [ __CLASS__, 'start_buffer' ], 1 );
        add_action( 'shutdown', [ __CLASS__, 'end_buffer' ], 999 );
    }
    
    public static function start_buffer() {
        ob_start( [ __CLASS__, 'filter_html' ] );
    }
    
    public static function end_buffer() {
        ob_end_flush();
    }
    
    public static function filter_html( $html ) {
        if ( empty( $html ) || ! is_string( $html ) ) {
            return $html;
        }
        
        // Remove old headers/footers
        $html = preg_replace( '/<header[^>]*class="[^"]*site-header[^"]*"[^>]*>.*?<\/header>/is', '', $html );
        $html = preg_replace( '/<nav[^>]*class="[^"]*wp-block-navigation[^"]*"[^>]*>.*?<\/nav>/is', '', $html );
        $html = preg_replace( '/<footer[^>]*class="[^"]*site-footer[^"]*"[^>]*>.*?<\/footer>/is', '', $html );
        
        // Add Local Legend header after <body
        $header = self::get_header_html();
        $html = preg_replace( '/<body[^>]*>/i', '<body$0>' . $header, $html );
        
        // Add Local Legend footer before </body>
        $footer = self::get_footer_html();
        $html = str_ireplace( '</body>', $footer . '</body>', $html );
        
        // Add CSS to hide theme elements
        $styles = self::get_styles();
        $html = str_ireplace( '</head>', $styles . '</head>', $html );
        
        return $html;
    }
    
    public static function get_styles() {
        $home_url = home_url( '/' );
        return <<<'CSS'
    <style id="local-legend-complete-styles">
        .site-header, header.site-header { display: none !important; }
        nav.wp-block-navigation { display: none !important; }
        .wp-block-template-part header { display: none !important; }
        .site-footer, footer.site-footer { display: none !important; }
        .wp-block-template-part footer { display: none !important; }
        
        body { padding-top: 100px !important; margin-top: 0 !important; }
        
        :root {
            --local-legend-gold: #bfa673;
            --local-legend-dark: #1a1a1a;
        }
        
        .ll-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999999;
            background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%);
            backdrop-filter: blur(30px);
            border-bottom: 2px solid rgba(191, 166, 115, 0.4);
            padding: 1.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .ll-header-wrap {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }
        
        .ll-logo-section { display: flex; align-items: center; gap: 1.5rem; }
        .ll-logo-img { height: 50px; width: auto; }
        .ll-branding { display: flex; flex-direction: column; }
        .ll-logo-text { font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0; }
        .ll-logo-text span { color: #bfa673; }
        .ll-tagline { font-size: 0.8rem; color: #bfa673; font-weight: 700; text-transform: uppercase; margin: 0.4rem 0 0 0; letter-spacing: 1px; }
        
        .ll-nav { display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap; }
        .ll-nav a { color: #fff; text-decoration: none; padding: 0.8rem 1.3rem; border-radius: 6px; transition: all 0.3s; font-weight: 600; border: 1.5px solid transparent; }
        .ll-nav a:hover { color: #bfa673; border-color: rgba(191, 166, 115, 0.6); background: rgba(191, 166, 115, 0.15); }
        .ll-nav a.cta { background: linear-gradient(135deg, rgba(191, 166, 115, 0.3) 0%, rgba(212, 193, 151, 0.2) 100%); border-color: rgba(191, 166, 115, 0.5); color: #bfa673; }
        
        .ll-footer {
            background: linear-gradient(180deg, rgba(26, 26, 26, 0.99) 0%, rgba(20, 20, 20, 0.99) 100%);
            border-top: 2px solid rgba(191, 166, 115, 0.3);
            padding: 3rem 2rem;
            color: #ccc;
            font-size: 0.95rem;
            margin-top: 4rem;
        }
        
        .ll-footer-wrap { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2.5rem; }
        .ll-footer h3 { color: #bfa673; font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; text-transform: uppercase; }
        .ll-footer ul { list-style: none; padding: 0; margin: 0; }
        .ll-footer li { margin-bottom: 0.7rem; }
        .ll-footer a { color: #ccc; text-decoration: none; transition: all 0.3s; }
        .ll-footer a:hover { color: #bfa673; padding-left: 8px; }
        .ll-footer-bottom { border-top: 1px solid rgba(191, 166, 115, 0.2); padding-top: 2rem; text-align: center; color: #888; font-size: 0.85rem; margin-top: 2rem; }
        .ll-footer-bottom a { color: #bfa673; }
        
        @media (max-width: 900px) {
            .ll-header-wrap { flex-direction: column; }
            .ll-nav { width: 100%; justify-content: center; }
            .ll-logo-text { font-size: 1.5rem; }
        }
    </style>
CSS;
    }
    
    public static function get_header_html() {
        $home = home_url( '/' );
        $logo = home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' );
        
        return <<<HTML
<header class="ll-header">
<div class="ll-header-wrap">
<div class="ll-logo-section">
<a href="$home"><img src="$logo" alt="Local Legend Stories" class="ll-logo-img" onerror="this.style.display='none'"></a>
<div class="ll-branding">
<h1 class="ll-logo-text">Local Legend<span>Stories</span></h1>
<p class="ll-tagline">Celebrating Local Business</p>
</div>
</div>
<nav class="ll-nav">
<a href="$home/directory/">Directory</a>
<a href="$home/submit-interview/">Submit Interview</a>
<a href="$home/recommend/" class="cta">Recommend Business</a>
</nav>
</div>
</header>
HTML;
    }
    
    public static function get_footer_html() {
        $home = home_url( '/' );
        $logo = home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' );
        $year = date( 'Y' );
        
        return <<<HTML
<footer class="ll-footer">
<div class="ll-footer-wrap">
<div>
<img src="$logo" alt="Local Legend" class="ll-logo-img" onerror="this.style.display='none'" style="margin-bottom:1rem;">
<h2 style="font-size:1.3rem;color:#fff;margin:0;">Local Legend<span style="color:#bfa673;">Stories</span></h2>
<p style="color:#bfa673;font-size:0.8rem;text-transform:uppercase;margin:0.3rem 0 0 0;">Celebrating Local Business</p>
<p style="margin-top:1rem;">Discover and celebrate authentic local business stories in your community.</p>
</div>
<div>
<h3>Explore</h3>
<ul>
<li><a href="$home/directory/">Business Directory</a></li>
<li><a href="$home/interviews/">Interview Stories</a></li>
<li><a href="$home/submit-interview/">Submit Your Story</a></li>
</ul>
</div>
<div>
<h3>Community</h3>
<ul>
<li><a href="$home/recommend/">Recommend Business</a></li>
<li><a href="$home/contact/">Contact Us</a></li>
<li><a href="$home/about/">About Us</a></li>
</ul>
</div>
<div>
<h3>Legal</h3>
<ul>
<li><a href="$home/privacy-policy/">Privacy Policy</a></li>
<li><a href="$home/terms-of-service/">Terms</a></li>
</ul>
</div>
</div>
<div class="ll-footer-bottom">
<p>&copy; $year Local Legend Stories | <a href="$home">Home</a></p>
</div>
</footer>
HTML;
    }
}

LocalLegendComplete::init();
