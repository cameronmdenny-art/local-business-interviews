#!/bin/bash

# Clean reinstall of plugin

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"

echo "🗑️  Deleting old plugin folder..."

# Delete the old folder via FTP
curl -v --quote "RMD /public_html/wp-content/plugins/local-business-interviews" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/" 2>&1 | tail -5

echo ""
echo "📤 Uploading fresh plugin files..."

# Run the normal deployment
bash deploy-to-hostinger.sh

echo ""
echo "✅ Done! Now go to WordPress admin:"
echo "   👉 Plugins → Installed Plugins"
echo "   👉 Find 'Local Business Interviews'"
echo "   👉 Click 'Activate'"
