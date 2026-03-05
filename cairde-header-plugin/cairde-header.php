<?php
/**
 * Plugin Name: Cairde Header Injector
 * Description: Intro header + static top navigation for Cairde Designs
 * Version: 3.3.0
 * Author: Cairde Designs
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_footer', function () {
    if (is_admin()) {
        return;
    }

    $home = esc_url(home_url('/'));
    $is_homepage = is_front_page() || is_home();
    ?>
    <style>
        #cdHeroHeader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100vh;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 20%, rgba(176,138,60,0.08), transparent 42%), radial-gradient(circle at 80% 75%, rgba(0,0,0,0.04), transparent 44%), #ffffff;
            color: #111111;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            transition: opacity 0.62s cubic-bezier(0.22, 1, 0.36, 1), visibility 0.62s cubic-bezier(0.22, 1, 0.36, 1);
            overflow: hidden;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Homepage: reserve space for hero */
        body.cd-homepage {
            overflow-x: hidden;
        }

        body.cd-homepage::before {
            content: "";
            display: block;
            height: 100vh;
            pointer-events: none;
        }

        /* Fade out overlay when scrolling past */
        body.cd-scrolled #cdHeroHeader {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Hide hero on non-homepage pages */
        body:not(.cd-homepage) #cdHeroHeader {
            display: none !important;
        }

        /* Show sticky nav from top on non-homepage pages */
        body:not(.cd-homepage) #cdTopNav {
            opacity: 1 !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            position: fixed !important;
            top: 0 !important;
        }

        /* Add padding on non-homepage pages so content isn't hidden under nav */
        body:not(.cd-homepage) {
            padding-top: 120px;
        }

        #cdHeroHeader::before,
        #cdHeroHeader::after {
            content: "";
            position: absolute;
            width: 46vw;
            height: 46vw;
            max-width: 520px;
            max-height: 520px;
            border-radius: 50%;
            filter: blur(24px);
            opacity: 0.35;
            pointer-events: none;
            z-index: 0;
        }

        #cdHeroHeader::before {
            background: radial-gradient(circle, rgba(176,138,60,0.18) 0%, rgba(176,138,60,0.04) 55%, transparent 75%);
            top: -12%;
            left: -8%;
            animation: cdWaterDriftA 13s ease-in-out infinite;
        }

        #cdHeroHeader::after {
            background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.03) 52%, transparent 75%);
            bottom: -20%;
            right: -10%;
            animation: cdWaterDriftB 16s ease-in-out infinite;
        }

        body.cd-scrolled #cdHeroHeader {
            transform: translateY(-100vh);
        }

        #cdHeroInner {
            text-align: center;
            width: 100%;
            max-width: 640px;
            padding: 2rem;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            backdrop-filter: blur(14px) saturate(145%);
            -webkit-backdrop-filter: blur(14px) saturate(145%);
            box-shadow: 0 18px 46px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.95);
            animation: cdFloatGlass 7.5s ease-in-out infinite;
        }

        #cdHeroTitle {
            margin: 0;
            font-size: 3.4rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: #111111;
        }

        #cdHeroTitle .gold {
            color: #b08a3c;
        }

        #cdHeroTag {
            margin: 1rem 0 2rem;
            color: #444444;
            font-size: 0.95rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        #cdHeroButtons {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .cd-btn {
            display: block;
            padding: 14px 22px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1rem;
            border: 2px solid #d6c29a;
            color: #111111;
            background: linear-gradient(180deg, #ffffff 0%, #fbf9f3 100%);
            transition: transform 0.34s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.34s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.34s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .cd-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(255,255,255,0) 25%, rgba(255,255,255,0.55) 50%, rgba(255,255,255,0) 75%);
            transform: translateX(-120%);
            transition: transform 0.72s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .cd-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.15);
            border-color: #b08a3c;
        }

        .cd-btn:hover::before { transform: translateX(120%); }

        .cd-btn.cd-btn-gold {
            background: linear-gradient(145deg, #c8a25b 0%, #a57b2f 100%);
            color: #ffffff;
            border-color: #a57b2f;
            box-shadow: 0 10px 24px rgba(176, 138, 60, 0.35);
        }

        #cdTopNav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 99998;
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid #ececec;
            backdrop-filter: saturate(150%) blur(9px);
            opacity: 0;
            transform: translateY(-100%);
            pointer-events: none;
            transition: all 0.52s cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
        }

        /* Hide any breadcrumbs or address elements */
        .breadcrumb,
        .site-address,
        .url-bar,
        .wp-toolbar,
        #wpadminbar,
        .admin-bar,
        .lbi-breadcrumbs,
        #header,
        #headerimg,
        body > hr {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Non-homepage pages: show nav from top */
        body:not(.cd-homepage) #cdTopNav {
            opacity: 1 !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            top: 0 !important;
        }

        /* Homepage pages: show nav on scroll */
        body.cd-scrolled #cdTopNav {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }        /* Homepage pages: show nav on scroll */
        body.cd-scrolled #cdTopNav {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        #cdTopNavInner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 18px 18px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        #cdTopBrand {
            font-size: 1.1rem;
            font-weight: 800;
            color: #111111;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        #cdTopBrand .gold {
            color: #b08a3c;
        }

        #cdTopLinks {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .cd-top-btn {
            padding: 12px 18px;
            border-radius: 999px;
            border: 1px solid #d6c29a;
            background: linear-gradient(180deg, #ffffff 0%, #fbf9f3 100%);
            color: #111111;
            text-decoration: none;
            font-size: 0.90rem;
            font-weight: 700;
            transition: transform 0.26s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.26s ease, border-color 0.26s ease;
            line-height: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .cd-top-btn:hover {
            transform: translateY(-1px);
            border-color: #b08a3c;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .cd-top-btn.cd-top-btn-gold {
            background: linear-gradient(145deg, #c8a25b 0%, #a57b2f 100%);
            color: #ffffff;
            border-color: #a57b2f;
        }

        @keyframes cdFloatGlass {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes cdWaterDriftA {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(6%, 4%) scale(1.08); }
        }

        @keyframes cdWaterDriftB {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-7%, -5%) scale(1.09); }
        }

        @media (prefers-reduced-motion: reduce) {
            #cdHeroInner,
            #cdHeroHeader::before,
            #cdHeroHeader::after {
                animation: none !important;
            }
        }

        @media (max-width: 840px) {
            #cdHeroTitle { font-size: 2.2rem; }
            #cdTopNavInner { align-items: flex-start; flex-direction: column; }
            #cdTopLinks { width: 100%; justify-content: flex-start; }

            /* Mobile: hide theme header */
            #header,
            #headerimg,
            body > hr {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                clip-path: inset(100%) !important;
                position: absolute !important;
                left: -9999px !important;
            }
        }

        @media (max-width: 768px) {
            body:not(.cd-homepage) {
                padding-top: 110px;
            }

            /* Mobile: explicitly hide theme header */
            #header,
            #headerimg,
            body > hr {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                clip-path: inset(100%) !important;
                position: absolute !important;
                left: -9999px !important;
            }
        }
    </style>

    <div id="cdHeroHeader" aria-hidden="true">
        <div id="cdHeroInner">
            <h1 id="cdHeroTitle">Cairde <span class="gold">Designs</span></h1>
            <p id="cdHeroTag">Local Business Storytelling</p>
            <div id="cdHeroButtons">
                <a class="cd-btn" href="<?php echo esc_url($home . 'directory/'); ?>">Directory</a>
                <a class="cd-btn" href="<?php echo esc_url($home . 'submit-interview/'); ?>">Submit Interview</a>
                <a class="cd-btn cd-btn-gold" href="<?php echo esc_url($home . 'recommend/'); ?>">Recommend Business</a>
            </div>
        </div>
    </div>

    <nav id="cdTopNav" aria-label="Cairde site navigation">
        <div id="cdTopNavInner">
            <div id="cdTopBrand">Cairde <span class="gold">Designs</span></div>
            <div id="cdTopLinks">
                <a class="cd-top-btn" href="<?php echo esc_url($home . 'directory/'); ?>">Directory</a>
                <a class="cd-top-btn" href="<?php echo esc_url($home . 'submit-interview/'); ?>">Submit Interview</a>
                <a class="cd-top-btn cd-top-btn-gold" href="<?php echo esc_url($home . 'recommend/'); ?>">Recommend Business</a>
            </div>
        </div>
    </nav>

    <script>
        (function () {
            // Add homepage class if on front page
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    var isHomepage = <?php echo $is_homepage ? 'true' : 'false'; ?>;
                    if (isHomepage) {
                        document.body.classList.add('cd-homepage');
                    }
                });
            } else {
                var isHomepage = <?php echo $is_homepage ? 'true' : 'false'; ?>;
                if (isHomepage) {
                    document.body.classList.add('cd-homepage');
                }
            }

            // Scroll listener for sticky nav on homepage
            // Trigger after minimal scroll (2% of viewport) for early fade
            var threshold = window.innerHeight * 0.02;
            
            function onScroll() {
                var isHomepage = document.body.classList.contains('cd-homepage');
                if (isHomepage) {
                    if (window.pageYOffset >= threshold) {
                        document.body.classList.add('cd-scrolled');
                    } else {
                        document.body.classList.remove('cd-scrolled');
                    }
                }
            }
            
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', function () {
                threshold = window.innerHeight * 0.02;
                onScroll();
            });
            onScroll();
        })();
    </script>
    <?php
}, 99999);
