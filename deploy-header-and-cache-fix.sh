#!/bin/bash

# Deploy Header Fix + Cache Bypass to Hostinger
# This script:
# 1. Deploys .htaccess with LiteSpeed cache bypass rules
# 2. Deploys MU plugin to wp-content/mu-plugins/
# 3. Clears LiteSpeed cache

set -e

echo "🚀 Deploying Header Fix + Cache Bypass..."

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Hostinger FTP credentials
FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
PLUGIN_PATH="/public_html/wp-content/plugins/local-business-interviews"
MU_PLUGINS_PATH="/public_html/wp-content/mu-plugins"

# Load FTP password from .env
if [ -f ".env" ]; then
    source .env
fi

if [ -z "$FTP_PASSWORD" ]; then
    echo -e "${RED}Error: FTP_PASSWORD not found in .env${NC}"
    exit 1
fi

# Step 1: Deploy .htaccess to plugin directory
echo -e "${BLUE}📄 Uploading .htaccess with cache bypass rules...${NC}"
curl -s --ftp-create-dirs \
     -T ".htaccess" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST${PLUGIN_PATH}/.htaccess"
echo -e "${GREEN}✅ .htaccess uploaded${NC}"

# Step 2: Deploy mu-plugin-header.php to mu-plugins directory
echo -e "${BLUE}📄 Uploading MU plugin to mu-plugins directory...${NC}"
curl -s --ftp-create-dirs \
     -T "mu-plugin-header.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST${MU_PLUGINS_PATH}/cairde-header-injector.php"
echo -e "${GREEN}✅ MU plugin uploaded${NC}"

# Step 3: Deploy the main plugin files to ensure latest version
echo -e "${BLUE}📄 Uploading main plugin file...${NC}"
curl -s -T "local-business-interviews.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST${PLUGIN_PATH}/local-business-interviews.php"

echo -e "${BLUE}📄 Uploading header-injector.php...${NC}"
curl -s --ftp-create-dirs \
     -T "includes/header-injector.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST${PLUGIN_PATH}/includes/header-injector.php"
echo -e "${GREEN}✅ Plugin files uploaded${NC}"

# Step 4: Create and upload cache purge script
echo -e "${BLUE}🗑️  Creating cache purge script...${NC}"
cat > /tmp/purge-litespeed-cache.php << 'EOF'
<?php
/**
 * LiteSpeed Cache Purge Script
 * Clears all LiteSpeed cache
 */

// Purge all LiteSpeed cache
if (function_exists('litespeed_purge_all')) {
    litespeed_purge_all();
    echo "✅ LiteSpeed cache purged via plugin function\n";
}

// Alternative: Send purge header
header('X-LiteSpeed-Purge: *');
echo "✅ LiteSpeed purge header sent\n";

// Delete this script after execution
@unlink(__FILE__);
echo "✅ Purge script self-deleted\n";
?>
EOF

curl -s -T "/tmp/purge-litespeed-cache.php" \
     -u "$FTP_USER:$FTP_PASSWORD" \
     "ftp://$FTP_HOST/public_html/purge-cache-now.php"
echo -e "${GREEN}✅ Cache purge script uploaded${NC}"

# Step 5: Execute the cache purge script
echo -e "${BLUE}🔄 Executing cache purge...${NC}"
PURGE_RESULT=$(curl -s "https://ivory-lark-138468.hostingersite.com/purge-cache-now.php" || echo "Purge request sent")
echo -e "${GREEN}$PURGE_RESULT${NC}"

# Step 6: Verify deployment
echo ""
echo -e "${YELLOW}🔍 Verifying deployment...${NC}"
echo -e "${BLUE}Checking homepage headers (should show no-cache)...${NC}"
sleep 2
curl -sI "https://ivory-lark-138468.hostingersite.com/" | grep -i "cache\|age" || echo "No cache headers found (good!)"

echo ""
echo -e "${GREEN}✨ DEPLOYMENT COMPLETE!${NC}"
echo -e "${GREEN}========================================${NC}"
echo -e "${YELLOW}What was deployed:${NC}"
echo -e "  ✅ .htaccess with LiteSpeed cache bypass"
echo -e "  ✅ MU plugin (cairde-header-injector.php)"
echo -e "  ✅ Latest plugin files"
echo -e "  ✅ LiteSpeed cache purged"
echo ""
echo -e "${BLUE}🌐 View your site:${NC}"
echo -e "   https://ivory-lark-138468.hostingersite.com/"
echo ""
echo -e "${YELLOW}💡 If header still doesn't show:${NC}"
echo -e "   1. Hard refresh browser (Cmd+Shift+R)"
echo -e "   2. Check in incognito/private window"
echo -e "   3. Wait 30-60 seconds for cache to clear"
