<?php
/**
 * Local Legend Stories Global Header/Footer Injector
 *
 * Ensures a single premium glass header and footer are rendered site-wide.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Header_Injector {
    private static $header_rendered = false;
    private static $footer_rendered = false;

    private static function get_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $custom_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            if ( $custom_logo_url ) {
                return $custom_logo_url;
            }
        }

        return esc_url( home_url( '/wp-content/uploads/2026/03/local-legend-logo-transparent.png' ) );
    }

    private static function nav_items() {
        return array(
            array(
                'label' => 'Home',
                'url'   => home_url( '/' ),
            ),
            array(
                'label' => 'Directory',
                'url'   => home_url( '/directory/' ),
            ),
            array(
                'label' => 'Interviews',
                'url'   => home_url( '/interviews/' ),
            ),
            array(
                'label' => 'Recommend',
                'url'   => home_url( '/recommend/' ),
            ),
            array(
                'label' => 'Contact',
                'url'   => home_url( '/contact/' ),
            ),
        );
    }

    private static function is_active( $url ) {
        $current_path = trim( parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );
        $target_path  = trim( parse_url( $url, PHP_URL_PATH ), '/' );

        if ( '' === $target_path ) {
            return '' === $current_path;
        }

        return 0 === strpos( $current_path, $target_path );
    }

    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'hide_theme_elements' ), 1 );
        add_action( 'wp_head', array( __CLASS__, 'header_styles' ), 5 );
        add_action( 'wp_head', array( __CLASS__, 'footer_styles' ), 6 );

        add_action( 'wp_body_open', array( __CLASS__, 'output_header' ), 1 );
        add_action( 'wp_footer', array( __CLASS__, 'output_header_fallback' ), 1 );
        add_action( 'wp_footer', array( __CLASS__, 'output_footer' ), 40 );
    }

    public static function hide_theme_elements() {
        echo '<style id="lbi-hide-theme-header">';
        echo '#page > #header { display: none !important; visibility: hidden; height: 0; margin: 0; padding: 0; overflow: hidden; }';
        echo '#header, #headerimg, #headerimg > h1, #headerimg > h1 > a { display: none !important; visibility: hidden; }';
        echo '#page > #header ~ hr { display: none !important; }';
        echo '.site-header, header.site-header, #masthead, .site-title, .site-title a, .site-description { display: none !important; }';
        echo '</style>';
    }

    public static function header_styles() {
        ?>
        <style id="lbi-global-header-styles">
            :root {
                --lbi-black: #000000;
                --lbi-white: #ffffff;
                --lbi-gold: #c3b391;
                --lbi-gold-hi: #ebd7a1;
                --lbi-gold-low: #9f8f64;
                --lbi-header-height: 84px;
            }

            body.has-lbi-global-header {
                padding-top: calc(var(--lbi-header-height) + 20px);
            }

            #lbi-global-header {
                position: fixed;
                top: 14px;
                left: 0;
                right: 0;
                z-index: 99999;
                display: flex;
                justify-content: center;
                pointer-events: none;
            }

            .lbi-global-header-container {
                width: 100%;
                max-width: 1180px;
                padding: 0 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                backdrop-filter: blur(20px);
                background-color: rgba(255, 255, 255, 0.88);
                border-radius: 24px;
                padding-left: 20px;
                padding-right: 20px;
                height: 56px;
                border: 1px solid rgba(195, 179, 145, 0.35);
                pointer-events: auto;
            }

            .lbi-logo {
                font-weight: 700;
                font-size: 20px;
                letter-spacing: -0.5px;
                display: flex;
                gap: 10px;
                align-items: center;
                text-decoration: none;
            }

            .lbi-logo img {
                height: 40px;
                width: auto;
            }

            .lbi-logo-text {
                color: var(--lbi-black);
            }

            .lbi-nav-items {
                display: flex;
                gap: 2px;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .lbi-nav-items > li > a {
                color: var(--lbi-black);
                text-decoration: none;
                padding: 8px 20px;
                border-radius: 12px;
                font-weight: 500;
                font-size: 14px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .lbi-nav-items > li > a:hover {
                background-color: rgba(195, 179, 145, 0.15);
                color: var(--lbi-gold);
            }

            .lbi-nav-items > li.is-active > a {
                background-color: rgba(195, 179, 145, 0.25);
                color: var(--lbi-gold);
                font-weight: 600;
            }

            @media (max-width: 920px) {
                .lbi-global-header-container {
                    max-width: 100%;
                    border-radius: 0;
                    padding: 12px 20px;
                }

                .lbi-logo-text {
                    display: none;
                }

                .lbi-nav-items > li > a {
                    padding: 8px 14px;
                    font-size: 12px;
                }
            }
        </style>
        <?php
    }

    public static function footer_styles() {
        ?>
        <style id="lbi-global-footer-styles">
            #lbi-global-footer {
                background-color: var(--lbi-black);
                color: var(--lbi-white);
                padding: 40px 20px 80px;
            }

            .lbi-footer-container {
                max-width: 1180px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 40px;
            }

            .lbi-footer-section h3 {
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 20px;
                color: var(--lbi-gold);
            }

            .lbi-footer-section p,
            .lbi-footer-section a {
                font-size: 14px;
                line-height: 1.6;
                margin-bottom: 12px;
                color: rgba(255, 255, 255, 0.75);
                transition: color 0.2s;
                text-decoration: none;
                display: block;
            }

            .lbi-footer-section a:hover {
                color: var(--lbi-gold);
            }

            .lbi-footer-bottom {
                max-width: 1180px;
                margin: 0 auto;
                margin-top: 40px;
                padding-top: 40px;
                border-top: 1px solid rgba(195, 179, 145, 0.25);
                text-align: center;
                font-size: 12px;
                color: rgba(255, 255, 255, 0.5);
            }

            @media (max-width: 920px) {
                #lbi-global-footer {
                    padding: 30px 20px 60px;
                }

                .lbi-footer-container {
                    gap: 30px;
                }

                .lbi-footer-section h3 {
                    font-size: 16px;
                }
            }
        </style>
        <?php
    }

    public static function output_header() {
        if ( self::$header_rendered ) {
            return;
        }
        self::$header_rendered = true;

        $logo_url  = self::get_logo_url();
        $nav_items = self::nav_items();
        ?>
        <header id="lbi-global-header">
            <div class="lbi-global-header-container">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="lbi-logo" title="Local Legend Stories">
                    <img src="<?php echo esc_attr( $logo_url ); ?>" alt="Local Legend Logo">
                    <span class="lbi-logo-text">LOCAL LEGEND STORIES</span>
                </a>
                <nav>
                    <ul class="lbi-nav-items">
                        <?php foreach ( $nav_items as $item ) : ?>
                            <li class="<?php echo self::is_active( $item['url'] ) ? 'is-active' : ''; ?>">
                                <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </header>
        <script>
            document.documentElement.classList.add('has-lbi-global-header');
            if (document.body) {
                document.body.classList.add('has-lbi-global-header');
            }
        </script>
        <?php
    }

    public static function output_header_fallback() {
        self::output_header();
    }

    public static function output_footer() {
        if ( self::$footer_rendered ) {
            return;
        }
        self::$footer_rendered = true;
        ?>
        <footer id="lbi-global-footer">
            <div class="lbi-footer-container">
                <div class="lbi-footer-section">
                    <h3>About</h3>
                    <p>Local Legend Stories showcases the people and businesses that make our community special.</p>
                </div>
                <div class="lbi-footer-section">
                    <h3>Explore</h3>
                    <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>">Business Directory</a>
                    <a href="<?php echo esc_url( home_url( '/interviews/' ) ); ?>">Interviews</a>
                    <a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Recommend</a>
                </div>
                <div class="lbi-footer-section">
                    <h3>Connect</h3>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                </div>
            </div>
            <div class="lbi-footer-bottom">
                <p>&copy; <?php echo intval( date( 'Y' ) ); ?> Local Legend Stories. All rights reserved.</p>
            </div>
        </footer>
        <?php
    }
}
