<?php
/**
 * Master Cleanup Plugin
 * 
 * Plugin Name: Master Site Cleanup
 * Description: Comprehensive cleanup of WordPress theme elements, hides Hostinger domain references, shows only custom Cairde Designs header
 * Version: 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ============================================================================
// 1. HIDE ALL WORDPRESS THEME HEADERS, NAVIGATION, AND FOOTERS WITH CSS
// ============================================================================

add_action( 'wp_head', function() {
    ?>
    <style id="master-cleanup-styles">
        /* COMPLETELY HIDE WORDPRESS THEME HEADER */
        .site-header,
        header.site-header,
        .wp-block-template-part[aria-label*="Header"],
        [data-type="core/template-part"][data-title*="Header"],
        .wp-block-group[data-wp-interactive*="core/navigation"],
        
        /* Hide WordPress navigation blocks */
        .wp-block-navigation,
        .wp-block-navigation-submenu,
        nav.wp-block-navigation,
        
        /* Hide theme menus */
        .primary-menu,
        .main-navigation,
        nav.site-navigation,
        .navbar-header,
        
        /* Hide any header-like elements showing domain */
        .site-branding,
        .site-title,
        .site-title a,
        .site-description,
        
        /* Hide all footer elements except custom footer */
        .site-footer,
        footer.site-footer,
        [data-type="core/template-part"][data-title*="Footer"],
        .wp-block-template-part[aria-label*="Footer"],
        .footer-widgets,
        .footer-content,
        
        /* Hide the URL/domain text if it appears anywhere */
        a[href*="ivory-lark"],
        a[href*="hostingersite"],
        [class*="ivory-lark"],
        [id*="ivory-lark"],
        
        /* Hide WordPress block elements in header/footer */
        .wp-block-group > .wp-block-navigation,
        .wp-block-group > nav,
        
        /* Hide theme-specific header/footer regions */
        [data-region="header-top"],
        [data-region="footer"],
        .site-header-inner,
        .header-wrapper,
        
        /* Hide block template parts that aren't ours */
        .wp-block-template-part:not(.custom-footer),
        
        /* Aggressive hide for any remaining site headers/navs */
        .custom-header,
        .entry-header > nav,
        .wp-site-blocks > .wp-block-navigation {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
        }
        
        /* Fine-tune body layout after hiding headers */
        body {
            padding-top: 0 !important;
        }
        
        /* Ensure custom headers float on top */
        #cdHeader,
        .cd-floating-header,
        .custom-cairde-header {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 99999 !important;
        }
        
        /* Hide any inline text showing domain (fallback for JS) */
        body > :first-child:contains("ivory-lark"),
        a:contains("ivory-lark") {
            display: none !important;
        }
    </style>
    <?php
}, 1 );

// ============================================================================
// 2. FILTER OUT WORDPRESS NAVIGATION MENUS - DON'T RENDER THEM
// ============================================================================

// Disable default WordPress menu rendering
add_filter( 'wp_nav_menu', function( $menu, $args ) {
    // Return empty for any wp_nav_menu calls except our custom ones
    if ( ! isset( $args->theme_location ) || ! in_array( $args->theme_location, [ 'custom-cairde' ] ) ) {
        return '';
    }
    return $menu;
}, 10, 2 );

// ============================================================================
// 3. REMOVE WORDPRESS ADMIN BAR TO CLEAN UP INTERFACE
// ============================================================================

add_filter( 'show_admin_bar', '__return_false' );

// ============================================================================
// 4. HIDE SITE TITLE AND TAGLINE IN HTML
// ============================================================================

add_filter( 'bloginfo', function( $output, $show = '' ) {
    if ( $show === 'name' || $show === 'description' ) {
        // Filter out any output that might display Hostinger domain
        if ( strpos( $output, 'ivory-lark' ) !== false || strpos( $output, 'hostingersite' ) !== false ) {
            return '';
        }
    }
    return $output;
}, 10, 2 );

// ============================================================================
// 5. INTERCEPT AND REMOVE THEME HEADER OUTPUT
// ============================================================================

// Remove the default header template part
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

// Remove all WordPress default header hooks
remove_all_actions( 'get_header_template_part_attributes' );

// ============================================================================
// 6. FOOTER CLEANUP - REMOVE WORDPRESS FOOTERS
// ============================================================================

