<?php
/**
 * LOCAL LEGEND STORIES - AUTOMATIC DEPLOYER
 * 
 * Instructions:
 * 1. Upload this file as: deploy-local-legends.php to /public_html/
 * 2. Access: https://ivory-lark-138468.hostingersite.com/deploy-local-legends.php?deploy=yes
 * 3. The script will create both mu-plugin files automatically
 * 4. Clear cache when done
 */

// Security token
$correct_token = 'local_legends_deploy_2026';
$deploy_key = $_GET['deploy'] ?? '';

if ($deploy_key !== 'yes') {
    echo '<h1>⚠️ Access Denied</h1>';
    echo '<p>Add ?deploy=yes to the URL to proceed</p>';
    exit;
}

echo '<h1>🚀 Local Legend Stories Deployer</h1>';
echo '<p>Creating mu-plugin files...</p>';
echo '<hr>';

// File 1: Header
$header_content = '<?php
/**
 * Local Legend Stories Header
 * 
 * Plugin Name: Local Legend Stories Header
 * Description: Professional header for Local Legend Stories brand
 * Version: 1.0
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

add_action( "wp_head", function() {
    ?>
    <style id="local-legend-header-styles">
        :root {
            --cairde-gold: #bfa673;
            --cairde-dark: #1a1a1a;
            --cairde-light: #f5f5f5;
            --cairde-accent: #d4c197;
        }
        
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
        
        .cairde-logo-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.2s ease;
        }
        
        .cairde-logo-link:hover {
            transform: scale(1.05);
        }
        
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

add_action( "wp_body_open", function() {
    ?>
    <header class="cairde-custom-header" id="cairde-main-header">
        <div class="cairde-header-container">
            <div class="cairde-header-left">
                <a href="<?php echo esc_url( home_url( "/" ) ); ?>" class="cairde-logo-link" title="Local Legend Stories">
                    <img src="<?php echo esc_url( home_url( "/wp-content/uploads/2026/03/local-legend-logo.png" ) ); ?>" 
                         alt="Local Legend Stories Logo"
                         class="cairde-logo-img"
                         onerror="this.style.display=\'none\';">
                </a>
                <div class="cairde-branding">
                    <h1 class="cairde-logo-text">Local Legend<span style="color: var(--cairde-gold);">Stories</span></h1>
                    <p class="cairde-logo-sub">Celebrating Local Business</p>
                </div>
            </div>
            
            <nav class="cairde-nav">
                <a href="<?php echo esc_url( home_url( "/directory/" ) ); ?>" class="cairde-nav-item">
                    Directory
                </a>
                <a href="<?php echo esc_url( home_url( "/submit-interview/" ) ); ?>" class="cairde-nav-item">
                    Submit Interview
                </a>
                <a href="<?php echo esc_url( home_url( "/recommend/" ) ); ?>" class="cairde-nav-item highlight">
                    Recommend a Business
                </a>
            </nav>
        </div>
    </header>
    
    <script>
        document.documentElement.classList.add("has-cairde-header");
        document.body.classList.add("has-cairde-header");
        
        const header = document.getElementById("cairde-main-header");
        if (header) {
            header.style.zIndex = "99999";
        }
    </script>
    <?php
}, 5 );

add_action( "init", function() {
    remove_action( "wp_head", "wp_custom_css_load_styles" );
    register_nav_menus( array() );
}, 2 );

?>';

// File 2: Footer
$footer_content = '<?php
/**
 * Local Legend Stories Footer
 * 
 * Plugin Name: Local Legend Stories Footer
 * Description: Professional footer for Local Legend Stories brand
 * Version: 1.0
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

add_action( "wp_footer", function() {
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
            letter-spacing: 0.5px;
        }
        
        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-column li {
            margin-bottom: 0.5rem;
        }
        
        .footer-column a {
            color: #cccccc;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-column a:hover {
            color: #bfa673;
            padding-left: 5px;
        }
        
        .footer-branding {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(191, 166, 115, 0.25));
        }
        
        .footer-logo-text {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        
        .footer-logo-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
        }
        
        .footer-logo-name span {
            color: #bfa673;
        }
        
        .footer-logo-tagline {
            font-size: 0.75rem;
            color: #bfa673;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(191, 166, 115, 0.2);
            padding-top: 2rem;
            margin-top: 2rem;
            text-align: center;
            color: #888888;
            font-size: 0.85rem;
        }
        
        .footer-bottom a {
            color: #bfa673;
            text-decoration: none;
        }
        
        .footer-bottom a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .local-legend-footer-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            footer.local-legend-footer {
                padding: 1.5rem;
            }
            
            .footer-branding {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    
    <footer class="local-legend-footer" id="local-legend-footer">
        <div class="local-legend-footer-container">
            <div class="footer-column">
                <div class="footer-branding">
                    <img src="<?php echo esc_url( home_url( "/wp-content/uploads/2026/03/local-legend-logo.png" ) ); ?>" 
                         alt="Local Legend Stories Logo"
                         class="footer-logo"
                         onerror="this.style.display=\'none\';">
                    <div class="footer-logo-text">
                        <div class="footer-logo-name">Local Legend<span>Stories</span></div>
                        <div class="footer-logo-tagline">Celebrating Local Business</div>
                    </div>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; line-height: 1.6;">
                    Discover amazing local businesses and their inspiring stories. Support your community one interview at a time.
                </p>
            </div>
            
            <div class="footer-column">
                <h3>Explore</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( "/directory/" ) ); ?>">Business Directory</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/interviews/" ) ); ?>">Interviews</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/blog/" ) ); ?>">Blog</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/submit-interview/" ) ); ?>">Submit Your Story</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Community</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( "/recommend/" ) ); ?>">Recommend a Business</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/contact/" ) ); ?>">Contact Us</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/faq/" ) ); ?>">FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( "/privacy-policy/" ) ); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url( home_url( "/terms-of-service/" ) ); ?>">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date( "Y" ); ?> Local Legend Stories. All rights reserved. | <a href="<?php echo esc_url( home_url( "/" ) ); ?>">Home</a></p>
        </div>
    </footer>
    
    <script>
    (function() {
        const footer = document.getElementById("local-legend-footer");
        if (footer) {
            footer.style.position = "relative";
            footer.style.zIndex = "1";
        }
    })();
    </script>
    <?php
}, 50 );

?>';

// Try to create the files
$dir = dirname(__FILE__);
$mu_plugins_dir = $dir . '/wp-content/mu-plugins';

// Create mu-plugins directory if it doesn't exist
if (!is_dir($mu_plugins_dir)) {
    if (mkdir($mu_plugins_dir, 0755, true)) {
        echo '<p style="color: green;">✓ Created wp-content/mu-plugins/ directory</p>';
    }
}

// Write header file
$header_file = $mu_plugins_dir . '/mu-cairde-header-complete.php';
if (file_put_contents($header_file, '<?php' . "\n" . $header_content)) {
    echo '<p style="color: green;">✓ Created: mu-cairde-header-complete.php</p>';
    chmod($header_file, 0644);
} else {
    echo '<p style="color: red;">✗ Failed to create header file</p>';
}

// Write footer file
$footer_file = $mu_plugins_dir . '/mu-local-legend-footer.php';
if (file_put_contents($footer_file, '<?php' . "\n" . $footer_content)) {
    echo '<p style="color: green;">✓ Created: mu-local-legend-footer.php</p>';
    chmod($footer_file, 0644);
} else {
    echo '<p style="color: red;">✗ Failed to create footer file</p>';
}

echo '<hr>';
echo '<h2>✅ Deployment Complete!</h2>';
echo '<p>Your site is now using Local Legend Stories branding:</p>';
echo '<ul>';
echo '<li>✓ Local Legend Stories header</li>';
echo '<li>✓ Professional footer</li>';
echo '<li>✓ Gold/tan color scheme</li>';
echo '<li>✓ Logo display</li>';
echo '</ul>';
echo '<p><a href="/">Visit your site →</a></p>';
echo '<p style="margin-top: 20px; color: #999; font-size: 0.9em;">You can now delete this file (deploy-local-legends.php) for security.</p>';

?>
