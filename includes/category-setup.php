<?php
/**
 * Setup default business categories
 * Run this once to populate categories
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Category_Setup {
    
    public static function create_default_categories() {
        $categories = array(
            // HVAC & Temperature Control
            'Air Conditioning & Heating',
            'Furnace Repair',
            'Air Conditioning Repair',
            'Heat Pump Installation',
            'Ductless HVAC',
            
            // Plumbing & Water
            'Plumbing',
            'Water Heater Repair',
            'Pipe Repair',
            'Water Softener',
            'Sewer & Drain Cleaning',
            'Well Service',
            
            // Electrical
            'Electrical Repair',
            'Electrician',
            'Generator Installation',
            'Home Wiring',
            'Panel Upgrade',
            
            // Roofing & Gutters
            'Roofing',
            'Gutter Installation',
            'Gutter Repair',
            'Roof Repair',
            'Roof Replacement',
            'Skylight Installation',
            
            // Cleaning Services
            'House Cleaning',
            'Carpet Cleaning',
            'Power Washing',
            'Pressure Washing',
            'Window Cleaning',
            'Chimney Cleaning',
            'Air Duct Cleaning',
            'Mold Removal',
            'Post-Construction Cleaning',
            
            // Pest Control
            'Pest Control',
            'Termite Treatment',
            'Bed Bug Treatment',
            'Wildlife Removal',
            'Mosquito Control',
            
            // Landscaping & Outdoor
            'Landscaping',
            'Tree Services',
            'Tree Trimming',
            'Tree Removal',
            'Lawn Care',
            'Lawn Maintenance',
            'Garden Design',
            'Sprinkler System',
            'Hardscape Installation',
            
            // Exterior & Siding
            'Siding Installation',
            'Siding Repair',
            'Door Installation',
            'Window Installation',
            'Deck Building',
            'Patio Installation',
            'Fencing',
            'Fence Repair',
            'Driveway Repair',
            'Asphalt Paving',
            'Concrete Services',
            
            // Flooring
            'Flooring Installation',
            'Hardwood Floor Refinishing',
            'Laminate Installation',
            'Tile Installation',
            'Vinyl Flooring',
            
            // Kitchen & Bath
            'Kitchen Remodel',
            'Bathroom Remodel',
            'Cabinet Installation',
            'Countertop Installation',
            'Backsplash Installation',
            
            // General Contracting & Remodeling
            'General Contractor',
            'Home Remodeling',
            'Remodel & Construction',
            'Home Addition',
            'Room Renovation',
            'Basement Finishing',
            'Attic Insulation',
            
            // Doors & Windows
            'Garage Door Installation',
            'Garage Door Repair',
            'Garage Door Maintenance',
            
            // Paint & Drywall
            'Painter',
            'Interior Painting',
            'Exterior Painting',
            'Drywall Installation',
            'Drywall Repair',
            'Texture Removal',
            
            // Insulation & Weather
            'Insulation Installation',
            'Weatherization',
            'Caulking Service',
            'Door Sealing',
            
            // Special Services
            'Home Inspection',
            'Home Inspections',
            'Solar Installation',
            'Solar Panel Installation',
            'Solar Energy',
            'Home Theater Installation',
            'Smart Home Installation',
            'Security System Installation',
            
            // Moving & Storage
            'Movers',
            'Moving Company',
            'Local Moving',
            
            // Real Estate & Finance
            'Real Estate Services',
            'Real Estate Agent',
            'Property Management',
            'Mortgage',
            'Home Appraisal',
            
            // Miscellaneous
            'Home Watch',
            'Pool Services',
            'Pool Maintenance',
            'Pool Repair',
            'Hot Tub Installation',
            'Cages and Lanai',
            'Sauna Installation',
            'General'
        );
        
        $created = 0;
        
        foreach ( $categories as $category ) {
            $term = term_exists( $category, 'business_category' );
            
            if ( ! $term ) {
                wp_insert_term( $category, 'business_category' );
                $created++;
            }
        }
        
        return $created;
    }
    
    public static function setup_admin_page() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
    }
    
    public static function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=directory',
            'Setup Categories',
            'Setup Categories',
            'manage_options',
            'lbi-setup-categories',
            array( __CLASS__, 'render_page' )
        );
    }
    
    public static function render_page() {
        if ( isset( $_POST['lbi_create_categories'] ) && check_admin_referer( 'lbi_create_categories' ) ) {
            $created = self::create_default_categories();
            echo '<div class="notice notice-success"><p>Created ' . $created . ' default business categories!</p></div>';
        }
        ?>
        <div class="wrap">
            <h1>Setup Business Categories</h1>
            <p>Click the button below to create all default business categories for your directory.</p>
            
            <form method="post">
                <?php wp_nonce_field( 'lbi_create_categories' ); ?>
                <p>
                    <button type="submit" name="lbi_create_categories" class="button button-primary button-hero">
                        Create Default Categories
                    </button>
                </p>
            </form>
            
            <hr>
            
            <h2>Current Categories</h2>
            <?php
            $terms = get_terms( array(
                'taxonomy' => 'business_category',
                'hide_empty' => false,
                'orderby' => 'name'
            ) );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
            ?>
                <ul>
                    <?php foreach ( $terms as $term ) : ?>
                        <li>
                            <strong><?php echo esc_html( $term->name ); ?></strong>
                            (<?php echo $term->count; ?> listings)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No categories yet. Click the button above to create them!</p>
            <?php endif; ?>
        </div>
        <?php
    }
}

LBI_Category_Setup::setup_admin_page();
