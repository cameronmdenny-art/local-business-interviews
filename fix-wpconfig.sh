#!/bin/bash

export FTP_PASSWORD='VinU9Y8kmNr!2l*0'

echo "=== Modifying wp-config.php to disable warnings ==="
echo ""

# Download wp-config.php
echo "📥 Downloading wp-config.php..."
curl -s "ftp://185.164.108.209/public_html/wp-config.php" \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    > /tmp/wp-config-original.php

if [ ! -f /tmp/wp-config-original.php ]; then
    echo "❌ Failed to download wp-config.php"
    exit 1
fi

echo "✅ Downloaded"
echo ""

# Check if our error suppression is already there
if grep -q "lbi-custom-error-handling" /tmp/wp-config-original.php; then
    echo "ℹ️  Error handling already configured in wp-config.php"
    exit 0
fi

# Add error suppression right after the opening <?php tag
echo "✏️  Adding error suppression to wp-config.php..."

# Create modified version
head -1 /tmp/wp-config-original.php > /tmp/wp-config-modified.php
cat >> /tmp/wp-config-modified.php << 'PHPCODE'

/**
 * Custom Error Handling - LBI Plugin
 * Suppress warnings on frontend (added 2026-03-02)
 * lbi-custom-error-handling
 */
if ( ! defined( 'WP_CLI' ) && php_sapi_name() !== 'cli' ) {
    // Suppress warnings on frontend only
    if ( ! isset( $_SERVER['REQUEST_URI'] ) || strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) === false ) {
        @ini_set( 'display_errors', 0 );
        @error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR );
    }
}

PHPCODE

# Append the rest of the original file (skip first line)
tail -n +2 /tmp/wp-config-original.php >> /tmp/wp-config-modified.php

echo "📤 Uploading modified wp-config.php..."
curl -T /tmp/wp-config-modified.php \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    "ftp://185.164.108.209/public_html/wp-config.php"

if [ $? -eq 0 ]; then
    echo "✅ wp-config.php updated successfully"
    echo ""
    echo "🎉 Fix applied! Warnings will now be suppressed on frontend."
    echo "   Test by refreshing your homepage."
else
    echo "❌ Failed to upload wp-config.php"
    exit 1
fi
