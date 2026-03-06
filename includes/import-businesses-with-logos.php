<?php
/**
 * Import Businesses from JSON Data with Logos
 * Access via: /wp-content/plugins/local-business-interviews/includes/import-businesses-with-logos.php?key=import
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

// Business data to import - from client spreadsheet with logos
$businesses = array(
    array('name' => 'A Joy of Granite & Natural Stone LLC', 'category' => 'Kitchen Remodeling Contractor', 'address' => '5907 Orchid Ln, Dallas, TX 75230', 'phone' => '', 'website' => 'https://joyofgranite.com', 'email' => 'ajoyofgranite@gmail.com', 'logo' => ''),
    array('name' => 'ASAGA HVAC', 'category' => 'HVAC Contractor', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://aaasg.net/', 'email' => 'asaga@aaasg.net', 'logo' => 'https://aaasg.net/wp-content/uploads/2020/04/logo1.png'),
    array('name' => 'Cairde Designs', 'category' => 'Web Design Agency', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://cairdedesigns.com', 'email' => 'cameron@cairdedesigns.com', 'logo' => 'https://cairdedesigns.com/wp-content/uploads/2024/11/cropped-cropped-cairdelogo8-300x300-1.webp'),
    array('name' => 'Dark Horse Custom Mowing', 'category' => 'Mowing service', 'address' => 'Frisco, TX', 'phone' => '', 'website' => 'https://darkhorsemowing.com/', 'email' => 'Darkhorsemowing@gmail.com', 'logo' => 'https://darkhorsemowing.com/wp-content/uploads/2024/10/Logo-Mkaer-2-e1729716859541-1-150x150.png'),
    array('name' => 'Denny Co Junk Removal', 'category' => 'Junk Removal Service', 'address' => 'Lakeside, CA', 'phone' => '', 'website' => 'https://dennyco.com', 'email' => 'dennycompanysd@gmail.com', 'logo' => 'https://dennyco.com/wp-content/uploads/2025/01/Untitleddesign.jpg'),
    array('name' => 'DoorCraftSVC', 'category' => 'Garage Door Service', 'address' => 'San Diego, CA', 'phone' => '', 'website' => 'https://doorcraftsvc.com/', 'email' => 'john@doorcraftsvc.com', 'logo' => 'https://doorcraftsvc.com/wp-content/uploads/2025/11/door-craft-garage1.jpeg'),
    array('name' => 'Durable Paving', 'category' => 'Asphalt Paving Contractor', 'address' => 'Zimmerman, MN', 'phone' => '', 'website' => 'https://durablepavingmn.com', 'email' => 'durablepaving@outlook.com', 'logo' => 'https://durablepavingmn.com/wp-content/uploads/2025/07/cropped-Untitled_design_1-1-32x32.webp'),
    array('name' => 'Erik\'s Tree & Lawn Care Services', 'category' => 'Tree & Lawn Care Services', 'address' => 'Frisco, TX', 'phone' => '', 'website' => 'https://erikstreeandlawn.com', 'email' => 'erikstreeandlawn03@gmail.com', 'logo' => 'https://erikstreeandlawn.com/wp-content/uploads/2024/10/Eriks-e1728408859554-150x150.png'),
    array('name' => 'Fire Rehad and Restoration', 'category' => 'Fire Restoration', 'address' => 'Addison, TX', 'phone' => '', 'website' => 'https://firerehabrestoration.com/', 'email' => 'support@firerehabrestoration.com', 'logo' => 'https://firerehabrestoration.com/wp-content/uploads/2024/12/cropped-Heading-14-32x32.png'),
    array('name' => 'Heritage Landscaping and Irrigation', 'category' => 'Landscape Design & Maintenance', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://heritagelandscapedallas.com', 'email' => '', 'logo' => 'https://heritagelandscapedallasllc.com/wp-content/uploads/2025/07/Logo-Heritage.png'),
    array('name' => 'Ideal Frame & Art', 'category' => 'Art Gallery / Retail', 'address' => 'Richardson, TX', 'phone' => '', 'website' => 'https://idealframeandartgallery.com/', 'email' => '', 'logo' => 'https://idealframeandartgallery.com/wp-content/uploads/2025/04/cropped-2-32x32.png'),
    array('name' => 'Jurmu Painting', 'category' => 'Painting & Decorating Contractor', 'address' => '7308 Aspen Ln N, Brooklyn Park, MN 55428', 'phone' => '', 'website' => 'https://jurmupainting.com', 'email' => '', 'logo' => 'https://jurmupainting.com/wp-content/uploads/2025/03/cropped-Jurmu-Painting-Inc.-Logo-4-1024x513.png'),
    array('name' => 'Keystone Conveyor', 'category' => 'Material Handling / Conveyor Systems', 'address' => 'Minnesota', 'phone' => '', 'website' => 'https://keystoneconveyor.com', 'email' => '', 'logo' => 'https://keystoneconveyor.com/wp-content/uploads/2025/07/bg-1920-x-1080-px-5.png'),
    array('name' => 'Kitchen Remodeling Frisco', 'category' => 'Kitchen Remodeling Contractor', 'address' => 'Frisco, TX', 'phone' => '', 'website' => 'https://kitchenremodelingfrisco.com', 'email' => '', 'logo' => 'https://kitchenremodelingfrisco.com/wp-content/uploads/2025/06/cropped-large-ChatGPT-Image-Jun-5_-2025_-06_35_24-PM-32x32.png'),
    array('name' => 'Lizards Ink Tattoo', 'category' => 'Tattoo Studio', 'address' => 'Lewisville, TX', 'phone' => '', 'website' => 'https://www.lizardsink.com', 'email' => '', 'logo' => 'https://lizardsink.com/wp-content/plugins/wp-google-places-review-slider/public/partials/imgs/google_small_icon.png'),
    array('name' => 'LOL Home Remodeling', 'category' => 'Home Remodeling & Renovation', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://lolhomeremodeling.com', 'email' => '', 'logo' => 'https://lolhomeremodeling.com/wp-content/uploads/2024/11/cropped-IMG_4586-1-scaled.png'),
    array('name' => 'Maria\'s Coffee Shop', 'category' => 'Coffee Shop / Food Service', 'address' => 'El Cajon, CA', 'phone' => '', 'website' => 'https://mariascoffeeshop.com', 'email' => '', 'logo' => 'https://mariascoffeeshop.com/wp-content/uploads/2025/07/cropped-cropped-ChatGPT-Image-Jul-19-2025-01_52_15-PM-32x32.png'),
    array('name' => 'Optic Element', 'category' => 'Eyewear Retail / Optical Services', 'address' => 'San Diego, CA', 'phone' => '', 'website' => 'https://opticelement.com', 'email' => '', 'logo' => ''),
    array('name' => 'Prince Optical & Wireless', 'category' => 'Optical / Eyewear Retail & Repair', 'address' => '562 E Main St, El Cajon, CA 92020', 'phone' => '', 'website' => 'https://princeeyeglasses.com/', 'email' => '', 'logo' => 'https://princeeyeglasses.com/wp-content/uploads/2025/05/cropped-680ae5149ab622202b8921c2-scaled-1-e1746652597152.png'),
    array('name' => 'ProServe SD', 'category' => 'Handyman & Property Maintenance Services', 'address' => 'San Diego, CA', 'phone' => '', 'website' => 'https://proservesd.com', 'email' => '', 'logo' => 'https://proservesd.com/wp-content/uploads/2025/10/cropped-pro-serve-CENTERED-logo-1.png'),
    array('name' => 'Pug Rescue San Diego County', 'category' => 'Animal Rescue Nonprofit', 'address' => 'San Diego, CA', 'phone' => '', 'website' => 'https://pugrescuesandiego.com', 'email' => '', 'logo' => 'https://pugrescuesandiego.com/wp-content/uploads/2025/04/cropped-Untitled-Facebook-Post-32x32.png'),
    array('name' => 'RAD Handyman Services', 'category' => 'Electrical Contractor', 'address' => 'El Cajon, CA', 'phone' => '', 'website' => 'https://radhandyworks.com/', 'email' => '', 'logo' => 'https://radhandyworks.com/wp-content/uploads/2025/06/cropped-Untitled-Facebook-Post-2-1-32x32.png'),
    array('name' => 'RG Custom Cabinets', 'category' => 'Custom Cabinetry & Woodworking', 'address' => 'El Cajon, CA', 'phone' => '', 'website' => 'https://rgcustomcabinets.net', 'email' => '', 'logo' => 'https://rgcustomcabinets.net/wp-content/uploads/2025/06/RG-New-logo.png'),
    array('name' => 'San Diego Pool Tech', 'category' => 'Pool Remodeling & Resurfacing', 'address' => '134 S Ivory Ave, El Cajon, CA 92019', 'phone' => '', 'website' => 'https://sandiegopooltech.com', 'email' => '', 'logo' => ''),
    array('name' => 'The Crystal Pour LLC', 'category' => 'Mobile Bar', 'address' => 'San Diego, CA', 'phone' => '', 'website' => '', 'email' => '', 'logo' => ''),
    array('name' => 'TL Remodeling', 'category' => 'Remodeling & Home Improvement', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://tlremodeling.us', 'email' => '', 'logo' => 'https://tlremodeling.us/wp-content/uploads/2024/12/cropped-cropped-img-1-2-32x32.png'),
    array('name' => 'Valley Wide Tree & Landscaping', 'category' => 'Tree Services & Landscaping', 'address' => 'Frisco, TX', 'phone' => '', 'website' => 'https://valleywidetree.com', 'email' => '', 'logo' => 'https://valleywidetree.com/wp-content/uploads/2020/02/cropped-valley-wide-Logo-1.png'),
    array('name' => 'Marios Carpet Binding', 'category' => 'Carpet Binding', 'address' => 'Dallas, TX', 'phone' => '', 'website' => 'https://texascarpetbinding.com/', 'email' => '', 'logo' => 'http://static1.squarespace.com/static/6679c3c18d675776788ede98/t/66f8b2e96ba3637b7341185e/1727574761886/nl%2Cnkl.png?format=1500w'),
    array('name' => 'Prowork Builders', 'category' => 'Remodeling & Home Improvement', 'address' => 'Anoka, MN', 'phone' => '', 'website' => 'https://proworkbuilders.com/', 'email' => '', 'logo' => 'https://proworkbuilders.com/wp-content/uploads/2026/01/3-1.png'),
    array('name' => 'Four Winds RV Park', 'category' => 'RV Park', 'address' => 'Taylor, TX', 'phone' => '', 'website' => '', 'email' => '', 'logo' => ''),
    array('name' => 'The Cupcake Store', 'category' => 'Bakery', 'address' => 'Santee, CA', 'phone' => '', 'website' => '', 'email' => '', 'logo' => ''),
    array('name' => 'Lakeside Landscaping', 'category' => 'Landscaping', 'address' => '', 'phone' => '', 'website' => '', 'email' => '', 'logo' => ''),
);

header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Import with Logos</title>
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
        <h2>📇 Business Import Tool (with Logos)</h2>
        <p>Importing <?php echo count( $businesses ); ?> businesses with logo URLs...</p>
        
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
                update_post_meta( $post_id, '_directory_logo', $biz['logo'] );
                
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