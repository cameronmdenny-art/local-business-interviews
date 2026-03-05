#!/bin/bash

source .env

echo "📤 Uploading Cairde Header Plugin..."
curl -T cairde-header-plugin/cairde-header.php \
  -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
  ftp://185.164.108.209/public_html/wp-content/plugins/cairde-header/cairde-header.php

echo ""
echo "✅ Plugin uploaded!"
echo ""
echo "📝 Next steps:"
echo "1. Go to WordPress admin: https://ivory-lark-138468.hostingersite.com/wp-admin"
echo "2. Navigate to Plugins"
echo "3. Find 'Cairde Header Injector'"
echo "4. Click 'Activate'"
echo ""
echo "Then visit the homepage and you should see the header!"
