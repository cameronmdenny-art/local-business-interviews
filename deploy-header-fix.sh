#!/bin/bash

# Deploy Master Cleanup Mu-Plugins to Hostinger via curl/FTP
# This script uploads the new mu-plugins that fix all the header/theme issues

set -e

echo "🚀 Deploying Cairde Header Fixes to Hostinger..."

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Hostinger FTP credentials
FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
REMOTE_PATH="/public_html/"

# Load FTP password from .env file
if [ -f ".env" ]; then
    source .env
else
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Check if password is set
if [ -z "$FTP_PASSWORD" ]; then
    echo -e "${RED}Error: FTP_PASSWORD not set${NC}"
    exit 1
fi

echo -e "${BLUE}📤 Uploading mu-plugins via FTP...${NC}\n"

# Function to upload a file via FTP
upload_file() {
    local file_name="$1"
    local file_path="$2"
    
    if [ ! -f "$file_path" ]; then
        echo -e "${YELLOW}⚠ File not found: $file_name${NC}"
        return 1
    fi
    
    echo -n "  Uploading $file_name... "
    
    if curl -s --ftp-create-dirs \
         -T "$file_path" \
         -u "$FTP_USER:$FTP_PASSWORD" \
         "ftp://$FTP_HOST${REMOTE_PATH}${file_name}" \
         --ssl-reqd --cacert /dev/null 2>/dev/null; then
        echo -e "${GREEN}✓${NC}"
        return 0
    else
        # Try without SSL
        if curl -s --ftp-create-dirs \
             -T "$file_path" \
             -u "$FTP_USER:$FTP_PASSWORD" \
             "ftp://$FTP_HOST${REMOTE_PATH}${file_name}" 2>/dev/null; then
            echo -e "${GREEN}✓${NC}"
            return 0
        else
            echo -e "${RED}✗${NC}"
            return 1
        fi
    fi
}

# Upload the new mu-plugins
upload_file "mu-master-cleanup.php" "./mu-master-cleanup.php"
upload_file "mu-cairde-header-complete.php" "./mu-cairde-header-complete.php"

echo ""
echo -e "${BLUE}🔄 Purging site cache...${NC}"

# Purge the LiteSpeed cache
curl -X PURGE "https://ivory-lark-138468.hostingersite.com/" \
    -H "X-Purge-By: admin" \
    -k 2>/dev/null || true

echo -e "${GREEN}✅ Cache purged!${NC}"

echo ""
echo -e "${GREEN}🎉 Deployment complete!${NC}"
echo -e "${BLUE}📍 Visit: https://ivory-lark-138468.hostingersite.com/${NC}"
echo ""
echo -e "${YELLOW}✨ Changes applied:${NC}"
echo "   ✓ Master cleanup plugin (hides WordPress elements)"
echo "   ✓ Professional Cairde Designs header"
echo "   ✓ Clean navigation menu"
echo "   ✓ Hidden Hostinger domain references"
echo "   ✓ All conflicting old plugins disabled"
echo ""
