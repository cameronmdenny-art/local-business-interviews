<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Helpers - Utility functions for Local Business Interviews plugin
 */
class LBI_Helpers {
    /**
     * Get the current user's IP address
     *
     * @return string IP address
     */
    public static function get_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            // May contain multiple IPs, get the first one
            $ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
            $ip = trim( $ips[0] );
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        return sanitize_text_field( $ip );
    }

    /**
     * Get user agent string
     *
     * @return string User agent
     */
    public static function get_user_agent() {
        return sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ?? '' );
    }

    /**
     * Generate a secure random token
     *
     * @return string Random token
     */
    public static function generate_token() {
        return wp_hash( wp_rand() . time() );
    }

    /**
     * Sanitize and validate email
     *
     * @param string $email Email to validate
     * @return string|false Sanitized email or false if invalid
     */
    public static function validate_email( $email ) {
        $sanitized = sanitize_email( $email );
        if ( is_email( $sanitized ) ) {
            return $sanitized;
        }
        return false;
    }

    /**
     * Sanitize and validate URL
     *
     * @param string $url URL to validate
     * @return string|false Sanitized URL or false if invalid
     */
    public static function validate_url( $url ) {
        if ( empty( $url ) ) {
            return false;
        }
        $sanitized = esc_url_raw( $url );
        if ( ! empty( $sanitized ) && wp_http_validate_url( $sanitized ) ) {
            return $sanitized;
        }
        return false;
    }

    /**
     * Sanitize text field
     *
     * @param string $text Text to sanitize
     * @return string Sanitized text
     */
    public static function sanitize_text( $text ) {
        return sanitize_text_field( $text );
    }

    /**
     * Sanitize HTML content (allows limited HTML)
     *
     * @param string $content Content to sanitize
     * @return string Sanitized content
     */
    public static function sanitize_html( $content ) {
        $allowed_html = array(
            'p'      => array(),
            'br'     => array(),
            'strong' => array(),
            'em'     => array(),
            'u'      => array(),
            'a'      => array( 'href' => true, 'title' => true ),
            'ul'     => array(),
            'ol'     => array(),
            'li'     => array(),
            'h1'     => array(),
            'h2'     => array(),
            'h3'     => array(),
            'h4'     => array(),
            'h5'     => array(),
            'h6'     => array(),
            'blockquote' => array(),
        );
        return wp_kses( $content, $allowed_html );
    }

    /**
     * Normalize business name (for deduplication)
     *
     * @param string $name Business name
     * @return string Normalized name
     */
    public static function normalize_name( $name ) {
        $name = strtolower( trim( $name ) );
        $name = preg_replace( '/[^a-z0-9]+/', '', $name );
        return $name;
    }

    /**
     * Check rate limit for IP address
     *
     * @param string $ip IP address
     * @return bool True if under limit, false if exceeded
     */
    public static function check_rate_limit( $ip = '' ) {
        if ( empty( $ip ) ) {
            $ip = self::get_user_ip();
        }
        $limit = apply_filters( 'lbi_max_submissions_per_hour', 5 );
        $key = 'lbi_submissions_' . sanitize_key( $ip );
        $count = (int) get_transient( $key );
        
        if ( $count >= $limit ) {
            return false;
        }
        
        set_transient( $key, $count + 1, HOUR_IN_SECONDS );
        return true;
    }

    /**
     * Get submission limit remaining for IP
     *
     * @param string $ip IP address
     * @return int Submissions remaining
     */
    public static function get_submissions_remaining( $ip = '' ) {
        if ( empty( $ip ) ) {
            $ip = self::get_user_ip();
        }
        $limit = apply_filters( 'lbi_max_submissions_per_hour', 5 );
        $key = 'lbi_submissions_' . sanitize_key( $ip );
        $count = (int) get_transient( $key );
        return max( 0, $limit - $count );
    }

    /**
     * Log action to error log
     *
     * @param string $message Log message
     * @param string $level Log level (error, warning, info)
     */
    public static function log( $message, $level = 'info' ) {
        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( sprintf( '[LBI_%s] %s', strtoupper( $level ), $message ) );
        }
    }

    /**
     * Get max upload file size (in bytes)
     *
     * @return int Max file size
     */
    public static function get_max_upload_size() {
        return apply_filters( 'lbi_max_upload_size', 5 * 1024 * 1024 ); // 5MB default
    }

    /**
     * Validate file upload
     *
     * @param array $file File array from $_FILES
     * @return array|WP_Error File validation result
     */
    public static function validate_file_upload( $file ) {
        if ( empty( $file ) || empty( $file['name'] ) ) {
            return new WP_Error( 'no_file', __( 'No file selected', 'local-business-interviews' ) );
        }

        // Check file size
        $max_size = self::get_max_upload_size();
        if ( $file['size'] > $max_size ) {
            return new WP_Error(
                'file_too_large',
                sprintf( __( 'File size exceeds %s limit', 'local-business-interviews' ), size_format( $max_size ) )
            );
        }

        // Check file type
        $allowed_types = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );
        $file_type = wp_check_filetype( $file['name'] );
        
        if ( ! in_array( $file_type['type'], $allowed_types, true ) ) {
            return new WP_Error(
                'invalid_file_type',
                __( 'File type not allowed. Please upload a JPG, PNG, GIF, or WebP image', 'local-business-interviews' )
            );
        }

        return $file;
    }

    /**
     * Get featured interviews for homepage
     *
     * @param int $count Number of interviews to retrieve
     * @return WP_Query Query object with featured interviews
     */
    public static function get_featured_interviews( $count = 6 ) {
        return new WP_Query( array(
            'post_type'      => 'interview',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
    }

    /**
     * Get featured directory entries for homepage
     *
     * @param int $count Number of entries to retrieve
     * @return WP_Query Query object with featured directory entries
     */
    public static function get_featured_directory( $count = 6 ) {
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key'     => 'featured',
                'value'   => '1',
                'compare' => '=',
            ),
            array(
                'key'     => 'featured',
                'compare' => 'NOT EXISTS',
            ),
        );

        return new WP_Query( array(
            'post_type'      => 'directory',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => $meta_query,
        ) );
    }

    /**
     * Get related posts by taxonomy
     *
     * @param int $post_id Post ID
     * @param string $post_type Post type
     * @param string $taxonomy Taxonomy name
     * @param int $count Number of related posts
     * @return WP_Query Query object with related posts
     */
    public static function get_related_posts( $post_id, $post_type = 'interview', $taxonomy = 'business_category', $count = 3 ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return new WP_Query( array( 'post__in' => array( 0 ) ) );
        }

        $term_ids = wp_list_pluck( $terms, 'term_id' );

        return new WP_Query( array(
            'post_type'      => $post_type,
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'post__not_in'   => array( $post_id ),
            'tax_query'      => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                ),
            ),
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
    }

    /**
     * Escape and display text
     *
     * @param string $text Text to escape
     * @return void
     */
    public static function display_text( $text ) {
        echo esc_html( $text );
    }

    /**
     * Escape and display HTML
     *
     * @param string $html HTML to escape
     * @return void
     */
    public static function display_html( $html ) {
        echo wp_kses_post( $html );
    }

    /**
     * Get timezone offset for datetime
     *
     * @return string Timezone string
     */
    public static function get_timezone() {
        return wp_timezone_string();
    }

    /**
     * Format date for display
     *
     * @param string $date Date string
     * @param string $format Date format (default: WordPress default)
     * @return string Formatted date
     */
    public static function format_date( $date, $format = '' ) {
        if ( empty( $format ) ) {
            $format = get_option( 'date_format' );
        }
        return date_i18n( $format, strtotime( $date ) );
    }

    /**
     * Create nonce for forms
     *
     * @param string $action Nonce action name
     * @return string Nonce field HTML
     */
    public static function create_nonce_field( $action ) {
        return wp_nonce_field( $action, '_lbi_nonce_' . $action, true, false );
    }

    /**
     * Verify nonce from form
     *
     * @param string $action Nonce action name
     * @return bool True if nonce is valid
     */
    public static function verify_nonce( $action ) {
        return isset( $_POST[ '_lbi_nonce_' . $action ] ) && 
               wp_verify_nonce( $_POST[ '_lbi_nonce_' . $action ], $action );
    }
}
