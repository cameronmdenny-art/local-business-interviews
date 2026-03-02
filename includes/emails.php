<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Emails - Email notification system for submissions, approvals, and rejections
 */
class LBI_Emails {
    public static function init() {
        add_action( 'lbi_submission_received', array( __CLASS__, 'send_submission_confirmation' ) );
        add_action( 'lbi_submission_approved', array( __CLASS__, 'send_approval_notification' ) );
        add_action( 'lbi_submission_rejected', array( __CLASS__, 'send_rejection_notification' ) );
        add_action( 'lbi_new_submission', array( __CLASS__, 'send_admin_new_submission_notification' ) );
    }

    /**
     * Send submission confirmation email to user
     *
     * @param int $post_id Post ID
     */
    public static function send_submission_confirmation( $post_id ) {
        $post_type = get_post_type( $post_id );
        $email = get_post_meta( $post_id, 'email', true );
        
        if ( ! $email || ! is_email( $email ) ) {
            return;
        }

        $post_title = get_the_title( $post_id );
        $subject = sprintf(
            __( 'Thank you - Your %s submission has been received', 'local-business-interviews' ),
            'interview' === $post_type ? 'Interview' : 'Directory'
        );

        $message = self::get_submission_confirmation_template( $post_id, $post_type );
        
        self::send_email( $email, $subject, $message );
    }

    /**
     * Send approval notification email to user
     *
     * @param int $post_id Post ID
     */
    public static function send_approval_notification( $post_id ) {
        $post_type = get_post_type( $post_id );
        $email = get_post_meta( $post_id, 'email', true );
        
        if ( ! $email || ! is_email( $email ) ) {
            return;
        }

        $post_title = get_the_title( $post_id );
        $subject = sprintf(
            __( 'Your %s has been published!', 'local-business-interviews' ),
            'interview' === $post_type ? 'interview' : 'business listing'
        );

        $message = self::get_approval_template( $post_id, $post_type );
        
        self::send_email( $email, $subject, $message );
    }

    /**
     * Send rejection notification email to user
     *
     * @param int $post_id Post ID
     */
    public static function send_rejection_notification( $post_id ) {
        $post_type = get_post_type( $post_id );
        $email = get_post_meta( $post_id, 'email', true );
        $admin_notes = get_post_meta( $post_id, 'admin_notes', true );
        
        if ( ! $email || ! is_email( $email ) ) {
            return;
        }

        $subject = sprintf(
            __( 'Your %s submission - Please review', 'local-business-interviews' ),
            'interview' === $post_type ? 'interview' : 'directory'
        );

        $message = self::get_rejection_template( $post_id, $post_type, $admin_notes );
        
        self::send_email( $email, $subject, $message );
    }

    /**
     * Send admin notification for new submission
     *
     * @param int $post_id Post ID
     */
    public static function send_admin_new_submission_notification( $post_id ) {
        $post_type = get_post_type( $post_id );
        $admin_email = get_option( 'admin_email' );
        
        $post_title = get_the_title( $post_id );
        $submitter_name = get_post_meta( $post_id, 'interviewee_name', true ) 
                         ?: get_post_meta( $post_id, 'business_name', true );
        
        $subject = sprintf(
            __( '[New Submission] %s - %s', 'local-business-interviews' ),
            ucfirst( str_replace( '-', ' ', $post_type ) ),
            $submitter_name
        );

        $message = self::get_admin_new_submission_template( $post_id, $post_type, $submitter_name );
        
        self::send_email( $admin_email, $subject, $message );
    }

