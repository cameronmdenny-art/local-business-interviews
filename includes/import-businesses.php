<?php
/**
 * Import Businesses from JSON Data
 * Access via: /wp-content/plugins/local-business-interviews/includes/import-businesses.php?key=import
 */

// Load WordPress
$wp_root = false;
$dir = dirname( __FILE__ );

for ( $i = 0; $i < 15; $i++ ) {
    $possible_path = dirname( $dir, $i + 3 );
    if ( file_exists( $possible_path . '/wp-config.php' ) ) {
        $wp_root = $possible_path;
        break;
    }
}

if ( ! $wp_root ) {
    $paths_to_try = array(
        dirname( __FILE__, 4 ),
        dirname( __FILE__, 5 ),
        '/var/www/html',
        $_SERVER['DOCUMENT_ROOT'] ?? '',
    );
    foreach ( $paths_to_try as $p ) {
        if ( ! empty( $p ) && file_exists( $p . '/wp-config.php' ) ) {
            $wp_root = $p;
            break;
        }
    }
}

if ( ! $wp_root || ! file_exists( $wp_root . '/wp-config.php' ) ) {
    die( 'ERROR: Could not find WordPress installation.' );
}

define( 'WP_USE_THEMES', false );
require( $wp_root . '/wp-load.php' );

// Security checks
$key = isset( $_GET['key'] ) ? sanitize_text_field( $_GET['key'] ) : '';
if ( $key !== 'import' ) {
    wp_die( 'Access denied' );
}

if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Admin access required' );
}

