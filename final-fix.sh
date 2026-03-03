#!/bin/bash

FTP_PASS='VinU9Y8kmNr!2l*0'

echo "Uploading fixed plugin..."
curl -T local-business-interviews.php \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASS" \
    "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php"

echo ""
echo "✅ Done! Refreshing your site..."
sleep 2
curl -s "https://ivory-lark-138468.hostingersite.com/" | head -5
