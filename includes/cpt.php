<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_CPT {
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_types' ) );
    }

    public static function register_post_types() {
        // Interview CPT
        $labels = array(
            'name'               => __( 'Interviews', 'local-business-interviews' ),
            'singular_name'      => __( 'Interview', 'local-business-interviews' ),
            'add_new'            => __( 'Add New', 'local-business-interviews' ),
            'add_new_item'       => __( 'Add New Interview', 'local-business-interviews' ),
            'edit_item'          => __( 'Edit Interview', 'local-business-interviews' ),
            'new_item'           => __( 'New Interview', 'local-business-interviews' ),
            'view_item'          => __( 'View Interview', 'local-business-interviews' ),
            'search_items'       => __( 'Search Interviews', 'local-business-interviews' ),
            'not_found'          => __( 'No interviews found', 'local-business-interviews' ),
            'not_found_in_trash' => __( 'No interviews found in Trash', 'local-business-interviews' ),
            'all_items'          => __( 'Interview Submissions', 'local-business-interviews' ),
            'menu_name'          => __( 'Interviews', 'local-business-interviews' ),
            'name_admin_bar'     => __( 'Interview', 'local-business-interviews' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'show_in_menu'       => true,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-id',
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
            'capability_type'    => 'post',
            'rewrite'            => array( 'slug' => 'interviews' ),
        );
        register_post_type( 'interview', $args );

        // Directory CPT
        $labels = array(
            'name'               => __( 'Directory Listings', 'local-business-interviews' ),
            'singular_name'      => __( 'Directory Listing', 'local-business-interviews' ),
            'add_new'            => __( 'Add New', 'local-business-interviews' ),
            'add_new_item'       => __( 'Add New Listing', 'local-business-interviews' ),
            'edit_item'          => __( 'Edit Listing', 'local-business-interviews' ),
            'new_item'           => __( 'New Listing', 'local-business-interviews' ),
            'view_item'          => __( 'View Listing', 'local-business-interviews' ),
            'search_items'       => __( 'Search Listings', 'local-business-interviews' ),
            'not_found'          => __( 'No listings found', 'local-business-interviews' ),
            'not_found_in_trash' => __( 'No listings found in Trash', 'local-business-interviews' ),
            'all_items'          => __( 'Directory', 'local-business-interviews' ),
            'menu_name'          => __( 'Directory', 'local-business-interviews' ),
            'name_admin_bar'     => __( 'Directory Listing', 'local-business-interviews' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'show_in_menu'       => true,
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-store',
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'capability_type'    => 'post',
            'rewrite'            => array( 'slug' => 'directory' ),
        );
        register_post_type( 'directory', $args );
    }

    public static function activate() {
        self::register_post_types();
        self::ensure_all_pages();
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Ensure all required pages exist and have correct content
     */
    public static function ensure_all_pages() {
        $pages = array(
            array(
                'post_name' => 'directory',
                'post_title' => 'Directory',
                'post_content' => '[lbi_directory_form]',
            ),
            array(
                'post_name' => 'recommend-a-business',
                'post_title' => 'Recommend a Business',
                'post_content' => '[lbi_recommend_form]',
            ),
            array(
                'post_name' => 'submit-interview',
                'post_title' => 'Submit Interview',
                'post_content' => '[lbi_interview_form]',
            ),
            array(
                'post_name' => 'home',
                'post_title' => 'Home',
                'post_content' => '<p>Welcome to our Local Business Directory</p>',
            ),
        );

        foreach ( $pages as $page_data ) {
            $existing = get_page_by_path( $page_data['post_name'] );
            
            if ( $existing ) {
                // Update page content if empty
                if ( empty( $existing->post_content ) || ! strpos( $existing->post_content, '[lbi_' ) ) {
                    wp_update_post( array(
                        'ID'           => $existing->ID,
                        'post_content' => $page_data['post_content'],
                    ) );
                }
            } else {
                // Create new page
                wp_insert_post( array(
                    'post_title'   => $page_data['post_title'],
                    'post_content' => $page_data['post_content'],
                    'post_type'    => 'page',
                    'post_status'  => 'publish',
                    'post_name'    => $page_data['post_name'],
                ) );
            }
        }

        flush_rewrite_rules();
    }

    /**
     * Create the "Recommend a Business" page if it doesn't exist (legacy)
     */
    public static function create_recommend_page() {
        self::ensure_all_pages();
    }
}
