#!/bin/bash

# Quick Deploy for Local Business Interviews Plugin
# This script builds the plugin and provides the upload path

set -e

echo "🔨 Building plugin package..."

# Create dist directory if it doesn't exist
mkdir -p dist

# Plugin name and version
PLUGIN_NAME="local-business-interviews"
VERSION=$(grep "Version:" local-business-interviews.php | head -1 | awk '{print $3}')
ZIP_NAME="${PLUGIN_NAME}-${VERSION}.zip"

# Remove old zip if exists
rm -f "dist/${ZIP_NAME}"

# Create zip excluding development files
zip -r "dist/${ZIP_NAME}" . \
    -x "*.git*" \
    -x "dist/*" \
    -x "*.DS_Store" \
    -x "build-release.sh" \
    -x "quick-deploy.sh" \
    -x "deploy-to-hostinger.sh" \
    -x "GITHUB_DEPLOY_SETUP.md" \
    -x ".editorconfig" \
    > /dev/null 2>&1

echo "✅ Plugin built successfully!"
echo ""
echo "📦 Package location:"
echo "   $(pwd)/dist/${ZIP_NAME}"
echo ""
echo "🚀 To deploy:"
echo "   1. Go to: https://ivory-lark-138468.hostingersite.com/wp-admin/plugin-install.php?tab=upload"
echo "   2. Drag and drop the zip file above"
echo "   3. Click 'Replace current with uploaded'"
echo "   4. Done! Refresh your site to see changes"
echo ""

# Open the WordPress upload page automatically
echo "Opening WordPress plugin upload page..."
open "https://ivory-lark-138468.hostingersite.com/wp-admin/plugin-install.php?tab=upload"

# Open the dist folder so user can grab the zip
echo "Opening plugin package folder..."
open "$(pwd)/dist"
