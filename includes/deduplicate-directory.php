<?php
/**
 * Directory Deduplication Tool
 * Removes duplicate business listings, keeping only one per business name
 * Access: /wp-content/plugins/local-business-interviews/includes/deduplicate-directory.php?key=dedupe
 */

// Security check
if ( empty( $_GET['key'] ) || $_GET['key'] !== 'dedupe' ) {
    wp_die( 'Unauthorized access' );
}

// Load WordPress
$wp_load = false;
$paths = [
    dirname( __FILE__ ) . '/../../wp-load.php',
    dirname( __FILE__ ) . '/../../../../wp-load.php',
    dirname( __FILE__ ) . '/../../../../../wp-load.php',
];

foreach ( $paths as $path ) {
    if ( file_exists( $path ) ) {
        require_once $path;
        $wp_load = true;
        break;
    }
}

if ( ! $wp_load ) {
    die( 'Could not load WordPress' );
}

// Only logged-in admin can proceed
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Admin login required' );
}

echo '<h1>Directory Deduplication Tool</h1>';

// Get all directory posts
$args = [
    'post_type'      => 'directory',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'ID',
    'order'          => 'ASC',
];

$directory_posts = get_posts( $args );

if ( empty( $directory_posts ) ) {
    echo '<p>No directory posts found.</p>';
    exit;
}

echo '<p>Total posts found: ' . count( $directory_posts ) . '</p>';

// Group by business name
$businesses = [];
$duplicates = [];

foreach ( $directory_posts as $post ) {
    $name = $post->post_title;
    
    if ( ! isset( $businesses[ $name ] ) ) {
        $businesses[ $name ] = [];
    }
    
    $businesses[ $name ][] = $post->ID;
}

// Find duplicates
foreach ( $businesses as $name => $ids ) {
    if ( count( $ids ) > 1 ) {
        $duplicates[ $name ] = $ids;
    }
}

if ( empty( $duplicates ) ) {
    echo '<p style="color: green; font-weight: bold;">✓ No duplicates found! All businesses are unique.</p>';
    exit;
}

echo '<p style="color: orange;">Duplicates found: ' . count( $duplicates ) . ' business names with multiple listings</p>';
echo '<ul>';

$total_deleted = 0;

foreach ( $duplicates as $name => $ids ) {
    // Keep first, delete rest
    $keep_id = array_shift( $ids );
    $delete_count = count( $ids );
    $total_deleted += $delete_count;
    
    foreach ( $ids as $delete_id ) {
        wp_delete_post( $delete_id, true );
    }
    
    echo '<li>';
    echo '<strong>' . esc_html( $name ) . '</strong> ';
    echo '(kept ID: ' . $keep_id . ', deleted ' . $delete_count . ' duplicate' . ( $delete_count > 1 ? 's' : '' ) . ')';
    echo '</li>';
}

echo '</ul>';

echo '<p style="color: green; font-weight: bold;">';
echo '✓ DEDUPLICATION COMPLETE<br>';
echo 'Total duplicates deleted: ' . $total_deleted . '<br>';
echo 'Unique businesses remaining: ' . count( $businesses );
echo '</p>';
