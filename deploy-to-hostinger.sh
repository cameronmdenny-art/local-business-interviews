#!/bin/bash

# Deploy Local Business Interviews Plugin to Hostinger via FTP
# Usage: ./deploy-to-hostinger.sh

set -e

echo "🚀 Deploying Local Business Interviews Plugin to Hostinger..."

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Hostinger FTP credentials
FTP_HOST="185.164.108.209"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
REMOTE_PATH="/public_html/wp-content/plugins/local-business-interviews"

# Load FTP password from .env file if it exists
if [ -f ".env" ]; then
    source .env
fi

# Prompt for FTP password if still not set
if [ -z "$FTP_PASSWORD" ]; then
    echo -e "${BLUE}Enter your Hostinger FTP password:${NC}"
    read -s FTP_PASSWORD
    echo ""
fi

# Create temporary directory for files to upload
TMP_DIR=$(mktemp -d)
echo -e "${BLUE}Preparing files for upload...${NC}"

# Copy all files except excluded ones
rsync -av \
    --exclude='.git/' \
    --exclude='.github/' \
    --exclude='dist/' \
    --exclude='.DS_Store' \
    --exclude='.gitignore' \
    --exclude='deploy-to-hostinger.sh' \
    --exclude='quick-deploy.sh' \
    --exclude='build-release.sh' \
    --exclude='GITHUB_DEPLOY_SETUP.md' \
    --exclude='DEPLOYMENT_WORKFLOW.md' \
    --exclude='.editorconfig' \
    --exclude='.env' \
    --exclude='*.backup' \
    ./ "$TMP_DIR/" > /dev/null

# Upload files using curl (FTP)
echo -e "${BLUE}Uploading to Hostinger...${NC}"

upload_directory() {
    local local_dir="$1"
    local remote_dir="$2"
    
    # Create remote directory
    curl -s --ftp-create-dirs \
         -u "$FTP_USER:$FTP_PASSWORD" \
         "ftp://$FTP_HOST$remote_dir/" > /dev/null 2>&1 || true
    
    # Upload all files in directory
    for file in "$local_dir"/*; do
        if [ -f "$file" ]; then
            local filename=$(basename "$file")
            echo -e "  Uploading: ${remote_dir}/${filename}"
            curl -s --ftp-create-dirs \
                 -T "$file" \
                 -u "$FTP_USER:$FTP_PASSWORD" \
                 "ftp://$FTP_HOST${remote_dir}/${filename}"
        elif [ -d "$file" ]; then
            local dirname=$(basename "$file")
            upload_directory "$file" "${remote_dir}/${dirname}"
        fi
    done
}

# Start recursive upload
upload_directory "$TMP_DIR" "$REMOTE_PATH"

# Clean up
rm -rf "$TMP_DIR"

echo -e "${GREEN}✅ Deployment complete!${NC}"
echo -e "${GREEN}View your site: https://ivory-lark-138468.hostingersite.com${NC}"
