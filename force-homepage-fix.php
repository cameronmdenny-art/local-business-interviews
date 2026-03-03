<?php
// FORCE HOMEPAGE FIX - Clear cache and set proper homepage
define('WP_USE_THEMES', false);
require('/home/u300002008/public_html/wp-load.php');

echo "=== HOMEPAGE FIX STARTING ===\n\n";

// Step 1: Clear LiteSpeed Cache
if (class_exists('LiteSpeed_Cache_API')) {
    LiteSpeed_Cache_API::purge_all();
    echo "✓ LiteSpeed Cache cleared\n";
}

// Step 2: Get or create Home page
$home_page = get_page_by_path('home');
if (!$home_page) {
    $home_page_id = wp_insert_post(array(
        'post_title' => 'Home',
        'post_name' => 'home',
        'post_content' => '<!-- wp:paragraph --><p>Welcome to our site</p><!-- /wp:paragraph -->',
        'post_status' => 'publish',
        'post_type' => 'page'
    ));
    echo "✓ Home page created (ID: $home_page_id)\n";
} else {
    $home_page_id = $home_page->ID;
    echo "✓ Home page exists (ID: $home_page_id)\n";
}

// Step 3: Force homepage settings
update_option('show_on_front', 'page');
update_option('page_on_front', $home_page_id);
echo "✓ Homepage set to page ID $home_page_id\n";

// Step 4: Flush rewrite rules
flush_rewrite_rules(false);
echo "✓ Rewrite rules flushed\n";

// Step 5: Delete all cache-related transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_%'");
echo "✓ Transients cleared\n";

// Step 6: Verify settings
echo "\n=== VERIFICATION ===\n";
echo "show_on_front: " . get_option('show_on_front') . "\n";
echo "page_on_front: " . get_option('page_on_front') . "\n";
echo "Home URL: " . home_url() . "\n";
echo "\n✓ HOMEPAGE FIX COMPLETE!\n";
echo "\nCache cleared - please refresh your browser\n";
?>
