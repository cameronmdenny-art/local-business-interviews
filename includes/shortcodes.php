<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Shortcodes {
    public static function init() {
        add_shortcode( 'business_directory', array( __CLASS__, 'directory_shortcode' ) );
        add_shortcode( 'featured_interviews', array( __CLASS__, 'featured_interviews' ) );
        add_shortcode( 'interview_content', array( __CLASS__, 'interview_content' ) );
        add_shortcode( 'directory_profile', array( __CLASS__, 'directory_profile' ) );
    }

    public static function directory_shortcode( $atts ) {
        $atts = shortcode_atts( array(), $atts );

        // filters from query
        $cat = sanitize_text_field( $_GET['lbi_cat'] ?? '' );
        $city = sanitize_text_field( $_GET['lbi_city'] ?? '' );

        $tax_query = array( 'relation' => 'AND' );
        if ( $cat ) {
            $tax_query[] = array(
                'taxonomy' => 'business_category',
                'field'    => 'slug',
                'terms'    => $cat,
            );
        }
        if ( $city ) {
            $tax_query[] = array(
                'taxonomy' => 'service_city',
                'field'    => 'slug',
                'terms'    => $city,
            );
        }

        $query = new WP_Query( array(
            'post_type'      => 'directory',
            'posts_per_page' => 12,
            'tax_query'      => $tax_query,
        ) );

        ob_start();
        ?>
        <form class="lbi-directory-filters" method="get">
            <div class="lbi-filter-group">
                <div class="lbi-filter-field">
                    <label for="lbi_cat" class="lbi-filter-label"><?php esc_html_e( 'Business Category', 'local-business-interviews' ); ?></label>
                    <select name="lbi_cat" id="lbi_cat" class="lbi-searchable-select">
                        <option value=""><?php esc_html_e( 'All Categories', 'local-business-interviews' ); ?></option>
                        <?php
                        $terms = get_terms( array( 'taxonomy' => 'business_category', 'hide_empty' => false, 'orderby' => 'name' ) );
                        foreach ( $terms as $t ) : ?>
                            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $cat, $t->slug ); ?>><?php echo esc_html( $t->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="lbi-filter-field">
                    <label for="lbi_city" class="lbi-filter-label"><?php esc_html_e( 'Location / City', 'local-business-interviews' ); ?></label>
                    <select name="lbi_city" id="lbi_city" class="lbi-searchable-select">
                        <option value=""><?php esc_html_e( 'All Cities', 'local-business-interviews' ); ?></option>
                        <?php
                        $terms = get_terms( array( 'taxonomy' => 'service_city', 'hide_empty' => false, 'orderby' => 'name' ) );
                        foreach ( $terms as $t ) : ?>
                            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $city, $t->slug ); ?>><?php echo esc_html( $t->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="lbi-filter-field lbi-filter-button">
                    <button type="submit" class="lbi-btn lbi-btn-primary"><?php esc_html_e( 'Search Directory', 'local-business-interviews' ); ?></button>
                </div>
            </div>
        </form>
        <div class="lbi-directory-list">
            <?php if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    $bus_name = get_the_title();
                    $link = get_permalink();
                    ?>
                    <div class="lbi-directory-item">
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $bus_name ); ?></a>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else :
                esc_html_e( 'No listings found.', 'local-business-interviews' );
            endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function featured_interviews( $atts ) {
        $atts = shortcode_atts( array( 'count' => 5 ), $atts );
        $query = new WP_Query( array(
            'post_type'      => 'interview',
            'posts_per_page' => intval( $atts['count'] ),
            'post_status'    => 'publish',
        ) );
        ob_start();
        if ( $query->have_posts() ) :
            echo '<ul class="lbi-featured-interviews">';
            while ( $query->have_posts() ) : $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        return ob_get_clean();
    }

    public static function interview_content( $atts ) {
        global $post;
        if ( 'interview' !== $post->post_type ) {
            return '';
        }
        ob_start();
        $fields = array(
            'business_name','tagline','website','phone','address_street','address_city','address_state','address_zip',
            'social_instagram','social_facebook','social_tiktok','social_linkedin','social_youtube',
        );
        // header
        echo '<div class="lbi-interview-header">';
        echo '<h1>' . esc_html( get_post_meta( $post->ID, 'business_name', true ) ) . '</h1>';
        echo '<p class="lbi-tagline">' . esc_html( get_post_meta( $post->ID, 'tagline', true ) ) . '</p>';
        echo '</div>';
        // contact info
        echo '<div class="lbi-contact-info">';
        if ( $url = get_post_meta( $post->ID, 'website', true ) ) {
            echo '<p><a href="' . esc_url( $url ) . '">Website</a></p>';
        }
        if ( $phone = get_post_meta( $post->ID, 'phone', true ) ) {
            echo '<p>' . esc_html( $phone ) . '</p>';
        }
        echo '</div>';
        // gallery
        $gallery = get_post_meta( $post->ID, 'gallery_ids', true );
        if ( $gallery ) {
            $ids = explode( ',', $gallery );
            echo '<div class="lbi-gallery">';
            foreach ( $ids as $id ) {
                echo wp_get_attachment_image( $id, 'medium' );
            }
            echo '</div>';
        }
        // Q&A
        for ( $i = 1; $i <= 8; $i++ ) {
            $q = get_post_meta( $post->ID, "q$i", true );
            if ( $q ) {
                echo '<h3>Q' . $i . '</h3><p>' . wpautop( esc_html( $q ) ) . '</p>';
            }
        }
        // CTA
        $dir_id = get_post_meta( $post->ID, 'related_directory_id', true );
        if ( ! $dir_id ) {
            // maybe query for directory
            $res = get_posts( array(
                'post_type' => 'directory',
                'meta_query' => array(
                    array('key'=>'related_interview_id','value'=>$post->ID),
                ),
                'posts_per_page' => 1,
            ) );
            if ( $res ) {
                $dir_id = $res[0]->ID;
            }
        }
        if ( $dir_id ) {
            echo '<p><a class="button" href="' . get_permalink( $dir_id ) . '">Read our directory profile</a></p>';
        }

        return ob_get_clean();
    }

    public static function directory_profile( $atts ) {
        global $post;
        if ( 'directory' !== $post->post_type ) {
            return '';
        }
        ob_start();
        echo '<div class="lbi-dir-header">';
        echo '<h1>' . esc_html( get_post_meta( $post->ID, 'business_name', true ) ) . '</h1>';
        echo '</div>';
        if ( $interview = get_post_meta( $post->ID, 'related_interview_id', true ) ) {
            echo '<p><a href="' . get_permalink( $interview ) . '">Read our interview</a></p>';
        }
        return ob_get_clean();
    }
}
