<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Security - Handle all security measures for the plugin
 * Includes: nonces, rate limiting, reCAPTCHA, IP logging, validation
 */
class LBI_Security {
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_recaptcha' ) );
        add_filter( 'lbi_validate_submission', array( __CLASS__, 'validate_submission' ), 10, 2 );
    }

    /**
     * Enqueue reCAPTCHA v3 script if enabled
     */
    public static function enqueue_recaptcha() {
        if ( ! self::is_recaptcha_enabled() ) {
            return;
        }

        $site_key = self::get_recaptcha_site_key();
        if ( empty( $site_key ) ) {
            return;
        }

        wp_enqueue_script(
            'lbi-recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $site_key ),
            array(),
            '3.0',
            false
        );

        wp_add_inline_script( 'lbi-recaptcha', self::get_recaptcha_init_script( $site_key ) );
    }

    /**
     * Get reCAPTCHA initialization script
     *
     * @param string $site_key reCAPTCHA site key
     * @return string JavaScript code
     */
    private static function get_recaptcha_init_script( $site_key ) {
        return <<<JS
        window['LBIRecaptcha'] = {
            siteKey: '{$site_key}',
            updateToken: function(action) {
                return grecaptcha.execute('{$site_key}', { action: action }).then(function(token) {
                    var input = document.querySelector('input[name="lbi_recaptcha_token"]');
                    if (input) {
                        input.value = token;
                    }
                    return token;
                });
            }
        };
        JS;
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public static function is_recaptcha_enabled() {
        $enabled = get_option( 'lbi_recaptcha_enabled', false );
        return apply_filters( 'lbi_recaptcha_enabled', (bool) $enabled );
    }

    /**
     * Get reCAPTCHA site key
     *
     * @return string
     */
    public static function get_recaptcha_site_key() {
        return get_option( 'lbi_recaptcha_site_key', '' ) 
            ?: ( defined( 'LBI_RECAPTCHA_SITE_KEY' ) ? LBI_RECAPTCHA_SITE_KEY : '' );
    }

    /**
     * Get reCAPTCHA secret key
     *
     * @return string
     */
    public static function get_recaptcha_secret_key() {
        return get_option( 'lbi_recaptcha_secret_key', '' ) 
            ?: ( defined( 'LBI_RECAPTCHA_SECRET_KEY' ) ? LBI_RECAPTCHA_SECRET_KEY : '' );
    }

    /**
     * Verify reCAPTCHA token
     *
     * @param string $token reCAPTCHA token from form
     * @return array Array with 'success' bool and 'score' float
     */
    public static function verify_recaptcha( $token ) {
        if ( ! self::is_recaptcha_enabled() || empty( $token ) ) {
            return array(
                'success' => true,
                'score'   => 1.0,
            );
        }

        $secret_key = self::get_recaptcha_secret_key();
        if ( empty( $secret_key ) ) {
            return array(
                'success' => false,
                'score'   => 0,
            );
        }

        $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $secret_key,
                'response' => sanitize_text_field( $token ),
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            LBI_Helpers::log( 
                'reCAPTCHA verification failed: ' . $response->get_error_message(), 
                'error' 
            );
            return array(
                'success' => false,
                'score'   => 0,
            );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        
        return array(
            'success' => isset( $body['success'] ) && $body['success'],
            'score'   => isset( $body['score'] ) ? floatval( $body['score'] ) : 0,
            'action'  => isset( $body['action'] ) ? sanitize_text_field( $body['action'] ) : '',
        );
    }

    /**
     * Validate full submission (all security checks)
     *
     * @param bool $valid Current validation status
     * @param array $data Submission data
     * @return bool|WP_Error True if valid, WP_Error if invalid
     */
    public static function validate_submission( $valid, $data ) {
        if ( is_wp_error( $valid ) ) {
            return $valid;
        }

        // Check honeypot field
        if ( ! self::check_honeypot( $data ) ) {
            LBI_Helpers::log( 'Honeypot field was filled - spam detected', 'warning' );
            return new WP_Error( 'spam_detected', __( 'Your submission could not be processed.', 'local-business-interviews' ) );
        }

        // Check rate limit
        if ( ! LBI_Helpers::check_rate_limit() ) {
            return new WP_Error(
                'rate_limit_exceeded',
                sprintf(
                    __( 'You have reached the submission limit. Please try again in %d minutes.', 'local-business-interviews' ),
                    apply_filters( 'lbi_rate_limit_reset_minutes', 60 )
                )
            );
        }

        // Verify reCAPTCHA if enabled
        if ( self::is_recaptcha_enabled() && ! empty( $data['recaptcha_token'] ) ) {
            $recaptcha_result = self::verify_recaptcha( $data['recaptcha_token'] );
            
            if ( ! $recaptcha_result['success'] ) {
                return new WP_Error(
                    'recaptcha_failed',
                    __( 'Please verify that you are human. Try again.', 'local-business-interviews' )
                );
            }

            // Check score threshold
            $score_threshold = apply_filters( 'lbi_recaptcha_score_threshold', 0.5 );
            if ( $recaptcha_result['score'] < $score_threshold ) {
                LBI_Helpers::log(
                    sprintf(
                        'reCAPTCHA score too low: %f (threshold: %f)',
                        $recaptcha_result['score'],
                        $score_threshold
                    ),
                    'warning'
                );
                return new WP_Error(
                    'recaptcha_score_low',
                    __( 'Your submission was flagged as suspicious. Please try again.', 'local-business-interviews' )
                );
            }

            // Store reCAPTCHA data for admin review
            $data['recaptcha_score'] = $recaptcha_result['score'];
            $data['recaptcha_action'] = $recaptcha_result['action'] ?? '';
        }

        // Validate all email fields
        $email_fields = array( 'email' );
        foreach ( $email_fields as $field ) {
            if ( ! empty( $data[ $field ] ) ) {
                if ( ! LBI_Helpers::validate_email( $data[ $field ] ) ) {
                    return new WP_Error(
                        'invalid_email',
                        sprintf( __( 'Please enter a valid email address for %s', 'local-business-interviews' ), $field )
                    );
                }
            }
        }

        // Validate all URL fields
        $url_fields = array( 'company_website', 'website_url', 'video_url' );
        foreach ( $url_fields as $field ) {
            if ( ! empty( $data[ $field ] ) ) {
                if ( ! LBI_Helpers::validate_url( $data[ $field ] ) ) {
                    return new WP_Error(
                        'invalid_url',
                        sprintf( __( 'Please enter a valid URL for %s', 'local-business-interviews' ), $field )
                    );
                }
            }
        }

        return true;
    }

    /**
     * Check honeypot field (anti-spam)
     *
     * @param array $data Form data
     * @return bool True if honeypot is empty (valid), false if filled (spam)
     */
    public static function check_honeypot( $data ) {
        return empty( $data['lbi_honeypot'] );
    }

    /**
     * Output honeypot field HTML
     *
     * @return void
     */
    public static function honeypot_field() {
        ?>
        <div style="position: absolute; left: -9999px; opacity: 0;">
            <input type="text" name="lbi_honeypot" value="" tabindex="-1" autocomplete="off" aria-hidden="true">
        </div>
        <?php
    }

    /**
     * Output reCAPTCHA hidden field
     *
     * @return void
     */
    public static function recaptcha_field() {
        if ( ! self::is_recaptcha_enabled() ) {
            return;
        }
        ?>
        <input type="hidden" name="lbi_recaptcha_token" value="">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window['LBIRecaptcha'] && window['LBIRecaptcha']['updateToken']) {
                    window['LBIRecaptcha']['updateToken']('submit');
                }
            });
        </script>
        <?php
    }

    /**
     * Log submission metadata (IP, user agent, etc.)
     *
     * @param int $post_id Post ID
     */
    public static function log_submission( $post_id ) {
        $ip = LBI_Helpers::get_user_ip();
        $user_agent = LBI_Helpers::get_user_agent();
        $user_id = get_current_user_id();

        update_post_meta( $post_id, 'submitter_ip', $ip );
        update_post_meta( $post_id, 'submitter_user_agent', $user_agent );
        
        if ( $user_id > 0 ) {
            update_post_meta( $post_id, 'submitter_user_id', $user_id );
        }

        // Log to debug log
        LBI_Helpers::log(
            sprintf(
                'New submission: post_id=%d, ip=%s, user_id=%d',
                $post_id,
                $ip,
                $user_id
            ),
            'info'
        );
    }

    /**
     * Get IP reputation check URL
     *
     * @param string $ip IP address
     * @return string URL for manual IP reputation check
     */
    public static function get_ip_reputation_url( $ip ) {
        return 'https://www.abuseipdb.com/check/' . urlencode( $ip );
    }

    /**
     * Detect suspicious submission patterns
     *
     * @param int $post_id Post ID
     * @return array Array of suspicious indicators
     */
    public static function get_submission_risk_factors( $post_id ) {
        $risk_factors = array();
        $ip = get_post_meta( $post_id, 'submitter_ip', true );
        $recaptcha_score = get_post_meta( $post_id, 'recaptcha_score', true );

        // Check if same IP has multiple submissions in short time
        if ( ! empty( $ip ) ) {
            $recent_count = count( get_posts( array(
                'post_type'  => array( 'interview', 'directory' ),
                'meta_query' => array(
                    array(
                        'key'   => 'submitter_ip',
                        'value' => $ip,
                    ),
                ),
                'date_query' => array(
                    array(
                        'after' => '1 hour ago',
                    ),
                ),
                'fields'     => 'ids',
            ) ) );

            if ( $recent_count > 3 ) {
                $risk_factors[] = __( 'Multiple submissions from same IP in short time', 'local-business-interviews' );
            }
        }

        // Check reCAPTCHA score
        if ( ! empty( $recaptcha_score ) && floatval( $recaptcha_score ) < 0.7 ) {
            $risk_factors[] = sprintf(
                __( 'Low reCAPTCHA score: %f', 'local-business-interviews' ),
                floatval( $recaptcha_score )
            );
        }

        return apply_filters( 'lbi_submission_risk_factors', $risk_factors, $post_id );
    }

    /**
     * Sanitize and validate submission data
     *
     * @param array $data Raw submission data
     * @return array|WP_Error Sanitized data or error
     */
    public static function sanitize_submission_data( $data ) {
        $sanitized = array();

        // Text fields
        $text_fields = array(
            'interviewee_name',
            'interviewee_title',
            'company_name',
            'business_name',
            'phone',
        );
        foreach ( $text_fields as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $sanitized[ $field ] = LBI_Helpers::sanitize_text( $data[ $field ] );
            }
        }

        // Email fields
        if ( isset( $data['email'] ) ) {
            $sanitized['email'] = sanitize_email( $data['email'] );
        }

        // URL fields
        $url_fields = array(
            'company_website',
            'website_url',
            'video_url',
        );
        foreach ( $url_fields as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $sanitized[ $field ] = esc_url_raw( $data[ $field ] );
            }
        }

        // HTML content fields
        $html_fields = array(
            'interview_transcript',
            'business_description',
            'hours_of_operation',
        );
        foreach ( $html_fields as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $sanitized[ $field ] = wp_kses_post( $data[ $field ] );
            }
        }

        // Textarea fields
        $textarea_fields = array(
            'address',
            'admin_notes',
        );
        foreach ( $textarea_fields as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $sanitized[ $field ] = sanitize_textarea_field( $data[ $field ] );
            }
        }

        // Array/JSON fields
        if ( isset( $data['social_media_links'] ) ) {
            if ( is_array( $data['social_media_links'] ) ) {
                $sanitized['social_media_links'] = array_map( function( $item ) {
                    return array(
                        'platform' => sanitize_text_field( $item['platform'] ?? '' ),
                        'url' => esc_url_raw( $item['url'] ?? '' ),
                    );
                }, $data['social_media_links'] );
            } else {
                $sanitized['social_media_links'] = $data['social_media_links'];
            }
        }

        // Boolean fields
        $bool_fields = array( 'featured', 'confirm_accuracy', 'confirm_permission', 'confirm_contact' );
        foreach ( $bool_fields as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $sanitized[ $field ] = (bool) $data[ $field ];
            }
        }

        return $sanitized;
    }

    /**
     * Verify form nonce
     *
     * @param string $nonce_field Name of nonce field
     * @param string $action Nonce action
     * @return bool True if nonce is valid
     */
    public static function verify_form_nonce( $nonce_field, $action ) {
        if ( ! isset( $_POST[ $nonce_field ] ) ) {
            return false;
        }
        return wp_verify_nonce( $_POST[ $nonce_field ], $action ) !== false;
    }

    /**
     * Create form nonce field
     *
     * @param string $action Nonce action
     * @param string $field_name Field name (optional)
     * @return void
     */
    public static function nonce_field( $action, $field_name = '' ) {
        if ( empty( $field_name ) ) {
            $field_name = '_lbi_nonce_' . $action;
        }
        wp_nonce_field( $action, $field_name );
    }
}
