#!/bin/bash

# Remove the MU plugin now that the main plugin works

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"

echo "🗑️  Removing MU plugin that was causing duplicate load..."

curl -s --quote "DELE /public_html/wp-content/mu-plugins/lbi-force-activate.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/" 2>&1 | grep -E "250|550"

echo ""
echo "✅ Done!"
echo ""
echo "Now:"
echo "1. Refresh the WordPress Plugins page"
echo "2. Find 'LBI Force Activate' and deactivate it if it shows"
echo "3. Clear browser cache"
echo "4. Visit: https://ivory-lark-138468.hostingersite.com/recommend/"
echo ""
echo "The form should still work without warnings!"
