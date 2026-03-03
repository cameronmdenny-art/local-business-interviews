#!/bin/bash

# Upload delete script to wp-content

FTP_PASSWORD="VinU9Y8kmNr!2l*0"

echo "📤 Uploading delete script..."

curl -s -T "./delete-lbi-plugin.php" \
     -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
     "ftp://185.164.108.209/public_html/wp-content/delete-lbi-plugin.php"

echo ""
echo "✅ Script uploaded!"
echo ""
echo "🌐 Now visit this URL in your browser:"
echo "   👉 https://ivory-lark-138468.hostingersite.com/wp-content/delete-lbi-plugin.php"
echo ""
echo "This will delete the old plugin folder so you can install the ZIP."
