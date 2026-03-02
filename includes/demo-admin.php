<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Demo_Admin - Admin tool for demo data generation
 */
class LBI_Demo_Admin {
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
        add_action( 'admin_post_lbi_generate_demo', array( __CLASS__, 'generate_demo_data' ) );
    }

    public static function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=interview',
            'Demo Data',
            'Demo Data',
            'manage_options',
            'lbi-demo-data',
            array( __CLASS__, 'render_page' )
        );
    }

    public static function render_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Local Business Interviews - Demo Data', 'local-business-interviews' ); ?></h1>
            
            <div style="background: #fff; padding: 20px; border-radius: 5px; margin-top: 20px; max-width: 600px;">
                <h2><?php esc_html_e( 'Generate Sample Content', 'local-business-interviews' ); ?></h2>
                
                <p><?php esc_html_e( 'Click the button below to generate 6 sample interviews and 6 sample directory listings. This will help you see the full visual design of the plugin.', 'local-business-interviews' ); ?></p>
                
                <p style="color: #666; font-size: 14px;">
                    <?php esc_html_e( 'The demo data includes:', 'local-business-interviews' ); ?><br>
                    • 6 featured interviews with business details<br>
                    • 6 featured directory listings<br>
                    • Business categories and locations<br>
                    • All marked as featured to appear on the homepage
                </p>
                
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="lbi_generate_demo">
                    <?php wp_nonce_field( 'lbi_generate_demo_nonce' ); ?>
                    
                    <button type="submit" class="button button-primary button-large" style="margin-top: 20px;">
                        <?php esc_html_e( 'Generate Demo Data Now', 'local-business-interviews' ); ?>
                    </button>
                </form>
                
                <p style="color: #999; font-size: 13px; margin-top: 20px;">
                    <?php esc_html_e( 'Note: You can delete this demo data later by going to each post type and deleting the posts individually.', 'local-business-interviews' ); ?>
                </p>
            </div>
        </div>
        <?php
    }

    public static function generate_demo_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        check_admin_referer( 'lbi_generate_demo_nonce' );

        // Include demo data generator
        require_once LBI_PLUGIN_DIR . 'includes/demo-data.php';
        
        // Generate the data
        LBI_Demo_Data::generate();

        // Redirect back with success message
        wp_safe_remote_post( admin_url( 'edit.php?post_type=interview&page=lbi-demo-data' ), array(
            'blocking' => false,
        ) );

        wp_redirect( admin_url( 'edit.php?post_type=interview&page=lbi-demo-data&lbi_demo_generated=1' ) );
        exit;
    }
}

// Initialize if in admin
if ( is_admin() ) {
    LBI_Demo_Admin::init();
}
