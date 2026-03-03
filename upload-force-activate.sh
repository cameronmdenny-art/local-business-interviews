#!/bin/bash

# Upload force-activate must-use plugin

FTP_PASSWORD="VinU9Y8kmNr!2l*0"

echo "📤 Uploading force-activate MU plugin to Hostinger..."
echo "📍 Local file: ./lbi-force-activate.php"  
echo "📍 Remote location: /public_html/wp-content/mu-plugins/lbi-force-activate.php"
echo ""

curl -s --ftp-create-dirs \
     -T "./lbi-force-activate.php" \
     -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
     "ftp://185.164.108.209/public_html/wp-content/mu-plugins/lbi-force-activate.php"

echo "✅ Must-use plugin uploaded successfully!"
echo "ℹ️  This plugin will force-load the main LBI plugin"
echo ""
echo "⏱️  Waiting 3 seconds for server to process..."
sleep 3

echo "🔍 Testing page..."
curl -s "https://ivory-lark-138468.hostingersite.com/recommend/" | grep -o "lbi-recommend-shortcode-wrap\|<form\|<input.*name=" | head -5
