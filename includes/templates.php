<?php
/**
 * Page Templates for Local Business Interviews
 * 
 * @package LocalBusinessInterviews
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Load custom front page template for homepage
 */
function lbi_load_front_page_template($template) {
	if (is_front_page() && !is_admin()) {
		$custom_template = dirname(dirname(__FILE__)) . '/front-page.php';
		if (file_exists($custom_template)) {
			return $custom_template;
		}
	}
	return $template;
}

// Use template_include to catch before all other filters
add_filter('template_include', 'lbi_load_front_page_template', 1);

/**
 * Add settings to customize hero section
 */
function lbi_register_hero_settings() {
	register_setting('general', 'lbi_hero_title', [
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest'      => true,
	]);

	register_setting('general', 'lbi_hero_subtitle', [
		'sanitize_callback' => 'sanitize_textarea_field',
		'show_in_rest'      => true,
	]);

	add_settings_field(
		'lbi_hero_title',
		'LBI Hero Title',
		function() {
			$value = get_option('lbi_hero_title', 'Discover Local Business Stories');
			echo '<input type="text" name="lbi_hero_title" value="' . esc_attr($value) . '" style="width: 100%; max-width: 500px;" />';
		},
		'general'
	);

	add_settings_field(
		'lbi_hero_subtitle',
		'LBI Hero Subtitle',
		function() {
			$value = get_option('lbi_hero_subtitle', 'Meet the entrepreneurs and leaders shaping our community');
			echo '<textarea name="lbi_hero_subtitle" style="width: 100%; max-width: 500px; height: 100px;">' . esc_attr($value) . '</textarea>';
		},
		'general'
	);
}

add_action('admin_init', 'lbi_register_hero_settings');
