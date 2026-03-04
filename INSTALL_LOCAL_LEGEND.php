<?php
/**
 * EMERGENCY LOCAL LEGEND STORIES INSTALLER
 * 
 * Upload this file to /public_html/ on your server and access it via:
 * https://ivory-lark-138468.hostingersite.com/INSTALL_LOCAL_LEGEND.php
 * 
 * This will install all mu-plugins to rebrand your site as Local Legend Stories
 */

// Security check
$secret_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($secret_key !== 'deploy-local-legend-now-2026') {
    die('Access denied. Use: ?key=deploy-local-legend-now-2026');
}

// Define the mu-plugins directory
$mu_plugins_dir = dirname(__FILE__) . '/wp-content/mu-plugins';

// Create mu-plugins directory if it doesn't exist
if (!is_dir($mu_plugins_dir)) {
    mkdir($mu_plugins_dir, 0755, true);
}

echo "<h1>Local Legend Stories Installer</h1>";
echo "<p>Installing mu-plugins to: " . htmlspecialchars($mu_plugins_dir) . "</p>";

// ============================================================================
// FILE 1: mu-cairde-header-complete.php
// ============================================================================

$header_content = <<<'PHPCODE'
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
PHPCODE;

$header_file = $mu_plugins_dir . '/mu-cairde-header-complete.php';
if (file_put_contents($header_file, $header_content)) {
    echo "<p>✅ Created: mu-cairde-header-complete.php</p>";
} else {
    echo "<p>❌ Failed to create: mu-cairde-header-complete.php</p>";
}

// ============================================================================
// FILE 2: mu-local-legend-footer.php
// ============================================================================

$footer_content = file_get_contents(__DIR__ . '/mu-local-legend-footer.php');
if ($footer_content === false) {
    $footer_content = <<<'PHPCODE'
<?php
/**
 * Local Legend Stories Footer
 * 
 * Plugin Name: Local Legend Stories Footer
 * Description: Professional footer for Local Legend Stories brand
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_footer', function() {
    ?>
    <footer class="local-legend-footer" style="background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.99) 100%); border-top: 1px solid rgba(191, 166, 115, 0.3); padding: 2rem; color: #cccccc;">
        <div style="max-width: 1400px; margin: 0 auto; text-align: center;">
            <p style="color: #bfa673; font-size: 1.2rem; font-weight: 700;">Local Legend<span style="color: #ffffff;">Stories</span></p>
            <p style="font-size: 0.9rem; margin-top: 1rem;">&copy; <?php echo date('Y'); ?> Local Legend Stories. All rights reserved.</p>
        </div>
    </footer>
    <?php
}, 50 );

?>
PHPCODE;
}

$footer_file = $mu_plugins_dir . '/mu-local-legend-footer.php';
if (file_put_contents($footer_file, $footer_content)) {
    echo "<p>✅ Created: mu-local-legend-footer.php</p>";
} else {
    echo "<p>❌ Failed to create: mu-local-legend-footer.php</p>";
}

// ============================================================================
// FILE 3: mu-master-cleanup.php
// ============================================================================

$cleanup_content = file_get_contents(__DIR__ . '/mu-master-cleanup.php');
if ($cleanup_content === false) {
    $cleanup_content = <<<'PHPCODE'
<?php
/**
 * Master Cleanup Plugin
 * 
 * Plugin Name: Master Site Cleanup
 * Description: Hides WordPress theme elements
 * Version: 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_head', function() {
    ?>
    <style>
        .site-header, .site-footer, .wp-block-navigation { display: none !important; }
    </style>
    <?php
}, 1 );

?>
PHPCODE;
}

$cleanup_file = $mu_plugins_dir . '/mu-master-cleanup.php';
if (file_put_contents($cleanup_file, $cleanup_content)) {
    echo "<p>✅ Created: mu-master-cleanup.php</p>";
} else {
    echo "<p>❌ Failed to create: mu-master-cleanup.php</p>";
}

// ============================================================================
// CLEAR CACHE
// ============================================================================

echo "<h2>Clearing Cache...</h2>";

// Clear WordPress object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<p>✅ WordPress object cache cleared</p>";
}

// Clear LiteSpeed cache if it exists
if (class_exists('LiteSpeed_Cache_API')) {
    LiteSpeed_Cache_API::purge_all();
    echo "<p>✅ LiteSpeed cache purged</p>";
}

// Try to clear via .htaccess
$htaccess = dirname(__FILE__) . '/.htaccess';
if (file_exists($htaccess)) {
    $content = file_get_contents($htaccess);
    if (strpos($content, 'X-LiteSpeed-Purge') === false) {
        file_put_contents($htaccess, "\n# Clear cache\nHeader set X-LiteSpeed-Purge \"*\"\n", FILE_APPEND);
        echo "<p>✅ Added cache clear directive to .htaccess</p>";
    }
}

echo "<h2>✅ Installation Complete!</h2>";
echo "<p>Visit your site: <a href='https://ivory-lark-138468.hostingersite.com/'>https://ivory-lark-138468.hostingersite.com/</a></p>";
echo "<p><strong>IMPORTANT:</strong> Delete this file after verifying the site works!</p>";
echo "<p><small>MU-Plugins directory: " . htmlspecialchars($mu_plugins_dir) . "</small></p>";

// List installed files
echo "<h3>Installed Files:</h3><ul>";
$files = glob($mu_plugins_dir . '/*.php');
foreach ($files as $file) {
    echo "<li>" . basename($file) . " (" . filesize($file) . " bytes)</li>";
}
echo "</ul>";
