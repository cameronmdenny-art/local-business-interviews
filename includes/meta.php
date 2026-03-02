<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Meta - Register custom meta fields for Interview and Directory CPTs
 */
class LBI_Meta {
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_meta' ) );
    }

    public static function register_meta() {
        // Interview CPT meta fields
        self::register_interview_meta();
        // Directory CPT meta fields
        self::register_directory_meta();
    }

    /**
     * Register meta fields for Interview CPT
     */
    private static function register_interview_meta() {
        $interview_fields = array(
            // Interviewee info
            'interviewee_name'         => array( 'type' => 'string', 'show_in_rest' => true ),
            'interviewee_title'        => array( 'type' => 'string', 'show_in_rest' => true ),
            'company_name'             => array( 'type' => 'string', 'show_in_rest' => true ),
            'company_website'          => array( 'type' => 'string', 'show_in_rest' => true ),
            'email'                    => array( 'type' => 'string', 'show_in_rest' => true ),
            'phone'                    => array( 'type' => 'string', 'show_in_rest' => false ),
            'interview_transcript'     => array( 'type' => 'string', 'show_in_rest' => true ),
            'submission_date'          => array( 'type' => 'string', 'show_in_rest' => true ),
            'approval_status'          => array( 'type' => 'string', 'show_in_rest' => true ),
            'admin_notes'              => array( 'type' => 'string', 'show_in_rest' => false ),
            'featured_image_credit'    => array( 'type' => 'string', 'show_in_rest' => false ),
            'video_url'                => array( 'type' => 'string', 'show_in_rest' => true ),
            // Security & submitter info
            'submitter_ip'             => array( 'type' => 'string', 'show_in_rest' => false ),
            'submitter_user_agent'     => array( 'type' => 'string', 'show_in_rest' => false ),
            'submitter_user_id'        => array( 'type' => 'string', 'show_in_rest' => false ),
            'recaptcha_score'          => array( 'type' => 'number', 'show_in_rest' => false ),
            'recaptcha_action'         => array( 'type' => 'string', 'show_in_rest' => false ),
        );

        foreach ( $interview_fields as $field => $args ) {
            $default_args = array(
                'type'              => $args['type'] ?? 'string',
                'single'            => true,
                'show_in_rest'      => $args['show_in_rest'] ?? true,
                'sanitize_callback' => function( $value ) use ( $field ) {
                    return self::sanitize_meta( $field, $value );
                },
                'auth_callback'     => function() {
                    return current_user_can( 'edit_posts' );
                },
            );
            register_post_meta( 'interview', $field, $default_args );
        }
    }

    /**
     * Register meta fields for Directory CPT
     */
    private static function register_directory_meta() {
        $directory_fields = array(
            // Business info
            'business_name'            => array( 'type' => 'string', 'show_in_rest' => true ),
            'business_description'     => array( 'type' => 'string', 'show_in_rest' => true ),
            'website_url'              => array( 'type' => 'string', 'show_in_rest' => true ),
            'email'                    => array( 'type' => 'string', 'show_in_rest' => true ),
            'phone'                    => array( 'type' => 'string', 'show_in_rest' => true ),
            'address'                  => array( 'type' => 'string', 'show_in_rest' => true ),
            'hours_of_operation'       => array( 'type' => 'string', 'show_in_rest' => true ),
            'approval_status'          => array( 'type' => 'string', 'show_in_rest' => true ),
            'submission_date'          => array( 'type' => 'string', 'show_in_rest' => true ),
            'featured'                 => array( 'type' => 'boolean', 'show_in_rest' => true ),
            'admin_notes'              => array( 'type' => 'string', 'show_in_rest' => false ),
            // Social media (stored as JSON string)
            'social_media_links'       => array( 'type' => 'string', 'show_in_rest' => true ),
            // Security & submitter info
            'submitter_ip'             => array( 'type' => 'string', 'show_in_rest' => false ),
            'submitter_user_agent'     => array( 'type' => 'string', 'show_in_rest' => false ),
            'submitter_user_id'        => array( 'type' => 'string', 'show_in_rest' => false ),
            'recaptcha_score'          => array( 'type' => 'number', 'show_in_rest' => false ),
            'recaptcha_action'         => array( 'type' => 'string', 'show_in_rest' => false ),
        );

        foreach ( $directory_fields as $field => $args ) {
            $default_args = array(
                'type'              => $args['type'] ?? 'string',
                'single'            => true,
                'show_in_rest'      => $args['show_in_rest'] ?? true,
                'sanitize_callback' => function( $value ) use ( $field ) {
                    return self::sanitize_meta( $field, $value );
                },
                'auth_callback'     => function() {
                    return current_user_can( 'edit_posts' );
                },
            );
            register_post_meta( 'directory', $field, $default_args );
        }
    }

    /**
     * Sanitize meta field values based on field type
     *
     * @param string $field Field name
     * @param mixed $value Value to sanitize
     * @return mixed Sanitized value
     */
    private static function sanitize_meta( $field, $value ) {
        if ( empty( $value ) ) {
            return '';
        }

        switch ( $field ) {
            case 'email':
                return sanitize_email( $value );
            case 'company_website':
            case 'website_url':
            case 'video_url':
                return esc_url_raw( $value );
            case 'recaptcha_score':
                return floatval( $value );
            case 'featured':
                return (bool) $value;
            case 'interview_transcript':
            case 'business_description':
            case 'hours_of_operation':
                return wp_kses_post( $value );
            case 'social_media_links':
                return is_array( $value ) ? wp_json_encode( $value ) : $value;
            default:
                return sanitize_text_field( $value );
        }
    }
}
