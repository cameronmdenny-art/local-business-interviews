<?php
/**
 * Single Directory Template
 * Displays a single business directory entry with full details, contact info, and related entries
 */

// Ensure helpers are loaded
if ( ! class_exists( 'LBI_Helpers' ) ) {
	$helpers_path = plugin_dir_path( __FILE__ ) . 'includes/helpers.php';
	if ( file_exists( $helpers_path ) ) {
		require_once $helpers_path;
	}
}

get_header();
?>

<main id="main" class="site-main lbi-single-directory">
    <div class="lbi-container">
        <?php
        while ( have_posts() ) :
            the_post();
            $post_id = get_the_ID();
            $business_name = get_post_meta( $post_id, 'business_name', true );
            $website_url = get_post_meta( $post_id, 'website_url', true );
            $email = get_post_meta( $post_id, 'email', true );
            $phone = get_post_meta( $post_id, 'phone', true );
            $address = get_post_meta( $post_id, 'address', true );
            $hours = get_post_meta( $post_id, 'hours_of_operation', true );
            $social_media = get_post_meta( $post_id, 'social_media_links', true );
            $featured = get_post_meta( $post_id, 'featured', true );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'lbi-directory-post' ); ?>>
                
                <!-- Breadcrumbs -->
                <nav class="lbi-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'local-business-interviews' ); ?>">
                    <a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Home', 'local-business-interviews' ); ?></a>
                    <span class="separator">/</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'directory' ) ); ?>"><?php esc_html_e( 'Directory', 'local-business-interviews' ); ?></a>
                    <span class="separator">/</span>
                    <span class="current"><?php the_title(); ?></span>
                </nav>

                <!-- Featured Image -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="lbi-featured-image">
                        <?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
                    </div>
                <?php endif; ?>

                <!-- Header Section -->
                <header class="lbi-post-header">
                    <div class="lbi-post-meta">
                        <!-- Featured Badge -->
                        <?php if ( ! empty( $featured ) ) : ?>
                            <span class="lbi-featured-badge">
                                <?php esc_html_e( 'Featured', 'local-business-interviews' ); ?>
                            </span>
                        <?php endif; ?>

                        <!-- Category badge -->
                        <?php
                        $categories = get_the_terms( $post_id, 'business_category' );
                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                        ?>
                            <div class="lbi-categories">
                                <?php foreach ( $categories as $cat ) : ?>
                                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="lbi-category-badge">
                                        <?php echo esc_html( $cat->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- City badge -->
                        <?php
                        $cities = get_the_terms( $post_id, 'service_city' );
                        if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) :
                        ?>
                            <div class="lbi-cities">
                                <?php foreach ( $cities as $city ) : ?>
                                    <span class="lbi-city-badge">
                                        📍 <?php echo esc_html( $city->name ); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h1 class="lbi-post-title"><?php the_title(); ?></h1>
                </header>

                <!-- Main Content -->
                <div class="lbi-post-content">
                    <?php the_content(); ?>
                </div>

                <!-- Two Column Layout for Contact Info -->
                <div class="lbi-directory-details">
                    <!-- Left Column: Contact Info -->
                    <section class="lbi-contact-section">
                        <h3><?php esc_html_e( 'Contact Information', 'local-business-interviews' ); ?></h3>
                        
                        <?php if ( ! empty( $phone ) ) : ?>
                            <div class="lbi-contact-item">
                                <strong><?php esc_html_e( 'Phone:', 'local-business-interviews' ); ?></strong>
                                <a href="<?php echo 'tel:' . esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>">
                                    <?php echo esc_html( $phone ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $email ) ) : ?>
                            <div class="lbi-contact-item">
                                <strong><?php esc_html_e( 'Email:', 'local-business-interviews' ); ?></strong>
                                <a href="<?php echo 'mailto:' . esc_attr( $email ); ?>">
                                    <?php echo esc_html( $email ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $website_url ) ) : ?>
                            <div class="lbi-contact-item">
                                <strong><?php esc_html_e( 'Website:', 'local-business-interviews' ); ?></strong>
                                <a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html( parse_url( $website_url, PHP_URL_HOST ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $address ) ) : ?>
                            <div class="lbi-contact-item">
                                <strong><?php esc_html_e( 'Address:', 'local-business-interviews' ); ?></strong>
                                <p><?php echo esc_html( $address ); ?></p>
                            </div>
                        <?php endif; ?>
                    </section>

                    <!-- Right Column: Hours & Social -->
                    <section class="lbi-additional-section">
                        <?php if ( ! empty( $hours ) ) : ?>
                            <div class="lbi-hours">
                                <h4><?php esc_html_e( 'Hours of Operation', 'local-business-interviews' ); ?></h4>
                                <?php echo wp_kses_post( nl2br( $hours ) ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $social_media ) ) : ?>
                            <div class="lbi-social-media">
                                <h4><?php esc_html_e( 'Follow Us', 'local-business-interviews' ); ?></h4>
                                <div class="lbi-social-links">
                                    <?php
                                    $social_array = is_string( $social_media ) ? json_decode( $social_media, true ) : $social_media;
                                    if ( is_array( $social_array ) ) :
                                        foreach ( $social_array as $social ) :
                                            if ( ! empty( $social['url'] ) && ! empty( $social['platform'] ) ) :
                                    ?>
                                        <a href="<?php echo esc_url( $social['url'] ); ?>" 
                                           class="lbi-social-link lbi-social-<?php echo esc_attr( strtolower( $social['platform'] ) ); ?>"
                                           target="_blank" rel="noopener noreferrer"
                                           title="<?php echo esc_attr( sprintf( __( 'Follow on %s', 'local-business-interviews' ), $social['platform'] ) ); ?>">
                                            <?php echo esc_html( $social['platform'] ); ?>
                                        </a>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>

                <!-- Share Buttons -->
                <div class="lbi-share-buttons">
                    <h3><?php esc_html_e( 'Share', 'local-business-interviews' ); ?></h3>
                    <a href="<?php echo 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( get_permalink() ); ?>" 
                       class="lbi-share-btn lbi-share-facebook" target="_blank" rel="noopener noreferrer"
                       title="<?php esc_attr_e( 'Share on Facebook', 'local-business-interviews' ); ?>">
                        Facebook
                    </a>
                    <a href="<?php echo 'https://twitter.com/intent/tweet?url=' . urlencode( get_permalink() ) . '&text=' . urlencode( get_the_title() ); ?>" 
                       class="lbi-share-btn lbi-share-twitter" target="_blank" rel="noopener noreferrer"
                       title="<?php esc_attr_e( 'Share on Twitter', 'local-business-interviews' ); ?>">
                        Twitter
                    </a>
                    <a href="<?php echo 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( get_permalink() ); ?>" 
                       class="lbi-share-btn lbi-share-linkedin" target="_blank" rel="noopener noreferrer"
                       title="<?php esc_attr_e( 'Share on LinkedIn', 'local-business-interviews' ); ?>">
                        LinkedIn
                    </a>
                    <a href="<?php echo 'mailto:?subject=' . urlencode( get_the_title() ) . '&body=' . urlencode( get_permalink() ); ?>" 
                       class="lbi-share-btn lbi-share-email"
                       title="<?php esc_attr_e( 'Share by Email', 'local-business-interviews' ); ?>">
                        Email
                    </a>
                </div>

                <!-- Related Directory Entries -->
                <?php
                if ( class_exists( 'LBI_Helpers' ) && method_exists( 'LBI_Helpers', 'get_related_posts' ) ) {
                    $related = LBI_Helpers::get_related_posts( $post_id, 'directory', 'business_category', 3 );
                } else {
                    // Fallback query if helper not available
                    $terms = get_the_terms( $post_id, 'business_category' );
                    $term_ids = ! empty( $terms ) && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
                    $related = new WP_Query( array(
                        'post_type'      => 'directory',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'post__not_in'   => array( $post_id ),
                        'tax_query'      => ! empty( $term_ids ) ? array(
                            array(
                                'taxonomy' => 'business_category',
                                'field'    => 'term_id',
                                'terms'    => $term_ids,
                            ),
                        ) : array(),
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ) );
                }
                
                if ( $related->have_posts() ) :
                ?>
                    <section class="lbi-related-posts">
                        <h3><?php esc_html_e( 'Related Businesses', 'local-business-interviews' ); ?></h3>
                        <div class="lbi-posts-grid">
                            <?php
                            while ( $related->have_posts() ) :
                                $related->the_post();
                            ?>
                                <article class="lbi-post-card">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <a href="<?php the_permalink(); ?>" class="lbi-post-image">
                                            <?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
                                        </a>
                                    <?php endif; ?>
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <p class="lbi-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="lbi-read-more">
                                        <?php esc_html_e( 'View Details', 'local-business-interviews' ); ?>
                                    </a>
                                </article>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

            </article>

            <?php
        endwhile;
        ?>
    </div>
</main>

<?php get_footer();
