<?php
/**
 * Plugin Name: Local Legend Emergency Header
 * Description: Emergency fallback header when main plugin fails
 * Version: 1.0
 */

// Ensure header shows even if main plugin fails
add_action( 'wp_head', function() {
    ?>
    <style id="local-legend-emergency-header">
        :root {
            --local-legend-gold: #bfa673;
            --local-legend-dark: #1a1a1a;
        }
        
        /* Hide theme elements */
        .site-header { display: none !important; }
        header.site-header { display: none !important; }
        .wp-block-navigation { display: none !important; }
        .site-footer { display: none !important; }
        footer.site-footer { display: none !important; }
        
        /* Emergency Header */
        .emergency-local-legend-header {
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
        
        .emergency-header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 2rem;
        }
        
        .emergency-header-left { display: flex; align-items: center; gap: 1.5rem; }
        .emergency-logo-img { height: 50px; width: auto; }
        .emergency-branding { display: flex; flex-direction: column; }
        .emergency-logo-text { font-size: 1.8rem; font-weight: 700; color: #ffffff; margin: 0; }
        .emergency-logo-text span { color: #bfa673; }
        .emergency-logo-sub { font-size: 0.85rem; color: #bfa673; font-weight: 600; margin: 0.3rem 0 0 0; text-transform: uppercase; }
        
        .emergency-nav { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .emergency-nav-item { display: inline-flex; padding: 0.75rem 1.2rem; color: #ffffff; text-decoration: none; font-weight: 500; border-radius: 8px; transition: all 0.3s ease; }
        .emergency-nav-item:hover { color: #bfa673; }
        
        body.has-emergency-header { padding-top: 100px !important; }
    </style>
    <?php
}, 1 );

add_action( 'wp_body_open', function() {
    ?>
    <header class="emergency-local-legend-header" id="emergency-header">
        <div class="emergency-header-container">
            <div class="emergency-header-left">
                <div class="emergency-branding">
                    <h1 class="emergency-logo-text">Local Legend<span>Stories</span></h1>
                    <p class="emergency-logo-sub">Celebrating Local Business</p>
                </div>
            </div>
            <nav class="emergency-nav">
                <a href="/" class="emergency-nav-item">Directory</a>
                <a href="/" class="emergency-nav-item">Submit Interview</a>
                <a href="/recommend/" class="emergency-nav-item">Recommend</a>
            </nav>
        </div>
    </header>
    <script>
        document.body.classList.add('has-emergency-header');
        document.documentElement.classList.add('has-emergency-header');
    </script>
    <?php
}, 1 );

// Disable the plugin that's causing errors
add_action( 'admin_init', function() {
    if ( is_plugin_active( 'local-business-interviews/local-business-interviews.php' ) ) {
        deactivate_plugins( 'local-business-interviews/local-business-interviews.php' );
    }
}, 1 );
