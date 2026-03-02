<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LBI_Forms - Handle interview and directory submission forms
 */
class LBI_Forms {
    public static function init() {
        add_shortcode( 'lbi_interview_form', array( __CLASS__, 'interview_form_shortcode' ) );
        add_shortcode( 'lbi_directory_form', array( __CLASS__, 'directory_form_shortcode' ) );
        add_action( 'init', array( __CLASS__, 'handle_interview_submission' ) );
        add_action( 'init', array( __CLASS__, 'handle_directory_submission' ) );
    }

    /**
     * Interview form shortcode
     */
    public static function interview_form_shortcode( $atts ) {
        if ( isset( $_GET['lbi_interview_submitted'] ) ) {
            return '<div class="lbi-success-message">' . 
                   __( 'Thank you for your submission! We will review it shortly and contact you with any questions.', 'local-business-interviews' ) . 
                   '</div>';
        }

        ob_start();
        ?>
        <form id="lbi-interview-form" method="post" enctype="multipart/form-data" class="lbi-form lbi-interview-form">
            <?php wp_nonce_field( 'lbi_interview_submit', '_lbi_nonce_interview' ); ?>
            
            <div class="lbi-form-message" style="display: none; margin: 20px 0; padding: 15px; border-radius: 4px;" 
                 role="alert" aria-live="polite"></div>

            <!-- Honeypot Field -->
            <?php LBI_Security::honeypot_field(); ?>

            <!-- reCAPTCHA Field -->
            <?php LBI_Security::recaptcha_field(); ?>

            <!-- Interviewee Information -->
            <fieldset>
                <legend><?php esc_html_e( 'Interviewee Information', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="interviewee_name">
                        <?php esc_html_e( 'Full Name', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="text" id="interviewee_name" name="interviewee_name" required 
                           placeholder="<?php esc_attr_e( 'John Doe', 'local-business-interviews' ); ?>">
                    <small><?php esc_html_e( 'Required', 'local-business-interviews' ); ?></small>
                </div>

                <div class="lbi-form-group">
                    <label for="interviewee_title">
                        <?php esc_html_e( 'Job Title/Position', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="text" id="interviewee_title" name="interviewee_title" required 
                           placeholder="<?php esc_attr_e( 'CEO, Owner, Manager', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="company_name">
                        <?php esc_html_e( 'Company Name', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="text" id="company_name" name="company_name" required 
                           placeholder="<?php esc_attr_e( 'Your Company', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="company_website">
                        <?php esc_html_e( 'Company Website', 'local-business-interviews' ); ?>
                    </label>
                    <input type="url" id="company_website" name="company_website" 
                           placeholder="<?php esc_attr_e( 'https://example.com', 'local-business-interviews' ); ?>">
                </div>
            </fieldset>

            <!-- Contact Information -->
            <fieldset>
                <legend><?php esc_html_e( 'Contact Information', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="email">
                        <?php esc_html_e( 'Email Address', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="email" id="email" name="email" required 
                           placeholder="<?php esc_attr_e( 'your@email.com', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="phone">
                        <?php esc_html_e( 'Phone Number', 'local-business-interviews' ); ?>
                    </label>
                    <input type="tel" id="phone" name="phone" 
                           placeholder="<?php esc_attr_e( '(123) 456-7890', 'local-business-interviews' ); ?>">
                </div>
            </fieldset>

            <!-- Business Category & Location -->
            <fieldset>
                <legend><?php esc_html_e( 'Business Information', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="business_category">
                        <?php esc_html_e( 'Business Category', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <select id="business_category" name="business_category" required>
                        <option value=""><?php esc_html_e( '-- Select a Category --', 'local-business-interviews' ); ?></option>
                        <?php
                        $terms = get_terms( array( 'taxonomy' => 'business_category', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $terms ) ):
                            foreach ( $terms as $term ):
                        ?>
                            <option value="<?php echo esc_attr( $term->term_id ); ?>">
                                <?php echo esc_html( $term->name ); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="lbi-form-group">
                    <label for="service_cities">
                        <?php esc_html_e( 'Service Cities', 'local-business-interviews' ); ?>
                    </label>
                    <p class="lbi-help-text">
                        <?php esc_html_e( 'Select the cities/areas you serve:', 'local-business-interviews' ); ?>
                    </p>
                    <div class="lbi-checkboxes">
                        <?php
                        $cities = get_terms( array( 'taxonomy' => 'service_city', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $cities ) ):
                            foreach ( $cities as $city ):
                        ?>
                            <label>
                                <input type="checkbox" name="service_cities[]" value="<?php echo esc_attr( $city->term_id ); ?>">
                                <?php echo esc_html( $city->name ); ?>
                            </label>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </fieldset>

            <!-- Interview Content -->
            <fieldset>
                <legend><?php esc_html_e( 'Interview Content', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="interview_transcript">
                        <?php esc_html_e( 'Interview Transcript or Content', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <p class="lbi-help-text">
                        <?php esc_html_e( 'You can paste your interview Q&A, write a summary, or provide the full interview content.', 'local-business-interviews' ); ?>
                    </p>
                    <?php
                    wp_editor( '', 'interview_transcript', array(
                        'textarea_name' => 'interview_transcript',
                        'media_buttons' => false,
                        'teeny'         => true,
                        'textarea_rows' => 10,
                    ) );
                    ?>
                </div>

                <div class="lbi-form-group">
                    <label for="video_url">
                        <?php esc_html_e( 'Video URL (Optional)', 'local-business-interviews' ); ?>
                    </label>
                    <input type="url" id="video_url" name="video_url" 
                           placeholder="<?php esc_attr_e( 'https://youtube.com/watch?v=...', 'local-business-interviews' ); ?>">
                    <small><?php esc_html_e( 'YouTube, Vimeo, or other oEmbed-supported video URLs', 'local-business-interviews' ); ?></small>
                </div>

                <div class="lbi-form-group">
                    <label for="featured_image">
                        <?php esc_html_e( 'Featured Image', 'local-business-interviews' ); ?>
                    </label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                    <small><?php esc_html_e( 'Max 5MB. Formats: JPG, PNG, GIF, WebP', 'local-business-interviews' ); ?></small>
                </div>
            </fieldset>

            <!-- Agreements -->
            <fieldset>
                <legend><?php esc_html_e( 'Agreements & Permissions', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_accuracy" value="1" required>
                        <?php esc_html_e( 'I confirm that all information provided is accurate and truthful.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>

                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_permission" value="1" required>
                        <?php esc_html_e( 'I grant permission to publish this content, images, and my information on the website.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>

                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_contact" value="1" required>
                        <?php esc_html_e( 'I agree to be contacted for any clarifications or corrections needed.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>
            </fieldset>

            <div class="lbi-form-submit">
                <button type="submit" class="lbi-btn lbi-btn-primary" name="lbi_submit_interview" value="1">
                    <?php esc_html_e( 'Submit Interview', 'local-business-interviews' ); ?>
                </button>
            </div>
        </form>

        <script>
        document.getElementById('lbi-interview-form').addEventListener('submit', function(e) {
            if (window['LBIRecaptcha'] && window['LBIRecaptcha']['updateToken']) {
                e.preventDefault();
                window['LBIRecaptcha']['updateToken']('interview_submit').then(function() {
                    document.getElementById('lbi-interview-form').submit();
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Directory form shortcode
     */
    public static function directory_form_shortcode( $atts ) {
        if ( isset( $_GET['lbi_directory_submitted'] ) ) {
            return '<div class="lbi-success-message">' . 
                   __( 'Thank you for your submission! We will review it shortly.', 'local-business-interviews' ) . 
                   '</div>';
        }

        ob_start();
        ?>
        <form id="lbi-directory-form" method="post" enctype="multipart/form-data" class="lbi-form lbi-directory-form">
            <?php wp_nonce_field( 'lbi_directory_submit', '_lbi_nonce_directory' ); ?>
            
            <div class="lbi-form-message" style="display: none; margin: 20px 0; padding: 15px; border-radius: 4px;" 
                 role="alert" aria-live="polite"></div>

            <!-- Honeypot Field -->
            <?php LBI_Security::honeypot_field(); ?>

            <!-- reCAPTCHA Field -->
            <?php LBI_Security::recaptcha_field(); ?>

            <!-- Business Information -->
            <fieldset>
                <legend><?php esc_html_e( 'Business Information', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="dir_business_name">
                        <?php esc_html_e( 'Business Name', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="text" id="dir_business_name" name="business_name" required 
                           placeholder="<?php esc_attr_e( 'Your Business Name', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="dir_business_category">
                        <?php esc_html_e( 'Business Category', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <select id="dir_business_category" name="business_category" required>
                        <option value=""><?php esc_html_e( '-- Select a Category --', 'local-business-interviews' ); ?></option>
                        <?php
                        $terms = get_terms( array( 'taxonomy' => 'business_category', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $terms ) ):
                            foreach ( $terms as $term ):
                        ?>
                            <option value="<?php echo esc_attr( $term->term_id ); ?>">
                                <?php echo esc_html( $term->name ); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="lbi-form-group">
                    <label for="dir_service_city">
                        <?php esc_html_e( 'Primary Service City', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <select id="dir_service_city" name="service_city" required>
                        <option value=""><?php esc_html_e( '-- Select a City --', 'local-business-interviews' ); ?></option>
                        <?php
                        $cities = get_terms( array( 'taxonomy' => 'service_city', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $cities ) ):
                            foreach ( $cities as $city ):
                        ?>
                            <option value="<?php echo esc_attr( $city->term_id ); ?>">
                                <?php echo esc_html( $city->name ); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="lbi-form-group">
                    <label for="dir_business_description">
                        <?php esc_html_e( 'Business Description', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <?php
                    wp_editor( '', 'business_description', array(
                        'textarea_name' => 'business_description',
                        'media_buttons' => false,
                        'teeny'         => true,
                        'textarea_rows' => 8,
                    ) );
                    ?>
                </div>
            </fieldset>

            <!-- Contact Information -->
            <fieldset>
                <legend><?php esc_html_e( 'Contact Information', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="dir_email">
                        <?php esc_html_e( 'Email Address', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="email" id="dir_email" name="email" required 
                           placeholder="<?php esc_attr_e( 'contact@business.com', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="dir_phone">
                        <?php esc_html_e( 'Phone Number', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="tel" id="dir_phone" name="phone" required 
                           placeholder="<?php esc_attr_e( '(123) 456-7890', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="dir_website">
                        <?php esc_html_e( 'Website URL', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="url" id="dir_website" name="website_url" required 
                           placeholder="<?php esc_attr_e( 'https://www.business.com', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="dir_address">
                        <?php esc_html_e( 'Address', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                    <input type="text" id="dir_address" name="address" required 
                           placeholder="<?php esc_attr_e( '123 Main St, City, State 12345', 'local-business-interviews' ); ?>">
                </div>

                <div class="lbi-form-group">
                    <label for="dir_hours">
                        <?php esc_html_e( 'Hours of Operation', 'local-business-interviews' ); ?>
                    </label>
                    <textarea id="dir_hours" name="hours_of_operation" rows="5" placeholder="<?php esc_attr_e( 'Monday - Friday: 9am - 5pm&#10;Saturday: 10am - 4pm&#10;Sunday: Closed', 'local-business-interviews' ); ?>"></textarea>
                </div>
            </fieldset>

            <!-- Media & Social -->
            <fieldset>
                <legend><?php esc_html_e( 'Media & Social Links', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label for="dir_featured_image">
                        <?php esc_html_e( 'Business Logo/Image', 'local-business-interviews' ); ?>
                    </label>
                    <input type="file" id="dir_featured_image" name="featured_image" accept="image/*">
                    <small><?php esc_html_e( 'Max 5MB. Formats: JPG, PNG, GIF, WebP', 'local-business-interviews' ); ?></small>
                </div>

                <div class="lbi-repeater-group" id="social_links_repeater">
                    <label><?php esc_html_e( 'Social Media Links', 'local-business-interviews' ); ?></label>
                    <p class="lbi-help-text"><?php esc_html_e( 'Add your social media profiles:', 'local-business-interviews' ); ?></p>
                    <div class="lbi-repeater-items">
                        <div class="lbi-repeater-item">
                            <select name="social_platforms[]">
                                <option value="">-- Select --</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Twitter">Twitter</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="YouTube">YouTube</option>
                                <option value="TikTok">TikTok</option>
                            </select>
                            <input type="url" name="social_urls[]" placeholder="https://...">
                            <button type="button" class="lbi-remove-item">×</button>
                        </div>
                    </div>
                    <button type="button" class="lbi-add-item"><?php esc_html_e( '+ Add Social Link', 'local-business-interviews' ); ?></button>
                </div>
            </fieldset>

            <!-- Agreements -->
            <fieldset>
                <legend><?php esc_html_e( 'Agreements & Permissions', 'local-business-interviews' ); ?></legend>
                
                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_accuracy" value="1" required>
                        <?php esc_html_e( 'I confirm that all information provided is accurate.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>

                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_permission" value="1" required>
                        <?php esc_html_e( 'I grant permission to publish this business information.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>

                <div class="lbi-form-group">
                    <label>
                        <input type="checkbox" name="confirm_contact" value="1" required>
                        <?php esc_html_e( 'I agree to be contacted for any updates or corrections.', 'local-business-interviews' ); ?>
                        <span class="lbi-required">*</span>
                    </label>
                </div>
            </fieldset>

            <div class="lbi-form-submit">
                <button type="submit" class="lbi-btn lbi-btn-primary" name="lbi_submit_directory" value="1">
                    <?php esc_html_e( 'Submit Business Listing', 'local-business-interviews' ); ?>
                </button>
            </div>
        </form>

        <script>
        document.getElementById('lbi-directory-form').addEventListener('submit', function(e) {
            if (window['LBIRecaptcha'] && window['LBIRecaptcha']['updateToken']) {
                e.preventDefault();
                window['LBIRecaptcha']['updateToken']('directory_submit').then(function() {
                    document.getElementById('lbi-directory-form').submit();
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle interview submission
     */
    public static function handle_interview_submission() {
        if ( empty( $_POST['lbi_submit_interview'] ) ) {
            return;
        }

        // Verify nonce
        if ( ! LBI_Security::verify_form_nonce( '_lbi_nonce_interview', 'lbi_interview_submit' ) ) {
            return;
        }

        // Prepare data
        $data = array();
        foreach ( $_POST as $key => $value ) {
            if ( strpos( $key, '_lbi_' ) === 0 ) {
                continue;
            }
            if ( is_array( $value ) ) {
                $data[ $key ] = array_map( 'sanitize_text_field', $value );
            } else {
                $data[ $key ] = sanitize_text_field( $value );
            }
        }

        // Validate submission
        $validation = apply_filters( 'lbi_validate_submission', true, $data );
        if ( is_wp_error( $validation ) ) {
            wp_die( esc_html( $validation->get_error_message() ) );
        }

        // Sanitize data according to field types
        $data = LBI_Security::sanitize_submission_data( $data );

        // Create post
        $post_id = wp_insert_post( array(
            'post_type'    => 'interview',
            'post_status'  => 'pending',
            'post_title'   => $data['company_name'] ?? 'Unnamed Interview',
            'post_date'    => current_time( 'mysql' ),
        ) );

        if ( ! $post_id ) {
            wp_die( __( 'Failed to create submission.', 'local-business-interviews' ) );
        }

        // Store metadata
        update_post_meta( $post_id, 'interviewee_name', $data['interviewee_name'] ?? '' );
        update_post_meta( $post_id, 'interviewee_title', $data['interviewee_title'] ?? '' );
        update_post_meta( $post_id, 'company_name', $data['company_name'] ?? '' );
        update_post_meta( $post_id, 'company_website', $data['company_website'] ?? '' );
        update_post_meta( $post_id, 'email', $data['email'] ?? '' );
        update_post_meta( $post_id, 'phone', $data['phone'] ?? '' );
        update_post_meta( $post_id, 'interview_transcript', $data['interview_transcript'] ?? '' );
        update_post_meta( $post_id, 'video_url', $data['video_url'] ?? '' );
        update_post_meta( $post_id, 'submission_date', current_time( 'mysql' ) );
        update_post_meta( $post_id, 'approval_status', 'pending' );

        //Set post content
        if ( ! empty( $data['interview_transcript'] ) ) {
            wp_update_post( array(
                'ID'           => $post_id,
                'post_content' => wp_kses_post( $data['interview_transcript'] ),
            ) );
        }

        // Handle featured image
        if ( ! empty( $_FILES['featured_image']['name'] ) ) {
            $image_id = self::handle_file_upload( $_FILES['featured_image'] );
            if ( $image_id ) {
                set_post_thumbnail( $post_id, $image_id );
            }
        }

        // Set taxonomies
        if ( ! empty( $data['business_category'] ) ) {
            wp_set_post_terms( $post_id, intval( $data['business_category'] ), 'business_category' );
        }
        if ( ! empty( $data['service_cities'] ) && is_array( $data['service_cities'] ) ) {
            $city_ids = array_map( 'intval', $data['service_cities'] );
            wp_set_post_terms( $post_id, $city_ids, 'service_city' );
        }

        // Log submission details
        LBI_Security::log_submission( $post_id );

        // Send notifications
        do_action( 'lbi_submission_received', $post_id );
        do_action( 'lbi_new_submission', $post_id );

        wp_redirect( add_query_arg( 'lbi_interview_submitted', '1', get_permalink() ) );
        exit;
    }

    /**
     * Handle directory submission
     */
    public static function handle_directory_submission() {
        if ( empty( $_POST['lbi_submit_directory'] ) ) {
            return;
        }

        // Verify nonce
        if ( ! LBI_Security::verify_form_nonce( '_lbi_nonce_directory', 'lbi_directory_submit' ) ) {
            return;
        }

        // Prepare data
        $data = array();
        foreach ( $_POST as $key => $value ) {
            if ( strpos( $key, '_lbi_' ) === 0 ) {
                continue;
            }
            if ( is_array( $value ) ) {
                $data[ $key ] = array_map( 'sanitize_text_field', $value );
            } else {
                $data[ $key ] = sanitize_text_field( $value );
            }
        }

        // Validate submission
        $validation = apply_filters( 'lbi_validate_submission', true, $data );
        if ( is_wp_error( $validation ) ) {
            wp_die( esc_html( $validation->get_error_message() ) );
        }

        // Sanitize data according to field types
        $data = LBI_Security::sanitize_submission_data( $data );

        // Create post
        $post_id = wp_insert_post( array(
            'post_type'    => 'directory',
            'post_status'  => 'pending',
            'post_title'   => $data['business_name'] ?? 'Unnamed Business',
            'post_date'    => current_time( 'mysql' ),
        ) );

        if ( ! $post_id ) {
            wp_die( __( 'Failed to create submission.', 'local-business-interviews' ) );
        }

        // Store metadata
        update_post_meta( $post_id, 'business_name', $data['business_name'] ?? '' );
        update_post_meta( $post_id, 'business_description', $data['business_description'] ?? '' );
        update_post_meta( $post_id, 'website_url', $data['website_url'] ?? '' );
        update_post_meta( $post_id, 'email', $data['email'] ?? '' );
        update_post_meta( $post_id, 'phone', $data['phone'] ?? '' );
        update_post_meta( $post_id, 'address', $data['address'] ?? '' );
        update_post_meta( $post_id, 'hours_of_operation', $data['hours_of_operation'] ?? '' );
        update_post_meta( $post_id, 'submission_date', current_time( 'mysql' ) );
        update_post_meta( $post_id, 'approval_status', 'pending' );
        update_post_meta( $post_id, 'featured', false );

        // Handle social media links
        if ( ! empty( $data['social_platforms'] ) && ! empty( $data['social_urls'] ) ) {
            $social_links = array();
            foreach ( $data['social_platforms'] as $idx => $platform ) {
                if ( ! empty( $platform ) && ! empty( $data['social_urls'][ $idx ] ) ) {
                    $social_links[] = array(
                        'platform' => sanitize_text_field( $platform ),
                        'url'      => esc_url_raw( $data['social_urls'][ $idx ] ),
                    );
                }
            }
            if ( ! empty( $social_links ) ) {
                update_post_meta( $post_id, 'social_media_links', wp_json_encode( $social_links ) );
            }
        }

        // Set post content
        if ( ! empty( $data['business_description'] ) ) {
            wp_update_post( array(
                'ID'           => $post_id,
                'post_content' => wp_kses_post( $data['business_description'] ),
            ) );
        }

        // Handle featured image
        if ( ! empty( $_FILES['featured_image']['name'] ) ) {
            $image_id = self::handle_file_upload( $_FILES['featured_image'] );
            if ( $image_id ) {
                set_post_thumbnail( $post_id, $image_id );
            }
        }

        // Set taxonomies
        if ( ! empty( $data['business_category'] ) ) {
            wp_set_post_terms( $post_id, intval( $data['business_category'] ), 'business_category' );
        }
        if ( ! empty( $data['service_city'] ) ) {
            wp_set_post_terms( $post_id, intval( $data['service_city'] ), 'service_city' );
        }

        // Log submission details
        LBI_Security::log_submission( $post_id );

        // Send notifications
        do_action( 'lbi_submission_received', $post_id );
        do_action( 'lbi_new_submission', $post_id );

        wp_redirect( add_query_arg( 'lbi_directory_submitted', '1', get_permalink() ) );
        exit;
    }

    /**
     * Handle file uploads
     *
     * @param array $file File from $_FILES
     * @return int Attachment ID or 0 on failure
     */
    protected static function handle_file_upload( $file ) {
        // Validate file
        $validated = LBI_Helpers::validate_file_upload( $file );
        if ( is_wp_error( $validated ) ) {
            return 0;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $result = wp_handle_upload( $file, array( 'test_form' => false ) );
        
        if ( isset( $result['error'] ) ) {
            return 0;
        }

        // Create attachment post
        $attachment = array(
            'post_mime_type' => $result['type'],
            'post_title'     => sanitize_file_name( basename( $result['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        $attach_id = wp_insert_attachment( $attachment, $result['file'] );
        
        // Generate metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $result['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }
}
