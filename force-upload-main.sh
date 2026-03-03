#!/bin/bash

# Force upload the fixed main plugin file

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"
REMOTE_FILE="/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php"
LOCAL_FILE="./local-business-interviews.php"

echo "🗑️  Deleting old file from server..."
curl -s --quote "DELE /public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/" || echo "File may not exist"

echo "📤 Uploading fixed file..."
curl -s --ftp-create-dirs \
     -T "$LOCAL_FILE" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST$REMOTE_FILE" && echo "✅ Uploaded successfully!"

echo "💾 Clearing PHP opcache..."
curl -s "https://ivory-lark-138468.hostingersite.com/?opcache_reset=1" > /dev/null

echo "✅ Done! Wait 5 seconds for PHP to reload..."
sleep 5

echo "🔍 Testing page..."
curl -s "https://ivory-lark-138468.hostingersite.com/recommend/" | grep -i "parse error\|lbi-recommend-shortcode-wrap" | head -2
