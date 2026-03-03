#!/bin/bash

# Deploy Header & Footer Updates + Fix Constant Warning
# Apple-inspired premium header and footer with glassmorphism

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"
REMOTE_BASE="/public_html/wp-content/plugins/local-business-interviews"

echo "=== Deploying Premium Header & Footer + Bug Fix ==="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Fix constant warning
echo "🔧 Uploading main plugin file (fixes constant warning)..."
curl -T local-business-interviews.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/local-business-interviews.php"

if [ $? -eq 0 ]; then
    echo "✅ Main plugin uploaded - Constant warning fixed!"
else
    echo "❌ Failed to upload main plugin"
    exit 1
fi

# Upload homepage with premium header and footer
echo ""
echo "📤 Uploading premium homepage.php..."
curl -T includes/homepage.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/includes/homepage.php"

if [ $? -eq 0 ]; then
    echo "✅ homepage.php uploaded successfully"
else
    echo "❌ Failed to upload homepage.php"
    exit 1
fi

echo ""
echo "🎉 DEPLOYMENT COMPLETE!"
echo "=================================================="
echo ""
echo "✅ Fixed Issues:"
echo "   • Removed 'Constant LBI_PLUGIN_DIR already defined' warning"
echo "   • Constants now check if defined before declaring"
echo ""
echo "🌟 New Premium Header Features:"
echo "   • Glassmorphism blur backdrop (rgba + backdrop-filter)"
echo "   • Smooth fade-in animation from top"
echo "   • Nav links with animated underline on hover"
echo "   • Gold accent color on hover"
echo "   • Sticky positioning with enhanced shadows"
echo "   • Fully responsive mobile layout"
echo ""
echo "✨ New Premium Footer Features:"
echo ""
echo "   📐 Layout:"
echo "      • 4-column grid (auto-responsive)"
echo "      • Dark gradient background matching hero"
echo "      • Animated gold top border"
echo ""
echo "   🔗 Content:"
echo "      • About Us section"
echo "      • Quick Links"
echo "      • Category directory"
echo "      • Social media icons"
echo "      • Copyright & legal links"
echo ""
echo "   💫 Animations:"
echo "      • Fade-in on scroll"
echo "      • Links slide right on hover"
echo "      • Social icons lift and rotate"
echo "      • Smooth color transitions"
echo ""
echo "   🎨 Apple-Inspired Design:"
echo "      • Cubic-bezier easing (0.4, 0, 0.2, 1)"
echo "      • Subtle hover transforms"
echo "      • Gold accent highlights"
echo "      • Clean typography"
echo "      • Perfect spacing"
echo ""
echo "   📱 Mobile Optimized:"
echo "      • Single column layout on mobile"
echo "      • Centered social icons"
echo "      • Touch-friendly tap targets"
echo "      • Responsive padding"
echo ""
echo "📝 What to do next:"
echo "   1. Hard refresh your homepage (Cmd+Shift+R / Ctrl+Shift+R)"
echo "   2. Warning message should be gone!"
echo "   3. Scroll down to see the new premium footer"
echo "   4. Hover over header nav links (underline animates in)"
echo "   5. Hover over footer links (slides right with arrow)"
echo "   6. Click social icons (lift + rotate animation)"
echo "   7. Test on mobile - everything is responsive!"
echo ""
echo "💡 Pro Tips:"
echo "   • Header has glassmorphism - looks amazing over scrolling content"
echo "   • Footer gradient matches your hero section for consistency"
echo "   • All animations respect prefers-reduced-motion"
echo "   • GPU-accelerated for smooth 60fps"
echo ""
echo "Deployment finished at: $(date '+%Y-%m-%d %H:%M:%S')"
