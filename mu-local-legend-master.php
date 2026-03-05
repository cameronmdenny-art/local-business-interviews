<?php
/**
 * Plugin Name: Local Legend Stories - Master Header/Footer
 * Description: Injects Local Legend Stories branding - overrides all other headers
 * Version: 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// CRITICAL: Hide ALL theme headers/footers first
add_action( 'wp_head', function() {
    ?>
    <style id="local-legend-master-hide" type="text/css">
        /* Hide EVERYTHING that's not Local Legend */
        .site-header, header.site-header { display: none !important; }
        nav.wp-block-navigation { display: none !important; }
        .wp-block-navigation { display: none !important; }
        .wp-block-template-part header { display: none !important; }
        .site-footer, footer.site-footer { display: none !important; }
        .wp-block-template-part footer { display: none !important; }
        
        /* Ensure body can accommodate fixed header */
        body { margin: 0 !important; padding-top: 100px !important; }
    </style>
    <?php
}, 0 );

// INJECT LOCAL LEGEND HEADER STYLES
add_action( 'wp_head', function() {
    ?>
    <style id="local-legend-master-header-styles">
        :root {
            --local-legend-gold: #bfa673;
            --local-legend-tan: #d4c197;
            --local-legend-dark: #1a1a1a;
        }
        
        .local-legend-master-header {
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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        }
        
        .local-legend-header-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }
        
        .local-legend-logo-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-shrink: 0;
        }
        
        .local-legend-logo-img {
            height: 50px;
            width: auto;
            display: block;
        }
        
        .local-legend-branding {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        
        .local-legend-logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin: 0;
            line-height: 1.1;
        }
        
        .local-legend-logo-text span {
            color: #bfa673;
        }
        
        .local-legend-logo-tagline {
            font-size: 0.8rem;
            color: #bfa673;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0.4rem 0 0 0;
        }
        
        .local-legend-nav {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .local-legend-nav-item {
            display: inline-flex;
            align-items: center;
            padding: 0.8rem 1.3rem;
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid transparent;
            white-space: nowrap;
        }
        
        .local-legend-nav-item:hover {
            color: #bfa673;
            border-color: rgba(191, 166, 115, 0.6);
            background-color: rgba(191, 166, 115, 0.15);
            transform: translateY(-2px);
        }
        
        .local-legend-nav-item.cta {
            background: linear-gradient(135deg, rgba(191, 166, 115, 0.3) 0%, rgba(212, 193, 151, 0.2) 100%);
            border-color: rgba(191, 166, 115, 0.5);
            color: #bfa673;
            font-weight: 700;
        }
        
        .local-legend-nav-item.cta:hover {
            background: linear-gradient(135deg, rgba(191, 166, 115, 0.4) 0%, rgba(212, 193, 151, 0.3) 100%);
            border-color: #bfa673;
        }
        
        @media (max-width: 900px) {
            .local-legend-header-wrapper { flex-direction: column; gap: 1rem; }
            .local-legend-nav { width: 100%; justify-content: center; }
            .local-legend-logo-text { font-size: 1.5rem; }
        }
    </style>
    <?php
}, 1 );

// INJECT HEADER HTML
add_action( 'wp_body_open', function() {
    ?>
    <header class="local-legend-master-header" id="local-legend-header-master">
        <div class="local-legend-header-wrapper">
            <div class="local-legend-logo-section">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="local-legend-logo-link">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                         alt="Local Legend Stories" 
                         class="local-legend-logo-img"
                         onerror="this.style.display='none'">
                </a>
                <div class="local-legend-branding">
                    <h1 class="local-legend-logo-text">Local Legend<span>Stories</span></h1>
                    <p class="local-legend-logo-tagline">Celebrating Local Business</p>
                </div>
            </div>
            
            <nav class="local-legend-nav">
                <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="local-legend-nav-item">Directory</a>
                <a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="local-legend-nav-item">Submit Interview</a>
                <a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>" class="local-legend-nav-item cta">Recommend Business</a>
            </nav>
        </div>
    </header>
    <script>
        (function() {
            document.documentElement.classList.add('has-local-legend-header');
            document.body.classList.add('has-local-legend-header');
            document.body.style.paddingTop = '100px';
        })();
    </script>
    <?php
}, 1 );

// INJECT FOOTER STYLES
add_action( 'wp_head', function() {
    ?>
    <style id="local-legend-master-footer-styles">
        .local-legend-master-footer {
            background: linear-gradient(180deg, rgba(26, 26, 26, 0.99) 0%, rgba(20, 20, 20, 0.99) 100%);
            border-top: 2px solid rgba(191, 166, 115, 0.3);
            padding: 3rem 2rem;
            color: #cccccc;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-top: 4rem;
        }
        
        .local-legend-footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2.5rem;
            margin-bottom: 3rem;
        }
        
        .footer-column h3 {
            color: #bfa673;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-column li {
            margin-bottom: 0.7rem;
        }
        
        .footer-column a {
            color: #cccccc;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-column a:hover {
            color: #bfa673;
            padding-left: 8px;
        }
        
        .footer-branding {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-logo {
            height: 50px;
            width: auto;
        }
        
        .footer-logo-text h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }
        
        .footer-logo-text h2 span {
            color: #bfa673;
        }
        
        .footer-tagline {
            font-size: 0.8rem;
            color: #bfa673;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0.3rem 0 0 0;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(191, 166, 115, 0.2);
            padding-top: 2rem;
            text-align: center;
            color: #888888;
            font-size: 0.85rem;
        }
        
        .footer-bottom a {
            color: #bfa673;
            text-decoration: none;
        }
    </style>
    <?php
}, 2 );

// INJECT FOOTER HTML
add_action( 'wp_footer', function() {
    ?>
    <footer class="local-legend-master-footer" id="local-legend-footer-master">
        <div class="local-legend-footer-content">
            <div class="footer-column">
                <div class="footer-branding">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                         alt="Local Legend Stories" 
                         class="footer-logo"
                         onerror="this.style.display='none'">
                    <div class="footer-logo-text">
                        <h2>Local Legend<span>Stories</span></h2>
                        <p class="footer-tagline">Celebrating Local Business</p>
                    </div>
                </div>
                <p>Discover and celebrate the authentic stories behind local businesses in your community.</p>
            </div>
            
            <div class="footer-column">
                <h3>Explore</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>">Business Directory</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/interviews/' ) ); ?>">Interview Stories</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>">Submit Your Story</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Community</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Recommend Business</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Us</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Us</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> Local Legend Stories. All rights reserved. | <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></p>
        </div>
    </footer>
    <?php
}, 5 );