// Business data to import
$businesses = array(
    array(
        'name' => 'A Joy of Granite & Natural Stone LLC',
        'category' => 'Kitchen Remodeling Contractor',
        'address' => '5907 Orchid Ln, Dallas, TX 75230',
        'phone' => '',
        'website' => 'https://joyofgranite.com',
        'email' => 'ajoyofgranite@gmail.com',
    ),
    array(
        'name' => 'ASAGA HVAC',
        'category' => 'HVAC Contractor',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://aaasg.net/',
        'email' => 'asaga@aaasg.net',
    ),
    array(
        'name' => 'Cairde Designs',
        'category' => 'Web Design Agency',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://cairdedesigns.com',
        'email' => 'cameron@cairdedesigns.com',
    ),
    array(
        'name' => 'Dark Horse Custom Mowing',
        'category' => 'Lawn & Landscape Services',
        'address' => 'Frisco, TX',
        'phone' => '',
        'website' => 'https://darkhorsemowing.com/',
        'email' => 'Darkhorsemowing@gmail.com',
    ),
    array(
        'name' => 'Denny Co Junk Removal',
        'category' => 'Junk Removal Service',
        'address' => 'Lakeside, CA',
        'phone' => '',
        'website' => 'https://dennyco.com',
        'email' => 'dennycompanysd@gmail.com',
    ),
    array(
        'name' => 'DoorCraftSVC',
        'category' => 'Garage Door Service',
        'address' => 'San Diego, CA',
        'phone' => '',
        'website' => 'https://doorcraftsvc.com/',
        'email' => 'john@doorcraftsvc.com',
    ),
    array(
        'name' => 'Durable Paving',
        'category' => 'Asphalt Paving Contractor',
        'address' => 'Zimmerman, MN',
        'phone' => '',
        'website' => 'https://durablepavingmn.com',
        'email' => 'durablepaving@outlook.com',
    ),
    array(
        'name' => 'Erik\'s Tree & Lawn Care Services',
        'category' => 'Tree & Lawn Care Services',
        'address' => 'Frisco, TX',
        'phone' => '',
        'website' => 'https://erikstreeandlawn.com',
        'email' => 'erikstreeandlawn03@gmail.com',
    ),
    array(
        'name' => 'Fire Rehab and Restoration',
        'category' => 'Fire Restoration',
        'address' => 'Addison, TX',
        'phone' => '',
        'website' => 'https://firerehabrestoration.com/',
        'email' => 'support@firerehabrestoration.com',
    ),
    array(
        'name' => 'FivePoint Home Inspections',
        'category' => 'Home Inspection Service',
        'address' => 'Texas Panhandle',
        'phone' => '',
        'website' => 'https://fivepointinspections.com',
        'email' => 'info@fivepointinspections.com',
    ),
    array(
        'name' => 'Foundation Pro Inspections',
        'category' => 'Foundation Repair',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://foundationproinspections.com',
        'email' => 'foundationproinspections@gmail.com',
    ),
    array(
        'name' => 'Gnarly Goats',
        'category' => 'Landscaping Service',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://gnarly-goats.com',
        'email' => 'info@gnarly-goats.com',
    ),
    array(
        'name' => 'Green Light Pros',
        'category' => 'Electrical Contractor',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://greenlightpros.com',
        'email' => 'contact@greenlightpros.com',
    ),
    array(
        'name' => 'Healthy Water Systems',
        'category' => 'Water Treatment Service',
        'address' => 'Frisco, TX',
        'phone' => '',
        'website' => 'https://healthywaterfilter.com',
        'email' => 'support@healthywaterfilter.com',
    ),
    array(
        'name' => 'Home Solutions by Duplantis',
        'category' => 'General Contractor',
        'address' => 'Baton Rouge, LA',
        'phone' => '',
        'website' => 'https://homesolutionsbyduplantis.com',
        'email' => 'info@homesolutionsbyduplantis.com',
    ),
    array(
        'name' => 'Hospitality Wellness Solutions',
        'category' => 'Health & Wellness Service',
        'address' => 'Dallas-Fort Worth',
        'phone' => '',
        'website' => 'https://hospitalitywellness.com',
        'email' => 'contact@hospitalitywellness.com',
    ),
    array(
        'name' => 'Huston Foundation Repair',
        'category' => 'Foundation Repair',
        'address' => 'Houston, TX',
        'phone' => '',
        'website' => 'https://hustonfoundationrepair.com',
        'email' => 'info@hustonfoundationrepair.com',
    ),
    array(
        'name' => 'John\'s Plumbing Service',
        'category' => 'Plumbing Contractor',
        'address' => 'San Antonio, TX',
        'phone' => '',
        'website' => 'https://johnsplumbingservice.com',
        'email' => 'john@johnsplumbingservice.com',
    ),
    array(
        'name' => 'Kenley Electric',
        'category' => 'Electrical Contractor',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://kenleyelectric.com',
        'email' => 'contact@kenleyelectric.com',
    ),
    array(
        'name' => 'Kingdom Plumbing',
        'category' => 'Plumbing Contractor',
        'address' => 'Texas',
        'phone' => '',
        'website' => 'https://kingdomplumbing.com',
        'email' => 'info@kingdomplumbing.com',
    ),
    array(
        'name' => 'Kingswood Construction',
        'category' => 'General Contractor',
        'address' => 'Texas',
        'phone' => '',
        'website' => 'https://kingswoodconstruction.com',
        'email' => 'contact@kingswoodconstruction.com',
    ),
    array(
        'name' => 'Kunkel Restoration',
        'category' => 'Water Damage Restoration',
        'address' => 'Texas',
        'phone' => '',
        'website' => 'https://kunkelrestoration.com',
        'email' => 'info@kunkelrestoration.com',
    ),
    array(
        'name' => 'Landmark Wood Flooring',
        'category' => 'Flooring Contractor',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://landmarkwoodflooring.com',
        'email' => 'contact@landmarkwoodflooring.com',
    ),
    array(
        'name' => 'Launch Digital Agency',
        'category' => 'Digital Marketing Agency',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://launchdigitalagency.com',
        'email' => 'contact@launchdigitalagency.com',
    ),
    array(
        'name' => 'Leaf Smart',
        'category' => 'Landscaping Service',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://leafsmart.com',
        'email' => 'info@leafsmart.com',
    ),
    array(
        'name' => 'Local Logic',
        'category' => 'SEO & Digital Marketing',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://locallogic.com',
        'email' => 'contact@locallogic.com',
    ),
    array(
        'name' => 'Loyal Roofing',
        'category' => 'Roofing Contractor',
        'address' => 'Dallas, TX',
        'phone' => '',
        'website' => 'https://loyalroofing.com',
        'email' => 'info@loyalroofing.com',
    ),
    array(
        'name' => 'Magnolia Remodeling',
        'category' => 'Kitchen Remodeling Contractor',
        'address' => 'Houston, TX',
        'phone' => '',
        'website' => 'https://magnoliaremolding.com',
        'email' => 'contact@magnoliaremolding.com',
    ),
    array(
        'name' => 'Morning Star Roofing',
        'category' => 'Roofing Contractor',
        'address' => 'San Antonio, TX',
        'phone' => '',
        'website' => 'https://morningstarroofing.com',
        'email' => 'info@morningstarroofing.com',
    ),
    array(
        'name' => 'NextLevel Electric',
        'category' => 'Electrical Contractor',
        'address' => 'Austin, TX',
        'phone' => '',
        'website' => 'https://nextlevelelectric.com',
        'email' => 'contact@nextlevelelectric.com',
    ),
    array(
        'name' => 'Proactive Pest Solutions',
        'category' => 'Pest Control Service',
        'address' => 'Texas',
        'phone' => '',
        'website' => 'https://proactivepest.com',
        'email' => 'info@proactivetest.com',
    ),
);

