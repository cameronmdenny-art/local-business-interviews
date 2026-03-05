#!/bin/bash
# Deploy and execute LiteSpeed purge script

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
source .env

echo "📤 Uploading purge script..."
curl -s -T "litespeed-purge.php" -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST/public_html/litespeed-purge.php"

echo "✅ Uploaded!"
echo ""
echo "🔄 Executing purge script..."
echo "🌐 Visit: https://ivory-lark-138468.hostingersite.com/litespeed-purge.php"
echo ""

# Open in browser
open "https://ivory-lark-138468.hostingersite.com/litespeed-purge.php" 2>/dev/null || echo "Visit the URL above in your browser"
