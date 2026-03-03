#!/bin/bash

# Remove the problematic MU plugin

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"

echo "🗑️ Removing MU plugins that might be interfering..."

curl -v --quote "DELE /public_html/wp-content/mu-plugins/lbi-force-activate.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/" 2>&1 | grep -E "DELE|250|550"

curl -v --quote "DELE /public_html/wp-content/mu-plugins/lbi-force-recommend-form.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/" 2>&1 | grep -E "DELE|250|550"

echo ""
echo "✅ MU plugins removed"
echo "🔄 Now refresh the WordPress Plugins page"
