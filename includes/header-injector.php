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
                top: 10px;
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
                padding: 0 18px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                backdrop-filter: blur(22px);
                background: linear-gradient(130deg, rgba(22, 22, 22, 0.95) 0%, rgba(64, 64, 64, 0.72) 48%, rgba(18, 18, 18, 0.95) 100%);
                border-radius: 16px;
                height: 58px;
                border: 1px solid rgba(195, 179, 145, 0.45);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
                pointer-events: auto;
            }

            .lbi-logo {
                display: flex;
                gap: 10px;
                align-items: center;
                text-decoration: none;
                min-width: 190px;
            }

            .lbi-logo img {
                height: 32px;
                width: auto;
            }

            .lbi-logo-text {
                color: #f7f7f7;
                font-size: 12px;
                line-height: 1.1;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                font-weight: 600;
            }

            .lbi-logo-text span {
                color: var(--lbi-gold-hi);
            }

            .lbi-nav-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                flex: 1;
            }

            .lbi-nav-items {
                display: flex;
                gap: 6px;
                list-style: none;
                margin: 0;
                padding: 0;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(195, 179, 145, 0.35);
                border-radius: 12px;
                padding: 3px;
            }

            .lbi-nav-items > li > a {
                color: rgba(255, 255, 255, 0.92);
                text-decoration: none;
                padding: 7px 12px;
                border-radius: 9px;
                font-weight: 600;
                font-size: 11px;
                letter-spacing: 0.01em;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .lbi-nav-items > li > a:hover {
                background-color: rgba(195, 179, 145, 0.2);
                color: var(--lbi-gold-hi);
            }

            .lbi-nav-items > li.is-active > a {
                background-color: rgba(195, 179, 145, 0.28);
                color: var(--lbi-gold-hi);
                font-weight: 600;
            }

            .lbi-header-cta {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 128px;
                height: 36px;
                padding: 0 14px;
                border-radius: 10px;
                border: 1px solid rgba(195, 179, 145, 0.55);
                background: linear-gradient(140deg, rgba(195, 179, 145, 0.26), rgba(235, 215, 161, 0.14));
                color: var(--lbi-gold-hi);
                text-decoration: none;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                transition: all 0.25s ease;
            }

            .lbi-header-cta:hover {
                transform: translateY(-1px);
                background: linear-gradient(140deg, rgba(195, 179, 145, 0.4), rgba(235, 215, 161, 0.22));
                color: #fff6db;
            }

            @media (max-width: 920px) {
                .lbi-global-header-container {
                    max-width: 100%;
                    border-radius: 12px;
                    height: auto;
                    min-height: 58px;
                    padding: 8px 10px;
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .lbi-logo-text {
                    display: none;
                }

                .lbi-logo {
                    min-width: auto;
                }

                .lbi-nav-wrap {
                    order: 3;
                    width: 100%;
                }

                .lbi-nav-items > li > a {
                    padding: 7px 10px;
                    font-size: 10px;
                }

                .lbi-header-cta {
                    min-width: 112px;
                    height: 32px;
                    font-size: 9px;
                }
            }
        </style>
        <?php
    }

    public static function footer_styles() {
        ?>
        <style id="lbi-global-footer-styles">
            #lbi-global-footer {
                background: transparent;
                color: var(--lbi-white);
                padding: 18px 20px 50px;
            }

            .lbi-footer-container {
                max-width: 1180px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: minmax(240px, 1.2fr) repeat(3, minmax(150px, 1fr));
                gap: 26px;
                background: linear-gradient(130deg, rgba(18, 18, 18, 0.98) 0%, rgba(36, 36, 36, 0.92) 45%, rgba(10, 10, 10, 0.98) 100%);
                border: 1px solid rgba(195, 179, 145, 0.45);
                border-radius: 16px;
                padding: 22px 24px;
                box-shadow: 0 14px 36px rgba(0, 0, 0, 0.35);
            }

            .lbi-footer-section h3 {
                font-size: 11px;
                font-weight: 700;
                margin-bottom: 12px;
                letter-spacing: 0.11em;
                text-transform: uppercase;
                color: var(--lbi-gold-hi);
            }

            .lbi-footer-brand {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 10px;
            }

            .lbi-footer-brand img {
                height: 30px;
                width: auto;
                margin-top: 1px;
            }

            .lbi-footer-brand-text {
                color: #f5f5f5;
                font-size: 11px;
                letter-spacing: 0.08em;
                line-height: 1.1;
                font-weight: 600;
                text-transform: uppercase;
            }

            .lbi-footer-section p,
            .lbi-footer-section a {
                font-size: 11px;
                line-height: 1.35;
                margin-bottom: 8px;
                color: rgba(255, 255, 255, 0.72);
                transition: color 0.2s;
                text-decoration: none;
                display: block;
            }

            .lbi-footer-section a:hover {
                color: var(--lbi-gold-hi);
            }

            .lbi-footer-bottom {
                max-width: 1180px;
                margin: 0 auto;
                margin-top: 14px;
                padding-top: 10px;
                text-align: center;
                font-size: 10px;
                color: rgba(255, 255, 255, 0.45);
            }

            @media (max-width: 920px) {
                #lbi-global-footer {
                    padding: 16px 16px 40px;
                }

                .lbi-footer-container {
                    grid-template-columns: 1fr;
                    gap: 14px;
                    padding: 18px;
                }

                .lbi-footer-section h3 {
                    font-size: 10px;
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
                    <span class="lbi-logo-text">LOCAL LEGEND<br><span>STORIES</span></span>
                </a>

                <div class="lbi-nav-wrap">
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

                <a class="lbi-header-cta" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Get Featured</a>
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
        $logo_url = self::get_logo_url();
        ?>
        <footer id="lbi-global-footer">
            <div class="lbi-footer-container">
                <div class="lbi-footer-section">
                    <div class="lbi-footer-brand">
                        <img src="<?php echo esc_attr( $logo_url ); ?>" alt="Local Legend Logo">
                        <div class="lbi-footer-brand-text">Local Legend<br>Stories</div>
                    </div>
                    <p>Premium local storytelling for founders, neighbors, and communities that value meaningful business connections.</p>
                </div>
                <div class="lbi-footer-section">
                    <h3>Explore</h3>
                    <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>">Business Directory</a>
                    <a href="<?php echo esc_url( home_url( '/interviews/' ) ); ?>">Interviews</a>
                    <a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>">Submit Interview</a>
                </div>
                <div class="lbi-footer-section">
                    <h3>Community</h3>
                    <a href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">Recommend Business</a>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
                    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
                </div>
                <div class="lbi-footer-section">
                    <h3>Legal</h3>
                    <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
                    <a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>">Terms of Service</a>
                </div>
            </div>
            <div class="lbi-footer-bottom">
                <p>&copy; <?php echo intval( date( 'Y' ) ); ?> Local Legend Stories. All rights reserved.</p>
            </div>
        </footer>
        <?php
    }
}
