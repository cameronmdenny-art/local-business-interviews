<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Schema {
    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'output_schema' ) );
    }

    public static function output_schema() {
        if ( is_singular( 'interview' ) ) {
            self::schema_interview();
        }
        if ( is_singular( 'directory' ) ) {
            self::schema_directory();
        }
    }

    public static function schema_interview() {
        global $post;
        $name = get_post_meta( $post->ID, 'business_name', true );
        $url  = get_permalink();
        $date = get_the_date( 'c', $post );
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Article',
            'headline' => $post->post_title,
            'datePublished' => $date,
            'author'   => array(
                '@type' => 'Person',
                'name'  => $name,
            ),
            'mainEntityOfPage' => $url,
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }

    public static function schema_directory() {
        global $post;
        $name = get_post_meta( $post->ID, 'business_name', true );
        $address = array(
            '@type' => 'PostalAddress',
            'streetAddress' => get_post_meta( $post->ID, 'address_street', true ),
            'addressLocality' => get_post_meta( $post->ID, 'address_city', true ),
            'addressRegion' => get_post_meta( $post->ID, 'address_state', true ),
            'postalCode' => get_post_meta( $post->ID, 'address_zip', true ),
        );
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'LocalBusiness',
            'name'     => $name,
            'address'  => $address,
            'telephone'=> get_post_meta( $post->ID, 'phone', true ),
            'url'      => get_post_meta( $post->ID, 'website', true ),
            'sameAs'   => array_filter( array(
                get_post_meta( $post->ID, 'social_facebook', true ),
                get_post_meta( $post->ID, 'social_instagram', true ),
                get_post_meta( $post->ID, 'social_linkedin', true ),
                get_post_meta( $post->ID, 'social_tiktok', true ),
                get_post_meta( $post->ID, 'social_youtube', true ),
            ) ),
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }
}
