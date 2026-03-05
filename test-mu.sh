#!/bin/bash
# Upload test MU plugin
FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
source .env

echo "Uploading test MU plugin..."
curl -T "mu-test.php" -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST/public_html/wp-content/mu-plugins/mu-test.php"
echo ""
echo "✅ Test plugin uploaded"
echo "Checking if it works..."
sleep 2
curl -s "https://ivory-lark-138468.hostingersite.com/?test=$(date +%s)" | grep -i "MU_WORKING\|MU Plugins" || echo "❌ MU plugins not detected"
