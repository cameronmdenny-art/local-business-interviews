<?php
/**
 * Template Name: Recommend a Business
 * Description: Clean, premium form for recommending local businesses
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If this template is being used, output a marker comment
echo '<!-- page-recommend.php template is being used -->';

get_header();

// Load the forms class and render the recommendation form directly
if ( class_exists( 'LBI_Forms' ) ) {
    echo LBI_Forms::recommend_form_shortcode( array() );
} else {
    // Fallback to shortcode if class method isn't available
    echo do_shortcode( '[lbi_recommend_form]' );
}

get_footer();

