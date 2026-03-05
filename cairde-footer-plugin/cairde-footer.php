<?php
/**
 * Plugin Name: Cairde Footer Injector
 * Description: Clean modern footer for Cairde Designs
 * Version: 3.0.0
 * Author: Cairde Designs
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_footer', 'cairde_inject_footer', 99999);

function cairde_inject_footer() {
    if (is_admin()) {
        return;
    }

    $home_url = esc_url(home_url('/'));
    ?>
    <style>
        #cairde-footer-container { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', sans-serif; 
            background: #ffffff; 
            border-top: 1px solid rgba(176, 138, 60, 0.15);
            padding: 80px 20px; 
            margin-top: 120px; 
            color: #111111;
            position: relative;
            overflow: hidden;
        }
        
        /* Luxury ambient animation */
        #cairde-footer-container::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(176, 138, 60, 0.06) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            animation: footerDrift 18s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        #cairde-footer-wrapper { 
            max-width: 1200px; 
            margin: 0 auto; 
            position: relative;
            z-index: 1;
        }
        
        .footer-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 60px; 
            margin-bottom: 60px; 
        }
        
        .footer-column {
            transition: transform 0.34s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .footer-column:hover {
            transform: translateY(-3px);
        }
        
        .footer-column h3 { 
            font-size: 13px; 
            font-weight: 700; 
            letter-spacing: 0.8px; 
            text-transform: uppercase; 
            color: #b08a3c; 
            margin: 0 0 25px 0; 
        }
        
        .footer-column ul { 
            list-style: none; 
            margin: 0; 
            padding: 0; 
        }
        
        .footer-column li { 
            margin-bottom: 15px; 
            line-height: 1.6; 
        }
        
        .footer-column a { 
            color: #111111; 
            text-decoration: none; 
            font-size: 14px; 
            border-bottom: 1px solid transparent;
            transition: all 0.26s cubic-bezier(0.22, 1, 0.36, 1);
            display: inline-block;
        }
        
        .footer-column a:hover { 
            color: #b08a3c; 
            border-bottom-color: #b08a3c;
            transform: translateX(3px);
        }
        
        .footer-about-title { 
            font-size: 20px; 
            font-weight: 800; 
            letter-spacing: -0.5px; 
            margin: 0 0 15px 0; 
            color: #111111;
        }
        
        .footer-about-title .gold {
            color: #b08a3c;
        }
        
        .footer-divider { 
            height: 1px; 
            background: linear-gradient(90deg, transparent, rgba(176, 138, 60, 0.2), transparent); 
            margin: 60px 0; 
        }
        
        .footer-bottom { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap; 
            gap: 30px; 
        }
        
        .footer-legal { 
            display: flex; 
            gap: 30px; 
            flex-wrap: wrap; 
        }
        
        .footer-cta { 
            background: linear-gradient(135deg, #c8a25b 0%, #a57b2f 100%);
            padding: 50px 40px; 
            border-radius: 16px; 
            text-align: center; 
            margin: 60px 0; 
            grid-column: 1 / -1;
            box-shadow: 0 20px 50px rgba(176, 138, 60, 0.25);
            position: relative;
            overflow: hidden;
            animation: footerFloat 6s ease-in-out infinite;
        }
        
        .footer-cta::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0) 70%);
            transform: translateX(-100%);
            transition: transform 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .footer-cta:hover::before {
            transform: translateX(100%);
        }
        
        .footer-cta h3 { 
            color: #ffffff; 
            font-size: 24px; 
            font-weight: 800;
            margin: 0 0 15px 0; 
            text-transform: none; 
            position: relative;
            z-index: 1;
        }
        
        .footer-cta p { 
            color: rgba(255, 255, 255, 0.95); 
            font-size: 15px; 
            margin: 0 0 28px 0; 
            max-width: 500px; 
            margin-left: auto; 
            margin-right: auto; 
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }
        
        .footer-cta-btn { 
            display: inline-block; 
            background: #ffffff; 
            color: #a57b2f; 
            padding: 14px 36px; 
            border-radius: 999px; 
            font-weight: 700; 
            font-size: 14px; 
            text-decoration: none; 
            border: 2px solid #ffffff;
            transition: all 0.34s cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }
        
        .footer-cta-btn:hover { 
            background: transparent; 
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }
        
        @keyframes footerDrift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, 20px) scale(1.1); }
        }
        
        @keyframes footerFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        @media (max-width: 768px) { 
            #cairde-footer-container { 
                padding: 50px 20px; 
                margin-top: 60px; 
            } 
            .footer-grid { 
                gap: 40px; 
            } 
            .footer-bottom { 
                flex-direction: column; 
                align-items: flex-start; 
                gap: 20px; 
            } 
            .footer-cta { 
                padding: 40px 30px; 
            }
            .footer-column:hover {
                transform: none;
            }
        }
    </style>

    <footer id="cairde-footer-container">
      <div id="cairde-footer-wrapper">
        <div class="footer-grid">
          <div class="footer-column" style="max-width:300px;">
            <h3 style="text-transform:none;color:#b08a3c;font-size:11px;letter-spacing:1.2px;">CAIRDE DESIGNS</h3>
            <div class="footer-about-title">Cairde <span class="gold">Designs</span></div>
            <p style="font-size:14px;color:#444444;line-height:1.8;margin:0;">Connecting local businesses with meaningful stories, one interview at a time.</p>
          </div>

          <div class="footer-column">
            <h3>Explore</h3>
            <ul>
              <li><a href="<?php echo esc_url($home_url . '#directory'); ?>">Business Directory</a></li>
              <li><a href="<?php echo esc_url($home_url . '#interviews'); ?>">Featured Interviews</a></li>
              <li><a href="<?php echo esc_url($home_url . '#categories'); ?>">Browse Categories</a></li>
              <li><a href="<?php echo esc_url($home_url . '#latest'); ?>">Latest Stories</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Get Involved</h3>
            <ul>
              <li><a href="<?php echo esc_url($home_url . 'submit-interview/'); ?>">Submit an Interview</a></li>
              <li><a href="<?php echo esc_url($home_url . 'recommend/'); ?>">Recommend a Business</a></li>
              <li><a href="<?php echo esc_url($home_url . 'contact/'); ?>">Partnership Inquiries</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Resources</h3>
            <ul>
              <li><a href="<?php echo esc_url($home_url . 'blog/'); ?>">Blog & Insights</a></li>
              <li><a href="<?php echo esc_url($home_url . 'faq/'); ?>">FAQ</a></li>
              <li><a href="<?php echo esc_url($home_url . 'contact/'); ?>">Support Center</a></li>
            </ul>
          </div>

          <div class="footer-cta">
            <h3>Start Your Story</h3>
            <p>Join our community and share your business journey with thousands of potential customers and partners.</p>
            <a href="<?php echo esc_url($home_url . 'submit-interview/'); ?>" class="footer-cta-btn">Submit Interview</a>
          </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
          <div style="font-size:12px;color:#999;">© <?php echo esc_html(date('Y')); ?> Cairde Designs. All rights reserved.</div>
          <div class="footer-legal">
            <a href="<?php echo esc_url($home_url . 'privacy-policy/'); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url($home_url . 'terms/'); ?>">Terms of Service</a>
            <a href="<?php echo esc_url($home_url . 'contact/'); ?>">Contact Us</a>
          </div>
        </div>
      </div>
    </footer>
    <?php
}
