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
    <style id="local-legend-footer-styles">
        /* Footer */
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
        
        /* Adjust body for fixed footer if needed */
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
            <!-- Branding -->
            <div class="footer-column">
                <div class="footer-branding">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo.png' ) ); ?>" 
                         alt="Local Legend Stories Logo"
                         class="footer-logo"
                         onerror="this.style.display='none';">
                    <div class="footer-logo-text">
                        <div class="footer-logo-name">Local Legend<span>Stories</span></div>
                        <div class="footer-logo-tagline">Celebrating Local Business</div>
                    </div>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; line-height: 1.6;">
                    Discover amazing local businesses and their inspiring stories. Support your community one interview at a time.
                </p>
            </div>
            
            <!-- Browse -->
            <div class="footer-column">
                <h3>Explore</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>">Business Directory</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/interviews/' ) ); ?>">Interviews</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>">Submit Your Story</a></li>
                </ul>
            </div>
            
            <!-- Community -->
            <div class="footer-column">
                <h3>Community</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Recommend a Business</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Us</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Legal -->
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> Local Legend Stories. All rights reserved. | <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></p>
        </div>
    </footer>
    
    <script>
    // Ensure footer displays properly
    (function() {
        const footer = document.getElementById('local-legend-footer');
        if (footer) {
            footer.style.position = 'relative';
            footer.style.zIndex = '1';
        }
    })();
    </script>
    <?php
}, 50 );

?>
