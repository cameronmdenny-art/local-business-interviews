<?php
/**
 * Local Legend Stories Header & Footer Injector
 * 
 * Injects custom header and footer with Local Legend Stories branding
 * Hides WordPress theme headers and footers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Header_Injector {
    
    public static function init() {
        // Hide theme headers/footers
        add_action( 'wp_head', [ __CLASS__, 'hide_theme_elements' ], 1 );
        
        // Inject Local Legend Stories header
        add_action( 'wp_head', [ __CLASS__, 'header_styles' ], 5 );
        add_action( 'wp_body_open', [ __CLASS__, 'output_header' ], 5 );
        
        // Inject Local Legend Stories footer
        add_action( 'wp_footer', [ __CLASS__, 'footer_styles' ], 1 );
        add_action( 'wp_footer', [ __CLASS__, 'output_footer' ], 50 );
        add_action( 'wp_footer', [ __CLASS__, 'cleanup_script' ], 99999 );
    }
    
    public static function hide_theme_elements() {
        ?>
        <style id="local-legend-hide-theme">
            /* Hide all WordPress theme headers and footers */
            .site-header, header.site-header, .wp-block-template-part[aria-label*="Header"],
            .wp-block-navigation, nav.wp-block-navigation, .primary-menu, .main-navigation,
            nav.site-navigation, .navbar-header, .site-branding, .site-title, .site-title a,
            .site-description, .site-footer, footer.site-footer, .footer-widgets,
            .footer-content, a[href*="ivory-lark"], a[href*="hostingersite"],
            .custom-header, .entry-header > nav, .wp-site-blocks > .wp-block-navigation {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                overflow: hidden !important;
            }
            body { padding-top: 0 !important; }
        </style>
        <?php
    }
    
    public static function header_styles() {
        ?>
        <style id="local-legend-header-styles">
            :root {
                --local-legend-gold: #bfa673;
                --local-legend-dark: #1a1a1a;
                --local-legend-accent: #d4c197;
            }
            
            .local-legend-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 99999;
                background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%);
                backdrop-filter: blur(30px);
                border-bottom: 1px solid rgba(191, 166, 115, 0.3);
                padding: 1.5rem 2rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }
            
            .local-legend-header-container {
                max-width: 1400px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: 1fr 1fr;
                align-items: center;
                gap: 2rem;
            }
            
            .local-legend-header-left {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }
            
            .local-legend-logo-img {
                height: 50px;
                width: auto;
                filter: drop-shadow(0 2px 8px rgba(191, 166, 115, 0.25));
            }
            
            .local-legend-branding {
                display: flex;
                flex-direction: column;
            }
            
            .local-legend-logo-text {
                font-size: 1.8rem;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: -0.5px;
                margin: 0;
                line-height: 1;
            }
            
            .local-legend-logo-sub {
                font-size: 0.85rem;
                color: var(--local-legend-gold);
                font-weight: 600;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin: 0.3rem 0 0 0;
            }
            
            .local-legend-nav {
                display: flex;
                gap: 0.5rem;
                align-items: center;
                justify-content: flex-end;
            }
            
            .local-legend-nav-item {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.2rem;
                color: #ffffff;
                text-decoration: none;
                font-weight: 500;
                font-size: 0.95rem;
                border-radius: 8px;
                transition: all 0.3s ease;
                border: 1px solid transparent;
                white-space: nowrap;
            }
            
            .local-legend-nav-item:hover {
                color: var(--local-legend-gold);
                border-color: rgba(191, 166, 115, 0.4);
                background-color: rgba(191, 166, 115, 0.1);
            }
            
            .local-legend-nav-item.highlight {
                background: linear-gradient(135deg, rgba(191, 166, 115, 0.25) 0%, rgba(212, 193, 151, 0.15) 100%);
                border-color: rgba(191, 166, 115, 0.4);
                color: var(--local-legend-gold);
            }
            
            body.has-local-legend-header { padding-top: 100px; }
            
            @media (max-width: 768px) {
                .local-legend-header { padding: 1rem; }
                .local-legend-header-container { grid-template-columns: 1fr; gap: 1rem; }
                .local-legend-logo-text { font-size: 1.3rem; }
                .local-legend-nav { width: 100%; flex-wrap: wrap; }
                .local-legend-nav-item { flex: 1; min-width: calc(50% - 0.25rem); justify-content: center; font-size: 0.85rem; padding: 0.6rem; }
                body.has-local-legend-header { padding-top: 120px; }
            }
        </style>
        <?php
    }
    
    public static function output_header() {
        ?>
        <header class="local-legend-header" id="local-legend-main-header">
            <div class="local-legend-header-container">
                <div class="local-legend-header-left">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="local-legend-logo-link" title="Local Legend Stories">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                             alt="Local Legend Stories Logo"
                             class="local-legend-logo-img"
                             onerror="this.style.display='none';">
                    </a>
                    <div class="local-legend-branding">
                        <h1 class="local-legend-logo-text">Local Legend<span style="color: var(--local-legend-gold);">Stories</span></h1>
                        <p class="local-legend-logo-sub">Celebrating Local Business</p>
                    </div>
                </div>
                
                <nav class="local-legend-nav">
                    <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="local-legend-nav-item">Directory</a>
                    <a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="local-legend-nav-item">Submit Interview</a>
                    <a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>" class="local-legend-nav-item highlight">Recommend a Business</a>
                </nav>
            </div>
        </header>
        
        <script>
            document.documentElement.classList.add('has-local-legend-header');
            document.body.classList.add('has-local-legend-header');
            const header = document.getElementById('local-legend-main-header');
            if (header) { header.style.zIndex = '99999'; }
        </script>
        <?php
    }
    
    public static function footer_styles() {
        ?>
        <style id="local-legend-footer-styles">
            footer.local-legend-footer {
                background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.99) 100%);
                border-top: 1px solid rgba(191, 166, 115, 0.3);
                padding: 2rem;
                color: #cccccc;
                font-size: 0.9rem;
                line-height: 1.6;
            }
            
            .local-legend-footer-container {
                max-width: 1400px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                padding: 2rem 0;
            }
            
            .footer-column h3 {
                color: #bfa673;
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 1rem;
                text-transform: uppercase;
            }
            
            .footer-column ul { list-style: none; padding: 0; margin: 0; }
            .footer-column li { margin-bottom: 0.5rem; }
            .footer-column a { color: #cccccc; text-decoration: none; transition: all 0.3s ease; }
            .footer-column a:hover { color: #bfa673; padding-left: 5px; }
            
            .footer-branding { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
            .footer-logo { height: 50px; width: auto; filter: drop-shadow(0 2px 8px rgba(191, 166, 115, 0.25)); }
            .footer-logo-text { display: flex; flex-direction: column; gap: 0.3rem; }
            .footer-logo-name { font-size: 1.3rem; font-weight: 700; color: #ffffff; }
            .footer-logo-name span { color: #bfa673; }
            .footer-logo-tagline { font-size: 0.75rem; color: #bfa673; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
            
            .footer-bottom {
                border-top: 1px solid rgba(191, 166, 115, 0.2);
                padding-top: 2rem;
                margin-top: 2rem;
                text-align: center;
                color: #888888;
                font-size: 0.85rem;
            }
            
            .footer-bottom a { color: #bfa673; text-decoration: none; }
            .footer-bottom a:hover { text-decoration: underline; }
            
            @media (max-width: 768px) {
                .local-legend-footer-container { grid-template-columns: 1fr; }
                footer.local-legend-footer { padding: 1.5rem; }
                .footer-branding { flex-direction: column; align-items: flex-start; }
            }
        </style>
        <?php
    }
    
    public static function output_footer() {
        ?>
        <footer class="local-legend-footer" id="local-legend-footer">
            <div class="local-legend-footer-container">
                <div class="footer-column">
                    <div class="footer-branding">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                             alt="Local Legend Stories Logo" class="footer-logo" onerror="this.style.display='none';">
                        <div class="footer-logo-text">
                            <div class="footer-logo-name">Local Legend<span>Stories</span></div>
                            <div class="footer-logo-tagline">Celebrating Local Business</div>
                        </div>
                    </div>
                    <p style="margin-top: 1rem;">Discover amazing local businesses and their inspiring stories.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Explore</h3>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>">Business Directory</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/interviews/' ) ); ?>">Interviews</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>">Submit Your Story</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Community</h3>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Recommend a Business</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Us</a></li>
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
                <p>&copy; <?php echo date( 'Y' ); ?> Local Legend Stories. All rights reserved.</p>
            </div>
        </footer>
        <?php
    }
    
    public static function cleanup_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('footer:not(.local-legend-footer), .site-footer, header:not(.local-legend-header), .site-header, .wp-block-navigation').forEach(el => {
                if (!el.classList.contains('local-legend-footer') && !el.classList.contains('local-legend-header')) {
                    el.style.display = 'none';
                }
            });
            document.querySelectorAll('a[href*="ivory-lark"], a[href*="hostingersite"]').forEach(el => el.style.display = 'none');
        });
        </script>
        <?php
    }
}

LBI_Header_Injector::init();

