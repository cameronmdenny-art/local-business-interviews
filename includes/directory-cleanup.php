<?php
/**
 * Directory Cleanup - Remove all test/demo data
 * Creates an admin page to safely delete all test business data
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Directory_Cleanup {
    
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
    }

    public static function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=directory',
            'Clean Directory',
            'Clean Directory',
            'manage_options',
            'lbi-cleanup-directory',
            array( __CLASS__, 'render_page' )
        );
    }

    public static function delete_all_directory_listings() {
        global $wpdb;
        
        // Get all directory posts
        $posts = get_posts( array(
            'post_type'      => 'directory',
            'post_status'    => 'any',
            'posts_per_page' => -1,
        ) );

        $deleted = 0;

        foreach ( $posts as $post ) {
            // Delete all post meta associated with this post
            $wpdb->delete( $wpdb->postmeta, array( 'post_id' => $post->ID ) );
            
            // Delete all term relationships
            $wpdb->delete( $wpdb->term_relationships, array( 'object_id' => $post->ID ) );
            
            // Delete the post itself
            wp_delete_post( $post->ID, true ); // true = force delete (skip trash)
            
            $deleted++;
        }

        return $deleted;
    }

    public static function render_page() {
        ?>
        <div class="wrap">
            <h1>Clean Directory Data</h1>
            <p style="font-size: 16px; padding: 15px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; margin: 20px 0;">
                ⚠️ <strong>Warning:</strong> This will permanently delete ALL business directory listings from the database. This cannot be undone.
            </p>

            <?php
            if ( isset( $_POST['lbi_delete_all_listings'] ) && check_admin_referer( 'lbi_delete_all_listings' ) ) {
                $deleted = self::delete_all_directory_listings();
                echo '<div class="notice notice-success"><p>✅ Successfully deleted ' . $deleted . ' directory listings. The directory is now empty and ready for your client businesses.</p></div>';
            }
            ?>

            <div style="background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 5px; max-width: 600px;">
                <h2>Remove All Test Data</h2>
                <p>Click the button below to remove all test and demo business listings from your directory.</p>
                
                <p><strong>This will delete:</strong></p>
                <ul style="margin-left: 20px;">
                    <li>Artisan Coffee Co.</li>
                    <li>Digital Dreams Marketing</li>
                    <li>EcoStyle Boutique</li>
                    <li>Momentum Fitness</li>
                    <li>Learn Together Academy</li>
                    <li>Morrison Woodworks Studio</li>
                    <li>Any other directory listings currently in the database</li>
                </ul>

                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field( 'lbi_delete_all_listings' ); ?>
                    
                    <p>
                        <strong>Are you sure? This cannot be undone.</strong>
                    </p>
                    
                    <button type="submit" name="lbi_delete_all_listings" class="button button-primary button-hero" style="background-color: #dc3545; border-color: #dc3545;">
                        🗑️ Delete All Directory Listings
                    </button>
                </form>
            </div>

            <div style="background: #e7f3ff; padding: 20px; border: 1px solid #b3d9ff; border-radius: 5px; margin-top: 30px; max-width: 600px;">
                <h3>Next Steps: Add Your Client Businesses</h3>
                <p>After cleaning the directory, you can add your client businesses in two ways:</p>
                
                <ol>
                    <li><strong>WordPress Admin:</strong> Go to <code>Directory</code> → <code>Add New</code> and create each business listing</li>
                    <li><strong>Bulk Import:</strong> Contact support if you have a CSV of businesses to import</li>
                </ol>

                <p><strong>Required fields for each business:</strong></p>
                <ul style="margin-left: 20px;">
                    <li>Business Name</li>
                    <li>Category (from dropdown)</li>
                    <li>Address / Service Area</li>
                    <li>Phone</li>
                    <li>Website (optional)</li>
                    <li>Service City (if applicable)</li>
                </ul>
            </div>
        </div>
        <?php
    }
}

LBI_Directory_Cleanup::init();
