#!/bin/bash

# Update MU plugin with cleaned version

export FTP_PASSWORD="VinU9Y8kmNr!2l*0"

curl -s -T "./lbi-force-activate.php" \
     -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
     "ftp://185.164.108.209/public_html/wp-content/mu-plugins/lbi-force-activate.php"

echo "✅ Updated MU plugin (removed activation warnings)"
echo ""
echo "Now refresh the WordPress Plugins page - the warnings should be gone!"
