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
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}