    /**
     * Get submission confirmation email template
     *
     * @param int $post_id Post ID
     * @param string $post_type Post type
     * @return string Email HTML content
     */
    private static function get_submission_confirmation_template( $post_id, $post_type ) {
        $site_name = get_bloginfo( 'name' );
        $site_url = home_url();
        
        ob_start();
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.6; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                    <h2><?php esc_html_e( 'Thank You for Your Submission!', 'local-business-interviews' ); ?></h2>
                    
                    <p><?php esc_html_e( 'We have received your submission and it is now under review by our team.', 'local-business-interviews' ); ?></p>
                    
                    <p><?php esc_html_e( 'Your submission details:', 'local-business-interviews' ); ?></p>
                    <ul style="background: #f5f5f5; padding: 15px; border-radius: 4px;">
                        <li><strong><?php echo 'interview' === $post_type ? __( 'Interviewee Name', 'local-business-interviews' ) : __( 'Business Name', 'local-business-interviews' ); ?>:</strong> <?php echo esc_html( get_the_title( $post_id ) ); ?></li>
                        <li><strong><?php esc_html_e( 'Submission Date', 'local-business-interviews' ); ?>:</strong> <?php echo esc_html( LBI_Helpers::format_date( get_post_meta( $post_id, 'submission_date', true ) ) ); ?></li>
                        <li><strong><?php esc_html_e( 'Submission ID', 'local-business-interviews' ); ?>:</strong> #<?php echo esc_html( $post_id ); ?></li>
                    </ul>
                    
                    <p><?php esc_html_e( 'We will review your submission and notify you once it has been published or if we need any additional information.', 'local-business-interviews' ); ?></p>
                    
                    <p style="margin-top: 30px; font-size: 12px; color: #666;">
                        <?php printf( 
                            __( 'Best regards,<br>The %s Team', 'local-business-interviews' ), 
                            esc_html( $site_name ) 
                        ); ?>
                    </p>
                </div>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get approval notification email template
     *
     * @param int $post_id Post ID
     * @param string $post_type Post type
     * @return string Email HTML content
     */
    private static function get_approval_template( $post_id, $post_type ) {
        $site_name = get_bloginfo( 'name' );
        $post_url = get_permalink( $post_id );
        
        ob_start();
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.6; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                    <h2 style="color: #27ae60;"><?php esc_html_e( 'Your submission is now live!', 'local-business-interviews' ); ?></h2>
                    
                    <p><?php esc_html_e( 'Great news! Your submission has been approved and is now published on our website.', 'local-business-interviews' ); ?></p>
                    
                    <p style="margin: 30px 0;">
                        <a href="<?php echo esc_url( $post_url ); ?>" style="display: inline-block; padding: 12px 24px; background-color: #bfa673; color: white; text-decoration: none; border-radius: 4px;">
                            <?php esc_html_e( 'View Your Published Post', 'local-business-interviews' ); ?>
                        </a>
                    </p>
                    
                    <p><?php esc_html_e( 'Share this with your network and let your customers know about your feature on our platform!', 'local-business-interviews' ); ?></p>
                    
                    <p style="margin-top: 30px; font-size: 12px; color: #666;">
                        <?php printf( 
                            __( 'Best regards,<br>The %s Team', 'local-business-interviews' ), 
                            esc_html( $site_name ) 
                        ); ?>
                    </p>
                </div>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get rejection notification email template
     *
     * @param int $post_id Post ID
     * @param string $post_type Post type
     * @param string $admin_notes Admin notes
     * @return string Email HTML content
     */
    private static function get_rejection_template( $post_id, $post_type, $admin_notes = '' ) {
        $site_name = get_bloginfo( 'name' );
        $support_email = get_option( 'admin_email' );
        
        ob_start();
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.6; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                    <h2><?php esc_html_e( 'Your Submission - Please Review', 'local-business-interviews' ); ?></h2>
                    
                    <p><?php esc_html_e( 'Thank you for your submission. Unfortunately, we were unable to publish it at this time.', 'local-business-interviews' ); ?></p>
                    
                    <?php if ( ! empty( $admin_notes ) ) : ?>
                        <p><strong><?php esc_html_e( 'Reason:', 'local-business-interviews' ); ?></strong></p>
                        <p style="background: #f5f5f5; padding: 15px; border-left: 4px solid #e74c3c; border-radius: 4px;">
                            <?php echo wp_kses_post( $admin_notes ); ?>
                        </p>
                    <?php endif; ?>
                    
                    <p><?php printf( 
                        __( 'If you believe this is an error or would like to discuss your submission further, please contact us at %s.', 'local-business-interviews' ), 
                        esc_html( $support_email ) 
                    ); ?></p>
                    
                    <p style="margin-top: 30px; font-size: 12px; color: #666;">
                        <?php printf( 
                            __( 'Best regards,<br>The %s Team', 'local-business-interviews' ), 
                            esc_html( $site_name ) 
                        ); ?>
                    </p>
                </div>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get admin notification template for new submission
     *
     * @param int $post_id Post ID
     * @param string $post_type Post type
     * @param string $submitter_name Submitter name
     * @return string Email HTML content
     */
    private static function get_admin_new_submission_template( $post_id, $post_type, $submitter_name ) {
        $admin_url = admin_url( "edit.php?post_type=$post_type&post=$post_id" );
        $approve_url = wp_nonce_url( 
            admin_url( "admin-post.php?action=lbi_approve&post=$post_id" ), 
            'lbi_approve' 
        );
        
        ob_start();
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.6; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                    <h2><?php esc_html_e( 'New Submission Received', 'local-business-interviews' ); ?></h2>
                    
                    <p><?php printf( 
                        __( 'A new %s submission from %s is waiting for your review.', 'local-business-interviews' ), 
                        'interview' === $post_type ? 'interview' : 'directory',
                        esc_html( $submitter_name )
                    ); ?></p>
                    
                    <p style="margin: 30px 0;">
                        <a href="<?php echo esc_url( $admin_url ); ?>" style="display: inline-block; padding: 12px 24px; background-color: #0073aa; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">
                            <?php esc_html_e( 'Review Submission', 'local-business-interviews' ); ?>
                        </a>
                        <a href="<?php echo esc_url( $approve_url ); ?>" style="display: inline-block; padding: 12px 24px; background-color: #27ae60; color: white; text-decoration: none; border-radius: 4px;">
                            <?php esc_html_e( 'Approve & Publish', 'local-business-interviews' ); ?>
                        </a>
                    </p>
                    
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #e0e0e0;">
                    
                    <p style="margin-top: 20px; font-size: 12px; color: #666;">
                        <?php esc_html_e( 'This is an automated notification. Please do not reply to this email.', 'local-business-interviews' ); ?>
                    </p>
                </div>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Send email using WordPress wp_mail function
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email message body (HTML)
     * @return bool True if email was sent
     */
    private static function send_email( $to, $subject, $message ) {
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
        );

        $result = wp_mail( $to, $subject, $message, $headers );
        
        if ( ! $result ) {
            LBI_Helpers::log( 
                sprintf( 'Failed to send email to %s: %s', $to, $subject ),
                'error'
            );
        }

        return $result;
    }
}
