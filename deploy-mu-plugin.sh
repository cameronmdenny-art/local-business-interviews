#!/bin/bash

# Upload must-use plugin to fix recommend form

FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASSWORD="VinU9Y8kmNr!2l*0"
LOCAL_FILE="./lbi-force-recommend-form.php"
REMOTE_DIR="/public_html/wp-content/mu-plugins"
REMOTE_FILE="lbi-force-recommend-form.php"

echo "📤 Uploading must-use plugin to Hostinger..."
echo "📍 Local file: $LOCAL_FILE"
echo "📍 Remote location: $REMOTE_DIR/$REMOTE_FILE"

# Create directory if needed
curl -s --ftp-create-dirs \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST$REMOTE_DIR/" > /dev/null 2>&1

# Upload file
curl -s --ftp-create-dirs \
     -T "$LOCAL_FILE" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST$REMOTE_DIR/$REMOTE_FILE"

echo ""
echo "✅ Must-use plugin uploaded successfully!"
echo "ℹ️  This plugin will run before all other WordPress code"
echo "⏱️  Clearing site cache now..."

# Clear site cache
curl -s "https://ivory-lark-138468.hostingersite.com/?lsi-cache-purge" > /dev/null

echo "✅ Cache cleared!"


