#!/bin/bash
set -e

source .env

echo "Uploading mu-header-v2.php..."
curl -T mu-header-v2.php \
  -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
  ftp://185.164.108.209/public_html/wp-content/mu-plugins/cairde-header-injector.php

echo ""
echo "✅ MU Plugin v2 Deployed!"
echo ""
echo "Testing on live site..."
sleep 3

# Test for the header
if curl -s "https://ivory-lark-138468.hostingersite.com/" | grep -q "Cairde Designs"; then
    echo "✅ SUCCESS! Cairde header is showing!"
    echo ""
    echo "🎉 Visit: https://ivory-lark-138468.hostingersite.com/"
else
    echo "⏳ Header not visible yet (may need page refresh)"
    echo "Try in browser: https://ivory-lark-138468.hostingersite.com/"
fi
