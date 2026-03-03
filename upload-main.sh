#!/bin/bash

export FTP_PASSWORD='VinU9Y8kmNr!2l*0'

echo "Uploading fixed main plugin file..."
curl -T local-business-interviews.php \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php"

if [ $? -eq 0 ]; then
    echo "✅ File uploaded"
else
    echo "❌ Upload failed"
fi
