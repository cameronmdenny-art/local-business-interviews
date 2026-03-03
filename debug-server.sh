#!/bin/bash

export FTP_PASSWORD='VinU9Y8kmNr!2l*0'

echo "🧹 Cleaning server cache and checking files..."
echo ""

# Download the current main plugin file from server
echo "📥 Downloading current local-business-interviews.php from server..."
curl -s "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php" \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    > /tmp/server-plugin.php

echo "Checking line 15 on server:"
sed -n '15p' /tmp/server-plugin.php

echo ""
echo "Checking line 15 locally:"
sed -n '15p' local-business-interviews.php

echo ""
echo "📥 Downloading current lbi-force-activate.php from server..."
curl -s "ftp://185.164.108.209/public_html/wp-content/mu-plugins/lbi-force-activate.php" \
    -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
    > /tmp/server-mu-plugin.php

echo "First 20 lines of MU plugin on server:"
head -20 /tmp/server-mu-plugin.php
