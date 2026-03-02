<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Admin {
    public static function init() {
        add_filter( 'manage_interview_posts_columns', array( __CLASS__, 'columns' ) );
        add_action( 'manage_interview_posts_custom_column', array( __CLASS__, 'column_content' ), 10, 2 );
        add_filter( 'post_row_actions', array( __CLASS__, 'row_actions' ), 10, 2 );
        add_action( 'admin_post_lbi_approve', array( __CLASS__, 'approve_handler' ) );
        add_action( 'transition_post_status', array( __CLASS__, 'maybe_sync_on_publish' ), 10, 3 );
        add_action( 'admin_notices', array( __CLASS__, 'notices' ) );
    }

    public static function columns( $cols ) {
        $new = array();
        $new['cb'] = $cols['cb'];
        $new['business_name'] = __( 'Business', 'local-business-interviews' );
        $new['category'] = __( 'Category', 'local-business-interviews' );
        $new['city'] = __( 'City', 'local-business-interviews' );
        $new['email'] = __( 'Email', 'local-business-interviews' );
        $new['date'] = $cols['date'];
        return $new;
    }

    public static function column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'business_name':
                echo esc_html( get_post_meta( $post_id, 'business_name', true ) );
                break;
            case 'category':
                $terms = get_the_terms( $post_id, 'business_category' );
                if ( $terms ) {
                    $names = wp_list_pluck( $terms, 'name' );
                    echo esc_html( implode( ', ', $names ) );
                }
                break;
            case 'city':
                $terms = get_the_terms( $post_id, 'service_city' );
                if ( $terms ) {
                    $names = wp_list_pluck( $terms, 'name' );
                    echo esc_html( implode( ', ', $names ) );
                }
                break;
            case 'email':
                echo esc_html( get_post_meta( $post_id, 'email', true ) );
                break;
        }
    }

    public static function row_actions( $actions, $post ) {
        if ( 'interview' === $post->post_type && current_user_can( 'edit_post', $post->ID ) ) {
            $url = wp_nonce_url( admin_url( 'admin-post.php?action=lbi_approve&post=' . $post->ID ), 'lbi_approve' );
            $actions['lbi_approve'] = '<a href="' . esc_url( $url ) . '">' . __( 'Approve & Publish', 'local-business-interviews' ) . '</a>';
        }
        return $actions;
    }

    public static function approve_handler() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( __( 'Permission denied', 'local-business-interviews' ) );
        }
        $post_id = intval( $_GET['post'] ?? 0 );
        if ( ! $post_id || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'lbi_approve' ) ) {
            wp_die( __( 'Invalid request', 'local-business-interviews' ) );
        }
        // set status to publish
        wp_update_post( array( 'ID' => $post_id, 'post_status' => 'publish' ) );
        // create/update directory
        LBI_Admin::sync_directory( $post_id );
        wp_redirect( add_query_arg( array( 'post_type' => 'interview', 'lbi_approved' => 1 ), admin_url( 'edit.php' ) ) );
        exit;
    }

    public static function notices() {
        if ( isset( $_GET['lbi_approved'] ) ) {
            echo '<div class="updated notice"><p>' . __( 'Interview approved and directory synced.', 'local-business-interviews' ) . '</p></div>';
        }
    }

    public static function maybe_sync_on_publish( $new_status, $old_status, $post ) {
        if ( 'interview' !== $post->post_type ) {
            return;
        }
        if ( 'publish' === $new_status && $old_status !== 'publish' ) {
            self::sync_directory( $post->ID );
        }
    }

    public static function sync_directory( $interview_id ) {
        $email = get_post_meta( $interview_id, 'email', true );
        $name  = get_post_meta( $interview_id, 'business_name', true );
        $normalized = LBI_Helpers::normalize_name( $name );

        $query_args = array(
            'post_type' => 'directory',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'email',
                    'value' => $email,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'business_name_normalized',
                    'value' => $normalized,
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => 1,
        );
        $existing = get_posts( $query_args );
        if ( $existing ) {
            $dir_id = $existing[0]->ID;
        } else {
            $dir_id = wp_insert_post( array(
                'post_type' => 'directory',
                'post_title' => $name,
                'post_status' => 'publish',
            ) );
        }

        // copy meta from interview to directory
        $fields = array(
            'business_name', 'owner_first', 'owner_last', 'email', 'phone', 'website',
            'address_street', 'address_city', 'address_state', 'address_zip',
            'service_area', 'years_in_business', 'tagline',
            'logo_id', 'gallery_ids',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_linkedin', 'social_youtube',
        );
        foreach ( $fields as $f ) {
            update_post_meta( $dir_id, $f, get_post_meta( $interview_id, $f, true ) );
        }
        // normalized name meta for matching
        update_post_meta( $dir_id, 'business_name_normalized', $normalized );
        update_post_meta( $dir_id, 'related_interview_id', $interview_id );

        // taxonomies
        $cats = wp_get_post_terms( $interview_id, 'business_category', array( 'fields' => 'slugs' ) );
        wp_set_post_terms( $dir_id, $cats, 'business_category' );
        $cities = wp_get_post_terms( $interview_id, 'service_city', array( 'fields' => 'slugs' ) );
        wp_set_post_terms( $dir_id, $cities, 'service_city' );
    }
}
