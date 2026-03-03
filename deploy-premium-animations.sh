#!/bin/bash

# Deploy Premium Animations and Assets System to Hostinger
# This deploys the Apple-inspired animation system

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
REMOTE_BASE="/public_html/wp-content/plugins/local-business-interviews"

echo "=== Deploying Premium Animations & Assets System ==="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"

# Check if FTP_PASSWORD is set
if [ -z "$FTP_PASSWORD" ]; then
    FTP_PASSWORD="VinU9Y8kmNr!2l*0"
fi

# Upload animations CSS
echo ""
echo "📤 Uploading animations-premium.css..."
curl -s -T assets/css/animations-premium.css \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/assets/css/animations-premium.css"

if [ $? -eq 0 ]; then
    echo "✅ animations-premium.css uploaded successfully"
else
    echo "❌ Failed to upload animations-premium.css"
    exit 1
fi

# Upload assets.php
echo ""
echo "📤 Uploading assets.php..."
curl -s -T includes/assets.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/includes/assets.php"

if [ $? -eq 0 ]; then
    echo "✅ assets.php uploaded successfully"
else
    echo "❌ Failed to upload assets.php"
    exit 1
fi

# Upload updated main plugin file
echo ""
echo "📤 Uploading updated local-business-interviews.php..."
curl -s -T local-business-interviews.php \
    -u "$FTP_USER:$FTP_PASSWORD" \
    "ftp://$FTP_HOST$REMOTE_BASE/local-business-interviews.php"

if [ $? -eq 0 ]; then
    echo "✅ local-business-interviews.php uploaded successfully"
else
    echo "❌ Failed to upload local-business-interviews.php"
    exit 1
fi

echo ""
echo "🎉 DEPLOYMENT COMPLETE!"
echo "=================================================="
echo "✅ Premium animations CSS deployed"
echo "✅ Assets manager deployed"
echo "✅ Main plugin file updated"
echo ""
echo "🌟 Apple-Inspired Features Now Active:"
echo "   • Smooth scroll behavior"
echo "   • Fade-in animations for cards"
echo "   • Float effects on hover"
echo "   • Card pulse animations"
echo "   • Button ripple effects"
echo "   • Loading skeleton animations"
echo "   • Modal transitions"
echo "   • Notification slide-ins"
echo "   • Map marker animations"
echo "   • Staggered list reveals"
echo "   • GPU-accelerated transforms"
echo ""
echo "📝 What to do next:"
echo "   1. Clear browser cache (Cmd+Shift+R / Ctrl+Shift+R)"
echo "   2. Visit /recommend/ to see smooth form animations"
echo "   3. Visit /directory/ to see card animations"
echo "   4. Hover over elements to see lift effects"
echo ""
echo "💡 Performance optimizations included:"
echo "   • will-change properties for smooth animations"
echo "   • GPU acceleration (translateZ)"
echo "   • Reduced motion support for accessibility"
echo "   • Optimized repaint containment"
echo ""
echo "Deployment finished at: $(date '+%Y-%m-%d %H:%M:%S')"