header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Import</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f1f1f1;
            padding: 20px;
        }
        .container { 
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
        }
        h2 { color: #333; margin-bottom: 20px; }
        p { color: #666; line-height: 1.6; margin-bottom: 15px; }
        ul { list-style: none; margin: 20px 0; padding: 0; }
        li { 
            padding: 12px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        li:last-child { border-bottom: none; }
        .success { color: #28a745; margin-right: 10px; }
        .error { color: #dc3545; margin-right: 10px; }
        .title { color: #333; font-weight: 500; flex: 1; }
        .category { color: #999; font-size: 12px; }
        .footer { 
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .success-summary { 
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
            padding: 15px;
            background: #d4edda;
            border-radius: 4px;
            margin: 20px 0;
        }
        a { color: #0073aa; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📇 Business Import Tool</h2>
        <p>Importing <?php echo count( $businesses ); ?> businesses...</p>
        
        <ul>
        <?php
        $imported = 0;
        $failed = 0;
        
        foreach ( $businesses as $biz ) {
            // Create or find category
            $category_id = false;
            $term = get_term_by( 'name', $biz['category'], 'business_category' );
            if ( $term ) {
                $category_id = $term->term_id;
            } else {
                $result = wp_insert_term( $biz['category'], 'business_category' );
                if ( ! is_wp_error( $result ) ) {
                    $category_id = $result['term_id'];
                }
            }
            
            // Create post
            $post_id = wp_insert_post( array(
                'post_type'    => 'directory',
                'post_title'   => $biz['name'],
                'post_status'  => 'publish',
                'post_content' => 'Directory listing for ' . $biz['name'],
            ) );
            
            if ( ! is_wp_error( $post_id ) && $post_id ) {
                // Add category
                if ( $category_id ) {
                    wp_set_post_terms( $post_id, array( $category_id ), 'business_category' );
                }
                
                // Add custom fields
                update_post_meta( $post_id, '_directory_address', $biz['address'] );
                update_post_meta( $post_id, '_directory_phone', $biz['phone'] );
                update_post_meta( $post_id, '_directory_website', $biz['website'] );
                update_post_meta( $post_id, '_directory_email', $biz['email'] );
                
                echo '<li><span class="success">✓</span><span class="title">' . esc_html( $biz['name'] ) . '</span><span class="category">' . esc_html( $biz['category'] ) . '</span></li>';
                $imported++;
            } else {
                echo '<li><span class="error">✗</span><span class="title">' . esc_html( $biz['name'] ) . ' (failed)</span></li>';
                $failed++;
            }
        }
        ?>
        </ul>
        
        <div class="success-summary">
            ✅ Import Complete! Added <?php echo $imported; ?> business(es)<?php echo $failed > 0 ? ' (' . $failed . ' failed)' : ''; ?>
        </div>
        
        <div class="footer">
            <p><a href="/directory/">View Directory →</a> | <a href="/">Back to Home</a></p>
            <p style="color: #999; font-size: 12px; margin-top: 20px;">Imported at: <?php echo date( 'Y-m-d H:i:s' ); ?> by <?php echo esc_html( wp_get_current_user()->user_login ); ?></p>
        </div>
    </div>
</body>
</html>