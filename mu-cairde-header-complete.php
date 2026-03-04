<?php
/**
 * Cairde Designs Custom header - Complete Replacement
 * 
 * Plugin Name: Cairde Header Complete
 * Description: Displays the professional Cairde Designs header with correct logo and navigation
 * Version: 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_head', function() {
    ?>
    <style id="cairde-custom-header-styles">
        /* Global font setup */
        :root {
            --cairde-gold: #bfa673;
            --cairde-dark: #1a1a1a;
            --cairde-light: #f5f5f5;
            --cairde-accent: #d4c197;
        }
        
        /* Custom Cairde Header */
        .cairde-custom-header {
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
        
        .cairde-header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 2rem;
        }
        
        .cairde-header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .cairde-logo-img {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(191, 166, 115, 0.25));
        }
        
        .cairde-branding {
            display: flex;
            flex-direction: column;
        }
        
        .cairde-logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0;
            line-height: 1;
        }
        
        .cairde-logo-sub {
            font-size: 0.85rem;
            color: var(--cairde-gold);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0.3rem 0 0 0;
        }
        
        /* Navigation */
        .cairde-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: flex-end;
        }
        
        .cairde-nav-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .cairde-nav-item:hover {
            color: var(--cairde-gold);
            border-color: rgba(191, 166, 115, 0.4);
            background-color: rgba(191, 166, 115, 0.1);
        }
        
        .cairde-nav-item.highlight {
            background: linear-gradient(135deg, rgba(191, 166, 115, 0.25) 0%, rgba(212, 193, 151, 0.15) 100%);
            border-color: rgba(191, 166, 115, 0.4);
            color: var(--cairde-gold);
        }
        
        .cairde-nav-item.highlight:hover {
            background: linear-gradient(135deg, rgba(191, 166, 115, 0.35) 0%, rgba(212, 193, 151, 0.25) 100%);
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .cairde-custom-header {
                padding: 1rem;
            }
            
            .cairde-header-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .cairde-header-left {
                gap: 1rem;
            }
            
            .cairde-logo-text {
                font-size: 1.3rem;
            }
            
            .cairde-nav {
                width: 100%;
                flex-wrap: wrap;
                justify-content: flex-start;
            }
            
            .cairde-nav-item {
                flex: 1;
                min-width: calc(50% - 0.25rem);
                justify-content: center;
                font-size: 0.85rem;
                padding: 0.6rem;
            }
        }
        
        /* Small screens */
        @media (max-width: 480px) {
            .cairde-nav-item {
                min-width: 100%;
                margin-bottom: 0.25rem;
            }
        }
        
        /* Logo link */
        .cairde-logo-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.2s ease;
        }
        
        .cairde-logo-link:hover {
            transform: scale(1.05);
        }
        
        /* Adjust main content to account for fixed header */
        body.has-cairde-header {
            padding-top: 100px;
        }
        
        @media (max-width: 768px) {
            body.has-cairde-header {
                padding-top: 120px;
            }
        }
    </style>
    <?php
}, 5 );

add_action( 'wp_body_open', function() {
    ?>
    <header class="cairde-custom-header" id="cairde-main-header">
        <div class="cairde-header-container">
            <div class="cairde-header-left">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cairde-logo-link" title="Local Legend Stories">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                         alt="Local Legend Stories Logo"
                         class="cairde-logo-img"
                         onerror="this.style.display='none';">
                </a>
                <div class="cairde-branding">
                    <h1 class="cairde-logo-text">Local Legend<span style="color: var(--cairde-gold);">Stories</span></h1>
                    <p class="cairde-logo-sub">Celebrating Local Business</p>
                </div>
            </div>
            
            <nav class="cairde-nav">
                <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="cairde-nav-item">
                    Directory
                </a>
                <a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="cairde-nav-item">
                    Submit Interview
                </a>
                <a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>" class="cairde-nav-item highlight">
                    Recommend a Business
                </a>
            </nav>
        </div>
    </header>
    
    <script>
        // Add body class for styling
        document.documentElement.classList.add('has-cairde-header');
        document.body.classList.add('has-cairde-header');
        
        // Ensure header stays on top
        const header = document.getElementById('cairde-main-header');
        if (header) {
            header.style.zIndex = '99999';
        }
    </script>
    <?php
}, 5 );

// ============================================================================
// HIDE ANY OTHER HEADERS THAT WORDPRESS TRIES TO RENDER
// ============================================================================

add_action( 'init', function() {
    // Remove default theme headers
    remove_action( 'wp_head', 'wp_custom_css_load_styles' );
    
    // Remove the default WordPress navigation
    register_nav_menus( array() );
}, 2 );

?>
