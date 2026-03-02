<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_REST {
    public static function init() {
        add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
    }

    public static function register_routes() {
        register_rest_route( 'lbi/v1', '/interview', array(
            'methods'             => 'POST',
            'callback'            => array( __CLASS__, 'handle_submission' ),
            'permission_callback' => array( __CLASS__, 'auth_callback' ),
        ) );
    }

    public static function auth_callback( $request ) {
        // require token if defined
        if ( defined( 'LBI_API_TOKEN' ) && LBI_API_TOKEN ) {
            $token = $request->get_header( 'x-lbi-token' ) ?: $request->get_param( 'lbi_token' );
            if ( $token !== LBI_API_TOKEN ) {
                return new WP_Error( 'forbidden', 'Invalid API token', array( 'status' => 403 ) );
            }
        }
        return true;
    }

    public static function handle_submission( WP_REST_Request $request ) {
        $params = $request->get_json_params();
        if ( empty( $params['business_name'] ) || empty( $params['owner_first'] ) || empty( $params['email'] ) ) {
            return new WP_Error( 'missing_fields', 'Required fields missing', array( 'status' => 400 ) );
        }
        // rate-limit by IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limit = apply_filters( 'lbi_max_submissions_per_hour', 5 );
        $key = 'lbi_rest_sub_' . sanitize_key( $ip );
        $cnt = (int) get_transient( $key );
        if ( $cnt >= $limit ) {
            return new WP_Error( 'too_many', 'Too many submissions', array( 'status' => 429 ) );
        }
        // optional recaptcha
        if ( defined( 'LBI_RECAPTCHA_SECRET' ) && LBI_RECAPTCHA_SECRET ) {
            $rec = sanitize_text_field( $params['g-recaptcha-response'] ?? '' );
            if ( $rec ) {
                $resp = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
                    'body' => array(
                        'secret'   => LBI_RECAPTCHA_SECRET,
                        'response' => $rec,
                        'remoteip' => $ip,
                    ),
                ) );
                $body = wp_remote_retrieve_body( $resp );
                $result = json_decode( $body );
                if ( ! $result || empty( $result->success ) ) {
                    return new WP_Error( 'recaptcha_failed', 'reCAPTCHA failed', array( 'status' => 400 ) );
                }
            } else {
                return new WP_Error( 'recaptcha_required', 'reCAPTCHA required', array( 'status' => 400 ) );
            }
        }
        $data = array();
        $fields = array(
            'business_name','owner_first','owner_last','email','phone','website',
            'address_street','address_city','address_state','address_zip',
            'service_area','years_in_business','tagline',
            'social_instagram','social_facebook','social_tiktok','social_linkedin','social_youtube',
            'q1','q2','q3','q4','q5','q6','q7','q8',
            'confirm_accuracy','confirm_permission','confirm_contact',
        );
        foreach ( $fields as $f ) {
            $val = isset( $params[ $f ] ) ? sanitize_text_field( $params[ $f ] ) : '';
            if ( 'email' === $f && $val && ! is_email( $val ) ) {
                return new WP_Error( 'invalid_email', 'Email address invalid', array( 'status' => 400 ) );
            }
            if ( 'website' === $f && $val ) {
                $val = esc_url_raw( $val );
            }
            $data[ $f ] = $val;
        }

        $post_id = wp_insert_post( array(
            'post_type'   => 'interview',
            'post_status' => 'pending',
            'post_title'  => sanitize_text_field( $data['business_name'] ),
            'post_content'=> '',
        ) );
        if ( $post_id && isset( $_SERVER['REMOTE_ADDR'] ) ) {
            update_post_meta( $post_id, 'submit_ip', sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) );
        }
        if ( is_wp_error( $post_id ) ) {
            return $post_id;
        }
        foreach ( $data as $k => $v ) {
            if ( $v !== '' ) {
                update_post_meta( $post_id, $k, $v );
            }
        }
        // increment counter
        set_transient( $key, $cnt + 1, HOUR_IN_SECONDS );
        // taxonomy terms
        if ( ! empty( $params['business_category'] ) ) {
            wp_set_post_terms( $post_id, array( sanitize_text_field( $params['business_category'] ) ), 'business_category' );
        }
        if ( ! empty( $params['service_area'] ) ) {
            $cities = is_array( $params['service_area'] ) ? $params['service_area'] : explode( ',', $params['service_area'] );
            $cities = array_map( 'sanitize_text_field', $cities );
            wp_set_post_terms( $post_id, $cities, 'service_city' );
        }

        return array( 'success' => true, 'post_id' => $post_id );
    }
}
