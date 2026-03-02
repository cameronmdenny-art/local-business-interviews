<?php
/**
 * Archive Interview Template
 * Displays all published interview posts with filters and pagination
 */

get_header();
?>

<main id="main" class="site-main lbi-archive-interviews">
    <div class="lbi-container">
        
        <header class="lbi-archive-header">
            <h1 class="page-title"><?php post_type_archive_title( '', true ); ?></h1>
            <p class="lbi-archive-description">
                <?php esc_html_e( 'Explore interviews with local business leaders and entrepreneurs.', 'local-business-interviews' ); ?>
            </p>
        </header>

        <!-- Filters -->
        <div class="lbi-filters">
            <form method="get" class="lbi-filter-form">
                <div class="lbi-filter-group">
                    <label for="lbi-category"><?php esc_html_e( 'Category:', 'local-business-interviews' ); ?></label>
                    <select id="lbi-category" name="category">
                        <option value=""><?php esc_html_e( 'All Categories', 'local-business-interviews' ); ?></option>
                        <?php
                        $terms = get_terms( array( 'taxonomy' => 'business_category', 'hide_empty' => true ) );
                        if ( ! is_wp_error( $terms ) ) :
                            foreach ( $terms as $term ) :
                        ?>
                            <option value="<?php echo esc_attr( $term->slug ); ?>" 
                                    <?php selected( isset( $_GET['category'] ) ? $_GET['category'] : '', $term->slug ); ?>>
                                <?php echo esc_html( $term->name ); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="lbi-filter-group">
                    <label for="lbi-orderby"><?php esc_html_e( 'Sort by:', 'local-business-interviews' ); ?></label>
                    <select id="lbi-orderby" name="orderby">
                        <option value="newest" <?php selected( isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'newest', 'newest' ); ?>>
                            <?php esc_html_e( 'Newest First', 'local-business-interviews' ); ?>
                        </option>
                        <option value="oldest" <?php selected( isset( $_GET['orderby'] ) ? $_GET['orderby'] : '', 'oldest' ); ?>>
                            <?php esc_html_e( 'Oldest First', 'local-business-interviews' ); ?>
                        </option>
                        <option value="title" <?php selected( isset( $_GET['orderby'] ) ? $_GET['orderby'] : '', 'title' ); ?>>
                            <?php esc_html_e( 'Alphabetical', 'local-business-interviews' ); ?>
                        </option>
                    </select>
                </div>

                <button type="submit" class="lbi-filter-btn">
                    <?php esc_html_e( 'Filter', 'local-business-interviews' ); ?>
                </button>
            </form>

            <!-- Search -->
            <div class="lbi-search">
                <form method="get">
                    <input type="hidden" name="post_type" value="interview">
                    <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search interviews...', 'local-business-interviews' ); ?>" value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>">
                    <button type="submit" class="lbi-search-btn">
                        <?php esc_html_e( 'Search', 'local-business-interviews' ); ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Posts Grid -->
        <?php
        if ( have_posts() ) :
        ?>
            <div class="lbi-posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    $post_id = get_the_ID();
                    $company_name = get_post_meta( $post_id, 'company_name', true );
                ?>
                    <article class="lbi-post-card lbi-interview-card">
                        <!-- Featured Image -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="lbi-post-image">
                                <?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="lbi-post-image lbi-placeholder">
                                <span class="lbi-icon">📰</span>
                            </a>
                        <?php endif; ?>

                        <!-- Category Badge -->
                        <?php
                        $categories = get_the_terms( $post_id, 'business_category' );
                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                        ?>
                            <div class="lbi-card-categories">
                                <?php foreach ( array_slice( $categories, 0, 1 ) as $cat ) : ?>
                                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="lbi-category-badge">
                                        <?php echo esc_html( $cat->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h3 class="lbi-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <!-- Company Name -->
                        <?php if ( ! empty( $company_name ) ) : ?>
                            <p class="lbi-company-name">
                                <strong><?php echo esc_html( $company_name ); ?></strong>
                            </p>
                        <?php endif; ?>

                        <!-- Excerpt -->
                        <p class="lbi-post-excerpt">
                            <?php echo esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ); ?>
                        </p>

                        <!-- Read More Link -->
                        <a href="<?php the_permalink(); ?>" class="lbi-read-more">
                            <?php esc_html_e( 'Read Full Interview →', 'local-business-interviews' ); ?>
                        </a>
                    </article>
                <?php
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="lbi-pagination">
                <?php
                the_posts_pagination( array(
                    'prev_text' => __( '← Previous', 'local-business-interviews' ),
                    'next_text' => __( 'Next →', 'local-business-interviews' ),
                ) );
                ?>
            </div>

        <?php
        else :
            // No posts found
        ?>
            <div class="lbi-no-posts">
                <p><?php esc_html_e( 'No interviews found. Check back soon!', 'local-business-interviews' ); ?></p>
                <a href="<?php echo esc_url( home_url() ); ?>" class="lbi-btn">
                    <?php esc_html_e( 'Back to Home', 'local-business-interviews' ); ?>
                </a>
            </div>
        <?php
        endif;
        ?>

    </div>
</main>

<?php get_footer();
