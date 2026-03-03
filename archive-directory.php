<?php
/**
 * Archive Directory Template
 * Clean list + map directory experience with category discovery.
 */

get_header();

echo "<!-- LBI_TEMPLATE_SOURCE: " . esc_html( basename( __FILE__ ) ) . " | BUILD: 2026-03-02-verify -->";

$archive_link       = get_post_type_archive_link( 'directory' );
$selected_name      = isset( $_GET['business_name'] ) ? sanitize_text_field( wp_unslash( $_GET['business_name'] ) ) : '';
$selected_location  = isset( $_GET['location'] ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : '';
$selected_category  = isset( $_GET['business_category'] ) ? sanitize_text_field( wp_unslash( $_GET['business_category'] ) ) : '';
$current_term_title = '';

if ( is_tax() ) {
    $current_term      = get_queried_object();
    $current_term_title = $current_term && ! is_wp_error( $current_term ) ? $current_term->name : '';

    if ( is_tax( 'business_category' ) && empty( $selected_category ) && isset( $current_term->slug ) ) {
        $selected_category = $current_term->slug;
    }

    if ( is_tax( 'service_city' ) && empty( $selected_location ) && isset( $current_term->slug ) ) {
        $selected_location = $current_term->slug;
    }
}

$categories = get_terms(
    array(
        'taxonomy'   => 'business_category',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);

$cities = get_terms(
    array(
        'taxonomy'   => 'service_city',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);

$pagination_args = array_filter(
    array(
        'business_name'     => $selected_name,
        'location'          => $selected_location,
        'business_category' => $selected_category,
    )
);

$frontend_css_url = plugin_dir_url( __FILE__ ) . 'assets/css/frontend.css';
$frontend_css_ver = file_exists( __DIR__ . '/assets/css/frontend.css' ) ? (string) filemtime( __DIR__ . '/assets/css/frontend.css' ) : (string) time();

$animations_css_url = plugin_dir_url( __FILE__ ) . 'assets/css/animations.css';
$animations_css_ver = file_exists( __DIR__ . '/assets/css/animations.css' ) ? (string) filemtime( __DIR__ . '/assets/css/animations.css' ) : (string) time();
?>

<link rel="stylesheet" href="<?php echo esc_url( add_query_arg( 'v', $frontend_css_ver, $frontend_css_url ) ); ?>" media="all" />
<link rel="stylesheet" href="<?php echo esc_url( add_query_arg( 'v', $animations_css_ver, $animations_css_url ) ); ?>" media="all" />

<style id="lbi-directory-premium-ui">
main.lbi-directory-app {
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    padding: 28px 0 54px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Inter, 'Helvetica Neue', Arial, sans-serif;
}

main.lbi-directory-app .lbi-directory-shell {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

main.lbi-directory-app .lbi-directory-title {
    margin: 0;
    font-size: clamp(34px, 4vw, 52px);
    line-height: 1.05;
    letter-spacing: -0.03em;
    color: #0f172a;
    font-weight: 700;
}

main.lbi-directory-app .lbi-directory-subtitle {
    margin: 12px 0 0;
    font-size: 17px;
    color: #475569;
}

main.lbi-directory-app .lbi-directory-controls,
main.lbi-directory-app .lbi-results-list,
main.lbi-directory-app .lbi-map-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
}

main.lbi-directory-app .lbi-directory-controls {
    margin: 22px 0 26px;
    padding: 18px;
}

main.lbi-directory-app .lbi-directory-filter-form {
    display: grid;
    grid-template-columns: 1.2fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

main.lbi-directory-app .lbi-filter-field { display: grid; gap: 6px; }

main.lbi-directory-app .lbi-filter-field label {
    font-size: 12px;
    color: #475569;
    font-weight: 600;
}

main.lbi-directory-app .lbi-filter-field input,
main.lbi-directory-app .lbi-filter-field select {
    width: 100%;
    height: 48px;
    padding: 0 14px;
    font-size: 15px;
    color: #0f172a;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    background: #fff;
}

main.lbi-directory-app .lbi-filter-field input:focus,
main.lbi-directory-app .lbi-filter-field select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
}

main.lbi-directory-app .lbi-filter-actions { display: flex; gap: 10px; }

main.lbi-directory-app .lbi-btn-primary,
main.lbi-directory-app .lbi-btn-ghost,
main.lbi-directory-app .lbi-map-focus-btn {
    height: 48px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #fff;
    color: #0f172a;
    padding: 0 16px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

main.lbi-directory-app .lbi-btn-primary {
    background: #0f172a;
    border-color: #0f172a;
    color: #fff;
}

main.lbi-directory-app .lbi-btn-primary:hover { background: #1e293b; border-color: #1e293b; }
main.lbi-directory-app .lbi-btn-ghost:hover,
main.lbi-directory-app .lbi-map-focus-btn:hover { background: #f8fafc; }

main.lbi-directory-app .lbi-section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

main.lbi-directory-app .lbi-section-head h2 {
    margin: 0;
    font-size: 24px;
    color: #0f172a;
    letter-spacing: -0.02em;
}

main.lbi-directory-app .lbi-results-count {
    min-width: 34px;
    height: 28px;
    border-radius: 999px;
    padding: 0 10px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

main.lbi-directory-app .lbi-category-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
}

main.lbi-directory-app .lbi-category-chip {
    text-decoration: none;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    padding: 12px;
    background: #fff;
    color: #0f172a;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    transition: all .2s ease;
}

main.lbi-directory-app .lbi-category-chip:hover {
    border-color: #93c5fd;
    background: #f8fbff;
    transform: translateY(-1px);
}

main.lbi-directory-app .lbi-category-chip-name { font-size: 14px; font-weight: 600; }
main.lbi-directory-app .lbi-category-chip-count { font-size: 12px; color: #2563eb; font-weight: 700; }

main.lbi-directory-app .lbi-directory-results-wrap {
    margin-top: 14px;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(360px, 44%);
    gap: 16px;
}

main.lbi-directory-app .lbi-results-list,
main.lbi-directory-app .lbi-map-panel { padding: 16px; }

main.lbi-directory-app .lbi-directory-listings { display: grid; gap: 12px; }

main.lbi-directory-app .lbi-directory-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px;
    background: #fff;
}

main.lbi-directory-app .lbi-directory-card-title {
    margin: 0 0 7px;
    font-size: 23px;
    line-height: 1.15;
    letter-spacing: -0.02em;
}

main.lbi-directory-app .lbi-directory-card-title a { color: #0f172a; text-decoration: none; }
main.lbi-directory-app .lbi-directory-card-title a:hover { color: #1d4ed8; }

main.lbi-directory-app .lbi-directory-card-cats {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
}

main.lbi-directory-app .lbi-directory-card-cats a {
    border: 1px solid #dbeafe;
    background: #eff6ff;
    color: #1e40af;
    border-radius: 999px;
    padding: 4px 10px;
    font-size: 12px;
    text-decoration: none;
    font-weight: 600;
}

main.lbi-directory-app .lbi-directory-card-location,
main.lbi-directory-app .lbi-directory-card-address,
main.lbi-directory-app .lbi-directory-card-phone {
    margin: 6px 0;
    color: #475569;
    font-size: 14px;
}

main.lbi-directory-app .lbi-directory-card-phone a { color: #0f172a; text-decoration: none; font-weight: 600; }

main.lbi-directory-app .lbi-directory-card-actions {
    margin-top: 12px;
    display: flex;
    gap: 8px;
}

main.lbi-directory-app .lbi-map-panel { position: sticky; top: 20px; }

main.lbi-directory-app .lbi-map-head h2 {
    margin: 0;
    font-size: 20px;
    letter-spacing: -0.02em;
    color: #0f172a;
}

main.lbi-directory-app .lbi-map-head p { margin: 8px 0 12px; color: #64748b; font-size: 13px; }

main.lbi-directory-app .lbi-directory-map {
    min-height: 560px;
    border-radius: 12px;
    border: 1px solid #dbe3ef;
    overflow: hidden;
}

main.lbi-directory-app .lbi-empty,
main.lbi-directory-app .lbi-empty-results { color: #64748b; }

main.lbi-directory-app .lbi-pagination {
    margin-top: 16px;
    padding-top: 8px;
}

main.lbi-directory-app .lbi-pagination .page-numbers {
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 8px 11px;
    margin-right: 6px;
    text-decoration: none;
    color: #0f172a;
    display: inline-block;
}

main.lbi-directory-app .lbi-pagination .current,
main.lbi-directory-app .lbi-pagination a:hover {
    background: #0f172a;
    color: #fff;
    border-color: #0f172a;
}

@media (max-width: 1060px) {
    main.lbi-directory-app .lbi-directory-results-wrap { grid-template-columns: 1fr; }
    main.lbi-directory-app .lbi-map-panel { position: static; }
    main.lbi-directory-app .lbi-directory-map { min-height: 430px; }
}

@media (max-width: 860px) {
    main.lbi-directory-app .lbi-directory-filter-form { grid-template-columns: 1fr; }
    main.lbi-directory-app .lbi-filter-actions { width: 100%; }
    main.lbi-directory-app .lbi-btn-primary,
    main.lbi-directory-app .lbi-btn-ghost { flex: 1; }
    main.lbi-directory-app .lbi-directory-card-actions { flex-direction: column; }
}
</style>

<main id="main" class="site-main lbi-directory-app" style="background:linear-gradient(180deg,#f8fafc 0%,#ffffff 100%);padding:28px 0 54px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Inter,'Helvetica Neue',Arial,sans-serif;">
    <div class="lbi-directory-shell" style="max-width:1360px;margin:0 auto;padding:0 20px;">
        <header class="lbi-directory-hero" style="margin-bottom:18px;">
            <h1 class="lbi-directory-title"><?php echo esc_html( $current_term_title ? $current_term_title : __( 'Business Directory', 'local-business-interviews' ) ); ?></h1>
            <p class="lbi-directory-subtitle"><?php esc_html_e( 'Search by business name, service location, or category.', 'local-business-interviews' ); ?></p>
        </header>

        <section class="lbi-directory-controls" aria-label="<?php esc_attr_e( 'Directory filters', 'local-business-interviews' ); ?>" style="margin:22px 0 26px;padding:18px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 8px 30px rgba(15,23,42,.05);">
            <form class="lbi-directory-filter-form" method="get" action="<?php echo esc_url( $archive_link ); ?>" style="display:grid;grid-template-columns:1.2fr 1fr 1fr auto;gap:12px;align-items:end;">
                <div class="lbi-filter-field" style="display:grid;gap:6px;">
                    <label for="lbi-business-name"><?php esc_html_e( 'Business name', 'local-business-interviews' ); ?></label>
                    <input id="lbi-business-name" type="search" name="business_name" placeholder="<?php esc_attr_e( 'Search business name', 'local-business-interviews' ); ?>" value="<?php echo esc_attr( $selected_name ); ?>" style="height:48px;border:1px solid #cbd5e1;border-radius:12px;padding:0 14px;font-size:15px;">
                </div>

                <div class="lbi-filter-field" style="display:grid;gap:6px;">
                    <label for="lbi-location"><?php esc_html_e( 'Location', 'local-business-interviews' ); ?></label>
                    <select id="lbi-location" name="location" style="height:48px;border:1px solid #cbd5e1;border-radius:12px;padding:0 12px;font-size:15px;">
                        <option value=""><?php esc_html_e( 'All service cities', 'local-business-interviews' ); ?></option>
                        <?php if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) : ?>
                            <?php foreach ( $cities as $city ) : ?>
                                <option value="<?php echo esc_attr( $city->slug ); ?>" <?php selected( $selected_location, $city->slug ); ?>>
                                    <?php echo esc_html( $city->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="lbi-filter-field" style="display:grid;gap:6px;">
                    <label for="lbi-business-category"><?php esc_html_e( 'Category', 'local-business-interviews' ); ?></label>
                    <select id="lbi-business-category" name="business_category" style="height:48px;border:1px solid #cbd5e1;border-radius:12px;padding:0 12px;font-size:15px;">
                        <option value=""><?php esc_html_e( 'All categories', 'local-business-interviews' ); ?></option>
                        <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                            <?php foreach ( $categories as $category ) : ?>
                                <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $selected_category, $category->slug ); ?>>
                                    <?php echo esc_html( $category->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="lbi-filter-actions" style="display:flex;gap:10px;">
                    <button type="submit" class="lbi-btn-primary" style="height:48px;border-radius:12px;padding:0 16px;border:1px solid #0f172a;background:#0f172a;color:#fff;font-weight:600;cursor:pointer;"><?php esc_html_e( 'Search', 'local-business-interviews' ); ?></button>
                    <a class="lbi-btn-ghost" href="<?php echo esc_url( $archive_link ); ?>" style="height:48px;border-radius:12px;padding:0 16px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-weight:600;display:inline-flex;align-items:center;text-decoration:none;"><?php esc_html_e( 'Reset', 'local-business-interviews' ); ?></a>
                </div>
            </form>
        </section>

        <section id="lbi-categories" class="lbi-category-index" aria-label="<?php esc_attr_e( 'Business categories', 'local-business-interviews' ); ?>">
            <div class="lbi-section-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;">
                <h2><?php esc_html_e( 'Browse All Categories', 'local-business-interviews' ); ?></h2>
            </div>

            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                <div class="lbi-category-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:10px;">
                    <?php foreach ( $categories as $category ) : ?>
                        <a class="lbi-category-chip" href="<?php echo esc_url( get_term_link( $category ) ); ?>" style="border:1px solid #dbe3ef;border-radius:12px;padding:12px;background:#fff;color:#0f172a;text-decoration:none;display:flex;justify-content:space-between;align-items:center;">
                            <span class="lbi-category-chip-name"><?php echo esc_html( $category->name ); ?></span>
                            <span class="lbi-category-chip-count"><?php echo esc_html( (string) $category->count ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="lbi-empty"><?php esc_html_e( 'No categories yet.', 'local-business-interviews' ); ?></p>
            <?php endif; ?>
        </section>

        <section class="lbi-directory-results-wrap" style="margin-top:14px;display:grid;grid-template-columns:minmax(0,1fr) minmax(360px,44%);gap:16px;align-items:start;">
            <div class="lbi-results-list" style="border:1px solid #e2e8f0;border-radius:16px;background:#fff;box-shadow:0 8px 30px rgba(15,23,42,.05);padding:16px;">
                <div class="lbi-section-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;">
                    <h2><?php esc_html_e( 'Businesses', 'local-business-interviews' ); ?></h2>
                    <span class="lbi-results-count" style="min-width:34px;height:28px;padding:0 10px;border-radius:999px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-size:12px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;"><?php echo esc_html( (string) $wp_query->found_posts ); ?></span>
                </div>

                <?php if ( have_posts() ) : ?>
                    <div class="lbi-directory-listings" id="lbi-directory-listings" style="display:grid;gap:12px;">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php
                            $post_id       = get_the_ID();
                            $business_name = get_post_meta( $post_id, 'business_name', true );
                            $phone         = get_post_meta( $post_id, 'phone', true );
                            $address       = get_post_meta( $post_id, 'address', true );
                            $service_area  = get_post_meta( $post_id, 'service_area', true );
                            $city_terms    = get_the_terms( $post_id, 'service_city' );
                            $category_terms = get_the_terms( $post_id, 'business_category' );
                            $city_label    = '';

                            if ( ! empty( $city_terms ) && ! is_wp_error( $city_terms ) ) {
                                $city_label = implode( ', ', wp_list_pluck( $city_terms, 'name' ) );
                            }

                            $map_source = $address;
                            if ( empty( $map_source ) ) {
                                $map_source = $service_area;
                            }
                            if ( empty( $map_source ) ) {
                                $map_source = $city_label;
                            }
                            ?>
                            <article class="lbi-directory-card" style="border:1px solid #e2e8f0;border-radius:14px;background:#fff;padding:14px;"
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                data-address="<?php echo esc_attr( $map_source ); ?>"
                                data-city="<?php echo esc_attr( $city_label ); ?>"
                                data-link="<?php echo esc_url( get_permalink() ); ?>">
                                <div class="lbi-directory-card-main">
                                    <h3 class="lbi-directory-card-title" style="margin:0 0 8px;font-size:24px;line-height:1.15;letter-spacing:-.02em;">
                                        <a href="<?php the_permalink(); ?>"><?php echo esc_html( $business_name ? $business_name : get_the_title() ); ?></a>
                                    </h3>

                                    <?php if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) : ?>
                                        <div class="lbi-directory-card-cats" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                                            <?php foreach ( $category_terms as $term ) : ?>
                                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" style="border:1px solid #dbeafe;background:#eff6ff;color:#1e40af;border-radius:999px;padding:4px 10px;font-size:12px;text-decoration:none;font-weight:600;"><?php echo esc_html( $term->name ); ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $city_label ) ) : ?>
                                        <p class="lbi-directory-card-location">📍 <?php echo esc_html( $city_label ); ?></p>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $address ) ) : ?>
                                        <p class="lbi-directory-card-address"><?php echo esc_html( $address ); ?></p>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $phone ) ) : ?>
                                        <p class="lbi-directory-card-phone">
                                            <a href="<?php echo esc_url( 'tel:' . preg_replace( '/\D+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="lbi-directory-card-actions" style="margin-top:12px;display:flex;gap:8px;">
                                    <a class="lbi-btn-ghost" href="<?php the_permalink(); ?>" style="height:42px;border-radius:12px;padding:0 16px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-weight:600;display:inline-flex;align-items:center;text-decoration:none;"><?php esc_html_e( 'View Business', 'local-business-interviews' ); ?></a>
                                    <button type="button" class="lbi-map-focus-btn" style="height:42px;border-radius:12px;padding:0 14px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-weight:600;cursor:pointer;"><?php esc_html_e( 'Show on Map', 'local-business-interviews' ); ?></button>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="lbi-pagination">
                        <?php
                        the_posts_pagination(
                            array(
                                'prev_text' => __( '← Previous', 'local-business-interviews' ),
                                'next_text' => __( 'Next →', 'local-business-interviews' ),
                                'add_args'  => $pagination_args,
                            )
                        );
                        ?>
                    </div>
                <?php else : ?>
                    <div class="lbi-empty-results">
                        <p><?php esc_html_e( 'No businesses matched your filters.', 'local-business-interviews' ); ?></p>
                        <a class="lbi-btn-ghost" href="<?php echo esc_url( $archive_link ); ?>"><?php esc_html_e( 'View all businesses', 'local-business-interviews' ); ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="lbi-map-panel" aria-label="<?php esc_attr_e( 'Business map', 'local-business-interviews' ); ?>" style="position:sticky;top:20px;border:1px solid #e2e8f0;border-radius:16px;background:#fff;box-shadow:0 8px 30px rgba(15,23,42,.05);padding:16px;">
                <div class="lbi-map-head">
                    <h2><?php esc_html_e( 'Service Area Map', 'local-business-interviews' ); ?></h2>
                    <p><?php esc_html_e( 'Locations are based on listing address or service city.', 'local-business-interviews' ); ?></p>
                </div>
                <div id="lbi-directory-map" class="lbi-directory-map" aria-hidden="false" style="min-height:560px;border:1px solid #dbe3ef;border-radius:12px;overflow:hidden;"></div>
            </aside>
        </section>
    </div>
</main>

<?php get_footer();
