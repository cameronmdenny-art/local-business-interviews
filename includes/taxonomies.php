<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Taxonomies {
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
    }

    public static function register_taxonomies() {
        // Business Category
        $labels = array(
            'name'          => __( 'Business Categories', 'local-business-interviews' ),
            'singular_name' => __( 'Business Category', 'local-business-interviews' ),
        );
        register_taxonomy( 'business_category', array( 'interview', 'directory' ), array(
            'labels'            => $labels,
            'public'            => true,
            'hierarchical'      => false,
            'rewrite'           => array( 'slug' => 'business-category' ),
        ) );

        // Service area / city
        $labels = array(
            'name'          => __( 'Service Cities', 'local-business-interviews' ),
            'singular_name' => __( 'Service City', 'local-business-interviews' ),
        );
        register_taxonomy( 'service_city', array( 'interview', 'directory' ), array(
            'labels'            => $labels,
            'public'            => true,
            'hierarchical'      => false,
            'rewrite'           => array( 'slug' => 'city' ),
        ) );
    }
}
