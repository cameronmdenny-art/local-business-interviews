<?php
/**
 * Single Interview Template
 * Displays a single interview post with full content, metadata, and related posts
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

<main id="main" class="site-main lbi-single-interview">
    <div class="lbi-container">
        <?php
        while ( have_posts() ) :
            the_post();
            $post_id = get_the_ID();
            $interviewee_name = get_post_meta( $post_id, 'interviewee_name', true );
            $interviewee_title = get_post_meta( $post_id, 'interviewee_title', true );
            $company_name = get_post_meta( $post_id, 'company_name', true );
            $company_website = get_post_meta( $post_id, 'company_website', true );
            $email = get_post_meta( $post_id, 'email', true );
            $phone = get_post_meta( $post_id, 'phone', true );
            $video_url = get_post_meta( $post_id, 'video_url', true );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'lbi-interview-post' ); ?>>
                
                <!-- Breadcrumbs -->
                <nav class="lbi-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'local-business-interviews' ); ?>">
                    <a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Home', 'local-business-interviews' ); ?></a>
                    <span class="separator">/</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'interview' ) ); ?>"><?php esc_html_e( 'Interviews', 'local-business-interviews' ); ?></a>
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
                        <?php
                        // Category badge
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

                        <!-- Date -->
                        <span class="lbi-date">
                            <?php 
                            if ( class_exists( 'LBI_Helpers' ) && method_exists( 'LBI_Helpers', 'format_date' ) ) {
                                echo esc_html( LBI_Helpers::format_date( get_the_date( 'c' ) ) );
                            } else {
                                echo esc_html( get_the_date() );
                            }
                            ?>
                        </span>
                    </div>

                    <h1 class="lbi-post-title"><?php the_title(); ?></h1>

                    <!-- Interviewee Info -->
                    <div class="lbi-interviewee-info">
                        <?php if ( ! empty( $interviewee_name ) ) : ?>
                            <p class="lbi-interviewee-name">
                                <strong><?php esc_html_e( 'Interviewee:', 'local-business-interviews' ); ?></strong>
                                <?php echo esc_html( $interviewee_name ); ?>
                                <?php if ( ! empty( $interviewee_title ) ) : ?>
                                    <span class="lbi-interviewee-title"><?php echo esc_html( $interviewee_title ); ?></span>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $company_name ) ) : ?>
                            <p class="lbi-company-name">
                                <strong><?php esc_html_e( 'Company:', 'local-business-interviews' ); ?></strong>
                                <?php echo esc_html( $company_name ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="lbi-post-content">
                    <?php the_content(); ?>
                </div>

                <!-- Video Embed (if provided) -->
                <?php if ( ! empty( $video_url ) ) : ?>
                    <div class="lbi-video-container">
                        <h3><?php esc_html_e( 'Video', 'local-business-interviews' ); ?></h3>
                        <div class="lbi-video-embed">
                            <?php echo wp_oembed_get( esc_url( $video_url ) ); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Contact Information -->
                <aside class="lbi-contact-info">
                    <h3><?php esc_html_e( 'Contact Information', 'local-business-interviews' ); ?></h3>
                    
                    <?php if ( ! empty( $company_website ) ) : ?>
                        <p class="lbi-contact-item">
                            <strong><?php esc_html_e( 'Website:', 'local-business-interviews' ); ?></strong><br>
                            <a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html( parse_url( $company_website, PHP_URL_HOST ) ); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $email ) ) : ?>
                        <p class="lbi-contact-item">
                            <strong><?php esc_html_e( 'Email:', 'local-business-interviews' ); ?></strong><br>
                            <a href="<?php echo 'mailto:' . esc_attr( $email ); ?>">
                                <?php echo esc_html( $email ); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $phone ) ) : ?>
                        <p class="lbi-contact-item">
                            <strong><?php esc_html_e( 'Phone:', 'local-business-interviews' ); ?></strong><br>
                            <a href="<?php echo 'tel:' . esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>">
                                <?php echo esc_html( $phone ); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </aside>

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

                <!-- Related Interviews -->
                <?php
                if ( class_exists( 'LBI_Helpers' ) && method_exists( 'LBI_Helpers', 'get_related_posts' ) ) {
                    $related = LBI_Helpers::get_related_posts( $post_id, 'interview', 'business_category', 3 );
                } else {
                    // Fallback query if helper not available
                    $terms = get_the_terms( $post_id, 'business_category' );
                    $term_ids = ! empty( $terms ) && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
                    $related = new WP_Query( array(
                        'post_type'      => 'interview',
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
                        <h3><?php esc_html_e( 'Related Interviews', 'local-business-interviews' ); ?></h3>
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
                                        <?php esc_html_e( 'Read Full Interview', 'local-business-interviews' ); ?>
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
