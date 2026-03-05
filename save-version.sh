#!/bin/bash
# Simple version save script - avoids needing to remember git commands
# Usage: ./save-version.sh "what did you do"
# Example: ./save-version.sh "dark header footer working"

if [ -z "$1" ]; then
  echo "❌ Usage: ./save-version.sh \"description-of-changes\""
  echo "Example: ./save-version.sh \"dark header footer working\""
  exit 1
fi

DESCRIPTION="$1"
TAG_NAME="save-${DESCRIPTION// /-}"  # Replace spaces with dashes

echo "💾 Saving version: $TAG_NAME"
echo "Description: $DESCRIPTION"
echo ""

cd "$(dirname "$0")" || exit 1

# Commit changes
git add -A
git commit -m "Save: $DESCRIPTION"

# Create tag
git tag "$TAG_NAME"

# Push to GitHub
git push origin main
git push origin --tags

echo ""
echo "✅ Version saved! Restore later with:"
echo "   git checkout $TAG_NAME"
echo ""
echo "Or see all saves with:"
echo "   git tag -l | sort"
