#!/bin/bash

# Deploy Premium Homepage Animations
# Apple-inspired smooth animations for homepage

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"
REMOTE_BASE="/public_html/wp-content/plugins/local-business-interviews"

echo "=== Deploying Premium Homepage Animations ==="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Upload home-shortcode.php
echo "📤 Uploading home-shortcode.php..."
curl -T includes/home-shortcode.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/includes/home-shortcode.php"

if [ $? -eq 0 ]; then
    echo "✅ home-shortcode.php uploaded successfully"
else
    echo "❌ Failed to upload home-shortcode.php"
    exit 1
fi

# Upload homepage.php
echo ""
echo "📤 Uploading homepage.php..."
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
echo "🎉 HOMEPAGE ANIMATIONS DEPLOYED!"
echo "=================================================="
echo ""
echo "🌟 Apple-Inspired Features Now Active on Homepage:"
echo ""
echo "   ✨ Hero Section:"
echo "      • Smooth fade-in animation"
echo "      • Button with ripple effect on hover"
echo "      • Enhanced shadow and scale transforms"
echo ""
echo "   💫 Card Animations:"
echo "      • Staggered reveal (0.1s delays)"
echo "      • Float up 12px on hover"
echo "      • Scale transform (1.02)"
echo "      • Image zoom effect inside cards"
echo ""
echo "   🎯 Section Titles:"
echo "      • Animated underline expansion"
echo "      • Gradient gold accent bar"
echo "      • Smooth fade-in from below"
echo ""
echo "   🔗 Interactive Elements:"
echo "      • Arrow slides right on link hover"
echo "      • Smooth color transitions"
echo "      • Gap expansion animation"
echo ""
echo "   ⚡ Performance:"
echo "      • GPU-accelerated transforms"
echo "      • Backface visibility optimization"
echo "      • Reduced motion support for accessibility"
echo ""
echo "📝 What to do next:"
echo "   1. Clear browser cache (Cmd+Shift+R / Ctrl+Shift+R)"
echo "   2. Visit your homepage"
echo "   3. Watch cards fade in with stagger effect"
echo "   4. Hover over cards to see float animation"
echo "   5. Hover over links to see arrow slide"
echo ""
echo "💡 Tip: Test on mobile - all animations are responsive!"
echo ""
echo "Deployment finished at: $(date '+%Y-%m-%d %H:%M:%S')"
