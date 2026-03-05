<?php
/**
 * Custom Apple/Google-Inspired Header for Cairde Designs
 * Unique floating design with glassmorphism
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class LBI_Custom_Header {
    
    public static function init() {
        add_action('wp_head', array(__CLASS__, 'render_header'), 1);
        add_filter('show_admin_bar', '__return_false'); // Hide admin bar for clean design
    }
    
    public static function render_header() {
        // Only on homepage
        if (!is_front_page()) return;
        
        $logo_url = plugin_dir_url(dirname(__FILE__)) . 'assets/images/cairde-logo.png';
        ?>
        
        <!-- Cairde Designs Custom Header -->
        <div class="cd-floating-header" id="cdHeader">
            <div class="cd-header-glass">
                <!-- Logo Section -->
                <div class="cd-logo-container">
                    <a href="<?php echo home_url('/'); ?>" class="cd-logo-link">
                        <img src="<?php echo esc_url($logo_url); ?>" 
                             alt="Cairde Designs" 
                             class="cd-logo-img"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="cd-logo-fallback" style="display: none;">
                            <span class="cd-logo-text">Cairde</span>
                            <span class="cd-logo-text-sub">Designs</span>
                        </div>
                    </a>
                    <p class="cd-tagline">Local Business Storytelling</p>
                </div>
                
                <!-- Unique Vertical Navigation -->
                <nav class="cd-nav-unique">
                    <div class="cd-nav-grid">
                        <a href="<?php echo home_url('/directory/'); ?>" class="cd-nav-item">
                            <span class="cd-nav-icon">🗂</span>
                            <span class="cd-nav-label">Directory</span>
                        </a>
                        <a href="<?php echo home_url('/submit-interview/'); ?>" class="cd-nav-item">
                            <span class="cd-nav-icon">✍️</span>
                            <span class="cd-nav-label">Submit</span>
                        </a>
                        <a href="<?php echo home_url('/recommend/'); ?>" class="cd-nav-item cd-nav-item-highlight">
                            <span class="cd-nav-icon">⭐</span>
                            <span class="cd-nav-label">Recommend</span>
                        </a>
                    </div>
                </nav>
                
                <!-- Scroll Indicator -->
                <div class="cd-scroll-indicator">
                    <div class="cd-scroll-arrow"></div>
                    <span class="cd-scroll-text">Explore</span>
                </div>
            </div>
            
            <!-- Ambient Background Effects -->
            <div class="cd-header-bg-effects">
                <div class="cd-glow cd-glow-1"></div>
                <div class="cd-glow cd-glow-2"></div>
                <div class="cd-glow cd-glow-3"></div>
            </div>
        </div>
        
        <script>
        // Smooth parallax and interactions
        (function() {
            const header = document.getElementById('cdHeader');
            if (!header) return;
            
            let scrolled = false;
            
            window.addEventListener('scroll', function() {
                const scrollY = window.pageYOffset;
                
                // Compact header on scroll
                if (scrollY > 100 && !scrolled) {
                    header.classList.add('cd-header-scrolled');
                    scrolled = true;
                } else if (scrollY <= 100 && scrolled) {
                    header.classList.remove('cd-header-scrolled');
                    scrolled = false;
                }
                
                // Parallax effect
                const scrollIndicator = header.querySelector('.cd-scroll-indicator');
                if (scrollIndicator && scrollY < 500) {
                    scrollIndicator.style.opacity = 1 - (scrollY / 500);
                    scrollIndicator.style.transform = `translateY(${scrollY * 0.3}px)`;
                }
            });
            
            // Nav item interactions
            const navItems = header.querySelectorAll('.cd-nav-item');
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px) scale(1.05)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            
            // Ambient glow animation
            const glows = header.querySelectorAll('.cd-glow');
            glows.forEach((glow, i) => {
                setInterval(() => {
                    const randomX = Math.random() * 20 - 10;
                    const randomY = Math.random() * 20 - 10;
                    glow.style.transform = `translate(${randomX}%, ${randomY}%)`;
                }, 3000 + (i * 1000));
            });
        })();
        </script>
        
        <?php
    }
}

// Initialize
LBI_Custom_Header::init();
