#!/bin/bash

# Quick deploy of MU plugin to Hostinger

set -e

echo "🚀 Deploying Cairde Header MU Plugin..."

# FTP credentials
FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
MU_PLUGINS_PATH="/public_html/wp-content/mu-plugins"

# Load password from .env
if [ -f ".env" ]; then
    source .env
fi

if [ -z "$FTP_PASSWORD" ]; then
    echo "Error: FTP_PASSWORD not found"
    exit 1
fi

echo "📤 Uploading mu-cairde-header.php..."
curl --ftp-create-dirs \
     -T "mu-cairde-header.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST${MU_PLUGINS_PATH}/cairde-header-injector.php"

echo ""
echo "✅ MU plugin deployed!"
echo "🌐 Check: https://ivory-lark-138468.hostingersite.com/"
echo "📝 Console log should show: '✅ Cairde Designs header injected by MU plugin'"
