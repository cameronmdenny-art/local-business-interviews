<?php
// Force homepage to display /home/ page instead of blog
define('WP_USE_THEMES', false);
require('/home/u300002008/public_html/wp-load.php');

// Update WordPress options to show custom home page
update_option('show_on_front', 'page');
update_option('page_on_front', 21);

// Also try creating a homepage if page 21 doesn't exist
$home_page = get_post(21);
if (!$home_page && !is_wp_error($home_page)) {
    // Create home page if it doesn't exist
    wp_insert_post(array(
        'ID' => 21,
        'post_title' => 'Home',
        'post_name' => 'home',
        'post_content' => '<h1>Welcome to our site</h1>',
        'post_type' => 'page',
        'post_status' => 'publish',
    ));
}

// Flush rewrite rules
flush_rewrite_rules(false);

echo "Homepage configuration updated successfully!";
?>