add_action( 'wp_footer', function() {
    // Remove footer by injecting CSS that hides it and shows ours
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide all WordPress footers
        document.querySelectorAll('footer, .site-footer, [data-type="core/template-part"]').forEach(el => {
            // Don't hide custom Cairde footer
            if (!el.classList.contains('custom-cairde-footer') && !el.id.includes('custom')) {
                el.style.display = 'none';
            }
        });
        
        // Hide any links containing the domain
        document.querySelectorAll('a[href*="ivory-lark"], a[href*="hostingersite"]').forEach(el => {
            el.style.display = 'none';
        });
        
        // Hide text nodes containing domain
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );
        
        let node;
        const nodesToRemove = [];
        while (node = walker.nextNode()) {
            if (node.textContent.includes('ivory-lark') || node.textContent.includes('hostingersite')) {
                nodesToRemove.push(node);
            }
        }
        
        nodesToRemove.forEach(node => {
            // Only remove if it's just the domain, not part of a larger element
            if (node.parentElement && !node.parentElement.querySelector('a')) {
                node.parentElement.style.display = 'none';
            }
        });
        
        // Ensure only Cairde headers are visible
        document.querySelectorAll('.cd-floating-header, .cd-header-glass, #cdHeader').forEach(el => {
            el.style.display = 'block';
            el.style.visibility = 'visible';
            el.style.zIndex = '99999';
            el.style.position = 'relative';
        });
    });
    </script>
    <?php
}, 1 );

// ============================================================================
// 7. DISABLE WORDPRESS BLOCKS IN TEMPLATES - ONLY SHOW CUSTOM CONTENT
// ============================================================================

add_filter( 'render_block_core/template-part', function( $output, $parsed_block ) {
    // Don't render WordPress template parts (header, footer, etc)
    $area = isset( $parsed_block['attrs']['area'] ) ? $parsed_block['attrs']['area'] : '';
    
    // Hide header and footer template parts
    if ( in_array( $area, [ 'header', 'footer', 'sidebar', 'navigation' ] ) ) {
        return '';
    }
    
    return $output;
}, 10, 2 );

// ============================================================================
// 8. ENSURE CUSTOM HEADER IS THE ONLY HEADER
// ============================================================================

add_action( 'wp_body_open', function() {
    // This is called right after <body>, ensure no other headers load before ours
    ?>
    <script>
    // Immediately hide any siblings that might be headers
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const children = Array.from(body.children);
        
        children.forEach(child => {
            // Skip the Cairde header
            if (child.id === 'cdHeader' || child.classList.contains('cd-floating-header')) {
                return;
            }
            
            // Hide anything that looks like a WordPress header before our custom one
            if (child.tagName === 'HEADER' || 
                child.tagName === 'NAV' ||
                child.classList.contains('site-header') ||
                child.classList.contains('wp-block-navigation')) {
                child.style.display = 'none';
            }
        });
    });
    </script>
    <?php
}, 1 );

// ============================================================================
// 9. CLEAN UP BODY CLASSES TO PREVENT CSS CONFLICTS
// ============================================================================

add_filter( 'body_class', function( $classes ) {
    // Keep only essential classes
    $allowed = [ 'wp-embed-responsive', 'has-block-support' ];
    $classes = array_intersect( $classes, $allowed );
    return $classes;
} );

// ============================================================================
// 10. final NUCLEAR OPTION - JAVASCRIPT CLEANUP ON EVERY PAGE
// ============================================================================

add_action( 'wp_footer', function() {
    ?>
    <script>
    // Final cleanup script - runs at the very end
    window.addEventListener('load', function() {
        // Triple-check: hide all WordPress headers/footers
        document.querySelectorAll('header, footer, nav').forEach(el => {
            const text = el.textContent || '';
            
            // If it contains WordPress theme elements or domain, hide it
            if (text.includes('ivory-lark') || 
                text.includes('hostingersite') ||
                el.classList.contains('site-header') ||
                el.classList.contains('site-footer') ||
                el.classList.contains('wp-block-navigation')) {
                
                // Don't hide our custom Cairde elements
                if (!el.id.includes('cdHeader') && !el.classList.contains('cd-')) {
                    el.style.display = 'none !important';
                    el.setAttribute('aria-hidden', 'true');
                }
            }
        });
        
        // Hide any anchor tags pointing to the domain
        document.querySelectorAll('a[href*="ivory-lark"]').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show only Cairde headers
        document.querySelectorAll('#cdHeader, .cd-floating-header').forEach(el => {
            el.style.display = 'block';
        });
    });
    </script>
    <?php
}, 99999 ); // Very late, after everything else

?>
