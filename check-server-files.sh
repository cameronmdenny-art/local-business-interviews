#!/bin/bash

# Check if the main plugin file exists on server

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"

echo "📂 Checking plugin files on server..."
echo ""

curl -s -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/public_html/wp-content/plugins/local-business-interviews/" | head -20

echo ""
echo "---"
echo "Checking main plugin file specifically..."
curl -s --head -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php" | grep -E "Content-Length|550"
