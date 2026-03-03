<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Directory_Search {
    public static function init() {
        add_action( 'pre_get_posts', array( __CLASS__, 'filter_directory_query' ) );
    }

    public static function filter_directory_query( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }

        if ( ! is_post_type_archive( 'directory' ) && ! is_tax( array( 'business_category', 'service_city' ) ) ) {
            return;
        }

        $query->set( 'post_type', 'directory' );
        $query->set( 'post_status', 'publish' );
        $query->set( 'posts_per_page', 18 );
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );

        $tax_query  = (array) $query->get( 'tax_query' );
        $meta_query = array( 'relation' => 'AND' );

        $category = isset( $_GET['business_category'] ) ? sanitize_text_field( wp_unslash( $_GET['business_category'] ) ) : '';
        if ( $category !== '' ) {
            $tax_query[] = array(
                'taxonomy' => 'business_category',
                'field'    => 'slug',
                'terms'    => sanitize_title( $category ),
            );
        }

        $name = isset( $_GET['business_name'] ) ? sanitize_text_field( wp_unslash( $_GET['business_name'] ) ) : '';
        if ( $name !== '' ) {
            $query->set( 's', $name );
            $meta_query[] = array(
                'key'     => 'business_name',
                'value'   => $name,
                'compare' => 'LIKE',
            );
        }

        $location = isset( $_GET['location'] ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : '';
        if ( $location !== '' ) {
            $location_slug = sanitize_title( $location );
            $city_term     = get_term_by( 'slug', $location_slug, 'service_city' );

            if ( ! $city_term ) {
                $city_term = get_term_by( 'name', $location, 'service_city' );
            }

            if ( $city_term && ! is_wp_error( $city_term ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'service_city',
                    'field'    => 'term_id',
                    'terms'    => array( (int) $city_term->term_id ),
                );
            } else {
                $meta_query[] = array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'address',
                        'value'   => $location,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'service_area',
                        'value'   => $location,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'address_city',
                        'value'   => $location,
                        'compare' => 'LIKE',
                    ),
                );
            }
        }

        if ( count( $tax_query ) > 1 ) {
            $tax_query['relation'] = 'AND';
            $query->set( 'tax_query', $tax_query );
        } elseif ( ! empty( $tax_query ) ) {
            $query->set( 'tax_query', $tax_query );
        }

        if ( count( $meta_query ) > 1 ) {
            $query->set( 'meta_query', $meta_query );
        }
    }
}
