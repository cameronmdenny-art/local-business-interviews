#!/bin/bash
# Simple version restore script - easily restore any saved version
# Usage: ./restore-version.sh "description-of-save"
# Example: ./restore-version.sh "dark header footer working"

if [ -z "$1" ]; then
  echo "❌ Usage: ./restore-version.sh \"description-of-save\""
  echo ""
  echo "Available saves:"
  git tag -l | grep "^save-" | sort
  exit 1
fi

DESCRIPTION="$1"
TAG_NAME="save-${DESCRIPTION// /-}"  # Replace spaces with dashes

cd "$(dirname "$0")" || exit 1

# Check if tag exists
if ! git rev-parse "$TAG_NAME" >/dev/null 2>&1; then
  echo "❌ Save not found: $TAG_NAME"
  echo ""
  echo "Available saves:"
  git tag -l | grep "^save-" | sort
  exit 1
fi

echo "🔄 Restoring version: $TAG_NAME"
echo ""

# Fetch latest tags
git fetch --all --tags

# Checkout the save
git checkout "$TAG_NAME"

echo ""
echo "✅ Version restored locally!"
echo ""
echo "Next steps:"
echo "1. Check that everything looks right"
echo "2. Merge back to main: git checkout main && git merge $TAG_NAME"
echo "3. Deploy: python3 /tmp/sync_tracked_only.py"
