#!/bin/bash

export FTP_PASSWORD='VinU9Y8kmNr!2l*0'

echo "=== Deploying Warning Fix (Suppress Frontend Warnings) ==="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Upload MU plugin with warning suppression
echo "📤 Uploading MU plugin with frontend warning suppression..."
curl -T lbi-force-activate.php \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    "ftp://185.164.108.209/public_html/wp-content/mu-plugins/lbi-force-activate.php"

if [ $? -eq 0 ]; then
    echo "✅ MU plugin uploaded"
else
    echo "❌ Failed to upload MU plugin"
    exit 1
fi

# Upload main plugin with @ suppression
echo ""
echo "📤 Uploading main plugin file (v1.0.2 with @ suppression)..."
curl -T local-business-interviews.php \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php"

if [ $? -eq 0 ]; then
    echo "✅ Main plugin uploaded"
else
    echo "❌ Failed to upload main plugin"
    exit 1
fi

echo ""
echo "🎉 DEPLOYMENT COMPLETE!"
echo "=================================================="
echo ""
echo "✅ Fix Applied:"
echo "   • Frontend warnings are now suppressed"
echo "   • Warnings still visible in WordPress admin (for debugging)"
echo "   • @ error suppression added to constant defines"
echo "   • Version bumped to 1.0.2 to bust any opcache"
echo ""
echo "📝 What this does:"
echo "   • MU plugin now sets error_reporting to hide warnings on frontend"
echo "   • Admin panel still shows warnings (important for debugging)"
echo "   • Constants use @ suppression as backup"
echo "   • Professional, clean homepage - no warnings visible"
echo ""
echo "🌐 Test it:"
echo "   Hard refresh your homepage - warning should be GONE!"
echo "   (Cmd+Shift+R or Ctrl+Shift+R)"
echo ""
echo "Deployment finished at: $(date '+%Y-%m-%d %H:%M:%S')"
