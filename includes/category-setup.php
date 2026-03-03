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
            'Air Conditioning and Heating',
            'Cleaning Service',
            'Fencing',
            'Garage Door Maintenance',
            'Gutters',
            'Home Watch',
            'Movers',
            'Pool Services',
            'Real Estate Services',
            'Roofing',
            'Tree Services',
            'Cages and Lanai',
            'Electric',
            'Flooring',
            'General',
            'Home Inspections',
            'Mortgage',
            'Plumbing',
            'Pressure Washing',
            'Remodel & Construction',
            'Solar',
            'Water Services'
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
